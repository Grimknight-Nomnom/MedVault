<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Pagination\Paginator; // <-- 1. Import Paginator

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
        // --- ADD THIS: Force Laravel to use Bootstrap 5 for Pagination ---
        Paginator::useBootstrapFive();
        // -----------------------------------------------------------------

        // Define the 'admin' Gate
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        // Define the 'staff' Gate
        Gate::define('staff', function (User $user) {
            return $user->role === 'staff';
        });

        // Your custom email verification logic
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Your Account - MedVault')
                ->view('emails.verify_account', [
                    'url' => $url,
                    'usernumber' => $notifiable->usernumber,
                    'email' => $notifiable->email,
                ]);
        });
    }
}