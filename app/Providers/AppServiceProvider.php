<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate; // <-- 1. Import Gate
use App\Models\User;                 // <-- 2. Import User model

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
        // --- ADD THIS: Define the 'admin' Gate ---
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
        // -----------------------------------------

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