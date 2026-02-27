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
    }
}
