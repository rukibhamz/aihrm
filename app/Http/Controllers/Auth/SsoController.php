<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SsoController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Check if user exists by provider ID
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    // Link existing user
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_token' => $socialUser->token,
                        'provider_refresh_token' => $socialUser->refreshToken,
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(24)), // Random password
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_token' => $socialUser->token,
                        'provider_refresh_token' => $socialUser->refreshToken,
                    ]);
                    
                    // Assign default role if needed (e.g., Employee)
                    // $user->assignRole('Employee');
                }
            } else {
                // Update tokens
                $user->update([
                    'provider_token' => $socialUser->token,
                    'provider_refresh_token' => $socialUser->refreshToken,
                ]);
            }

            Auth::login($user);

            return redirect()->intended('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'SSO Login failed: ' . $e->getMessage()]);
        }
    }
}
