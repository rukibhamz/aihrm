<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use built assets in production, dev server in development
        Vite::useBuildDirectory('build');
        
        // Prefetch DNS for external resources
        Vite::prefetch(concurrency: 3);
    }
}
