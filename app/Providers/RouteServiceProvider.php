<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

    }

    protected function configureRateLimiting(): void
    {
        // Force in-memory cache during unit tests
        if (app()->runningUnitTests()) {
            config(['cache.default' => 'array']);
        }

        // Login throttling: 5 attempts per minute per email+IP
        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(5)->by($request->input('email').'|'.$request->ip());
        });

        // Register throttling: 3 attempts per 10 minutes per IP
        RateLimiter::for('register', function ($request) {
            return Limit::perMinutes(10, 3)->by($request->ip());
        });
    }
}
