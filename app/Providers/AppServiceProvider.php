<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register SSO event listeners for community Socialite providers
        Event::listen(
            \SocialiteProviders\Manager\SocialiteWasCalled::class,
            \SocialiteProviders\Azure\AzureExtendSocialite::class . '@handle'
        );
        Event::listen(
            \SocialiteProviders\Manager\SocialiteWasCalled::class,
            \SocialiteProviders\Zoho\ZohoExtendSocialite::class . '@handle'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "Admin" role all permissions
        // This works in the app by using Gate::before rule; but Spatie typically uses its own Gate registration.
        // Ideally we use Gate::before allow.
        try {
            \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
                return $user->hasRole('Admin') ? true : null;
            });
        } catch (\Exception $e) {
            // Failsafe if role table not migrated yet
        }

        // Use built assets in production, dev server in development
        Vite::useBuildDirectory('build');
        
        // Prefetch DNS for external resources
        Vite::prefetch(concurrency: 3);

        // Load Mail Configuration from Settings
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $mailConfig = [
                    'transport' => 'smtp',
                    'host' => \App\Models\Setting::get('smtp_host'),
                    'port' => \App\Models\Setting::get('smtp_port', 587),
                    'encryption' => \App\Models\Setting::get('smtp_encryption', 'tls'),
                    'username' => \App\Models\Setting::get('smtp_username'),
                    'password' => \App\Models\Setting::get('smtp_password'),
                    'from' => [
                        'address' => \App\Models\Setting::get('smtp_from_address'),
                        'name' => \App\Models\Setting::get('smtp_from_name', 'AIHRM'),
                    ],
                ];

                if ($mailConfig['host']) {
                    config(['mail.mailers.smtp' => array_merge(config('mail.mailers.smtp', []), $mailConfig)]);
                    config(['mail.default' => 'smtp']);
                    config(['mail.from.address' => $mailConfig['from']['address']]);
                    config(['mail.from.name' => $mailConfig['from']['name']]);
                }
            }
        } catch (\Exception $e) {
            // Failsafe for migration
        }

        // Auto-run pending migrations on deployment
        $this->autoMigrate();
    }

    /**
     * Automatically run pending migrations once per deployment.
     * Uses a hash of migration files to detect new migrations.
     */
    protected function autoMigrate(): void
    {
        try {
            $migrationsPath = database_path('migrations');
            $hashFile = storage_path('framework/migration_hash.txt');

            // Build a hash from all migration filenames
            $files = glob($migrationsPath . '/*.php');
            $currentHash = md5(implode('|', array_map('basename', $files)));

            // Check if we already ran for this set of migrations
            $storedHash = file_exists($hashFile) ? file_get_contents($hashFile) : '';

            if ($currentHash !== $storedHash) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                file_put_contents($hashFile, $currentHash);
            }
        } catch (\Exception $e) {
            // Silently fail — DB might not be reachable yet
            \Illuminate\Support\Facades\Log::warning('Auto-migration failed: ' . $e->getMessage());
        }
    }

}
