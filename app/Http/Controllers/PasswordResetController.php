<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class PasswordResetController extends Controller
{
    // 1. Show Email Form
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Send OTP to Email
    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $email = $request->email;
        $code = mt_rand(100000, 999999); 

        Cache::put('password_reset_' . $email, $code, now()->addMinutes(10));

        Mail::send('emails.reset_otp', ['code' => $code], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Your Password Reset Code - MedVault');
        });

        Session::put('reset_email', $email);

        return redirect()->route('password.verify');
    }

    // --- NEW: Resend the Code with 30s Cooldown ---
    public function resendCode(Request $request)
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.request');
        }

        $email = Session::get('reset_email');

        // 30 SECOND BACKEND COOLDOWN CHECK
        if (Cache::has('resend_reset_cooldown_' . $email)) {
            return back()->withErrors(['code' => 'Please wait 30 seconds before resending the code.']);
        }

        $code = mt_rand(100000, 999999);
        Cache::put('password_reset_' . $email, $code, now()->addMinutes(10));
        
        Mail::send('emails.reset_otp', ['code' => $code], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Your Password Reset Code - MedVault');
        });

        // Set cooldown in cache for 30 seconds
        Cache::put('resend_reset_cooldown_' . $email, true, now()->addSeconds(30));

        return back()->with('success', 'A new 6-digit code has been sent to your email.');
    }

    // 3. Show Code Verification Form
    public function showVerifyCodeForm()
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-code', ['email' => Session::get('reset_email')]);
    }

    // 4. Verify the Code
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $email = Session::get('reset_email');
        $cachedCode = Cache::get('password_reset_' . $email);

        if ($request->code == $cachedCode) {
            Session::put('is_verified', true);
            return redirect()->route('password.reset');
        }

        return back()->withErrors(['code' => 'Invalid or expired code.']);
    }

    // 5. Show Reset Password Form
    public function showResetForm()
    {
        if (!Session::has('is_verified') || !Session::has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    // 6. Update Password
    public function resetPassword(Request $request)
    {
        if (!Session::has('is_verified')) {
            return redirect()->route('login');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = Session::get('reset_email');
        $user = User::where('email', $email)->first();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        Cache::forget('password_reset_' . $email);
        Session::forget(['reset_email', 'is_verified']);

        return redirect()->route('login')->with('success', 'Password reset successful! You can now log in.');
    }
}