<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Auth\OptimizedEloquentUserProvider;

class AuthOptimizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register optimized Eloquent user provider
        Auth::provider('optimized-eloquent', function ($app, array $config) {
            return new OptimizedEloquentUserProvider(
                $app['hash'],
                $config['model']
            );
        });
    }
}
