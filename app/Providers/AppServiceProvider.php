<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckUserApproved;

class AppServiceProvider extends ServiceProvider
{

    protected $routeMiddleware = [
        // ... other middleware
        'approved' => \App\Http\Middleware\CheckUserApproved::class,
        'admin' => \App\Http\Middleware\CheckAdmin::class,
    ];
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
        //
    }
}
