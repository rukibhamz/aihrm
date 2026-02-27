<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SsoController extends Controller
{
    /**
     * Allowed SSO providers (whitelist)
     */
    protected array $validProviders = ['azure', 'google', 'zoho'];

    /**
     * Map provider names to Socialite driver names
     */
    protected array $driverMap = [
        'azure' => 'azure',
        'google' => 'google',
        'zoho' => 'zoho',
    ];

    /**
     * Inject provider configuration dynamically from settings
     */
    protected function injectProviderConfig(string $provider): void
    {
        $configKey = "services.{$provider}";
        
        config([
            "{$configKey}.client_id" => Setting::get("{$provider}_client_id"),
            "{$configKey}.client_secret" => Setting::get("{$provider}_client_secret"),
            "{$configKey}.redirect" => url("/auth/{$provider}/callback"),
        ]);

        if ($provider === 'azure') {
            config(["{$configKey}.tenant" => Setting::get('azure_tenant_id')]);
        }
    }

    /**
     * Redirect to the SSO provider's login page
     */
    public function redirectToProvider(string $provider)
    {
        if (!$this->isValidProvider($provider)) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid SSO provider.']);
        }

        if (!$this->isProviderEnabled($provider)) {
            return redirect()->route('login')->withErrors(['email' => ucfirst($provider) . ' SSO is not enabled. Contact your administrator.']);
        }

        $this->injectProviderConfig($provider);

        $driver = $this->driverMap[$provider] ?? $provider;

        return Socialite::driver($driver)->redirect();
    }

    /**
     * Handle the callback from the SSO provider
     */
    public function handleProviderCallback(string $provider)
    {
        if (!$this->isValidProvider($provider) || !$this->isProviderEnabled($provider)) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid or disabled SSO provider.']);
        }

        try {
            $this->injectProviderConfig($provider);
            
            $driver = $this->driverMap[$provider] ?? $provider;
            $socialUser = Socialite::driver($driver)->user();

            if (empty($socialUser->getEmail())) {
                return redirect()->route('login')->withErrors(['email' => 'Could not retrieve email from ' . ucfirst($provider) . '. Please ensure your account has an email address.']);
            }

            // Check if user exists by provider ID
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    // Link existing user to this SSO provider
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_token' => $socialUser->token,
                        'provider_refresh_token' => $socialUser->refreshToken,
                    ]);
                } else {
                    // Check if new user registration via SSO is allowed
                    $allowRegistration = Setting::get('sso_allow_registration', 'no');

                    if ($allowRegistration !== 'yes') {
                        return redirect()->route('login')->withErrors([
                            'email' => 'No employee account found with this email. Only existing employees can log in via SSO. Contact your HR administrator.'
                        ]);
                    }

                    // Create new user
                    $user = User::create([
                        'name' => $socialUser->getName() ?? $socialUser->getEmail(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(32)),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_token' => $socialUser->token,
                        'provider_refresh_token' => $socialUser->refreshToken,
                        'email_verified_at' => now(), // SSO-verified email
                    ]);

                    // Assign default Employee role
                    try {
                        $user->assignRole('Employee');
                    } catch (\Exception $e) {
                        Log::warning('Could not assign default role to SSO user: ' . $e->getMessage());
                    }
                }
            } else {
                // Update existing tokens
                $user->update([
                    'provider_token' => $socialUser->token,
                    'provider_refresh_token' => $socialUser->refreshToken,
                ]);
            }

            Auth::login($user, true); // Remember the user

            Log::info("SSO login successful via {$provider} for user {$user->email}");

            return redirect()->intended('dashboard');

        } catch (\Exception $e) {
            Log::error("SSO login failed via {$provider}: " . $e->getMessage());

            return redirect()->route('login')->withErrors([
                'email' => 'SSO Login failed. Please try again or use email/password login.'
            ]);
        }
    }

    /**
     * Check if the provider is in the whitelist
     */
    protected function isValidProvider(string $provider): bool
    {
        return in_array($provider, $this->validProviders);
    }

    /**
     * Check if the provider is enabled in settings
     */
    protected function isProviderEnabled(string $provider): bool
    {
        return Setting::get("sso_{$provider}_enabled", 'no') === 'yes';
    }
}
