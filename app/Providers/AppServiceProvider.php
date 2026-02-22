<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // 1. Import Gate
use App\Models\User; // 2. Import User model
use Illuminate\Pagination\Paginator; // <--- NEW: Import the Paginator

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
        // 3. Define the 'admin' gate
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        // 4. NEW: Force Bootstrap 5 Pagination Globally
        Paginator::useBootstrapFive();
    }
}