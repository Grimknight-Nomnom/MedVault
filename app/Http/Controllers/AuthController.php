<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    // --- LOGIN LOGIC ---

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_identifier' => 'required',
            'password' => 'required'
        ]);

        $input = $request->input('login_identifier');
        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'usernumber';

        $credentials = [
            $fieldType => $input,
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentials)) {
            
            if (Auth::user()->role !== 'admin' && is_null(Auth::user()->email_verified_at)) {
                $unverified_identifier = $input; // Save what they typed
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Pass the identifier back so the view knows who to resend the email to
                return back()->with('unverified_identifier', $unverified_identifier)->withErrors([
                    'unverified' => 'You must verify your email address before logging in.',
                ]);
            }

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'login_identifier' => 'Invalid credentials or user number.',
        ])->onlyInput('login_identifier');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // --- NEW: RESEND VERIFICATION LINK ---
    public function resendVerification(Request $request)
    {
        $identifier = $request->input('login_identifier');
        if (!$identifier) return back();

        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'usernumber';
        $user = User::where($fieldType, $identifier)->first();

        if (!$user) return back()->withErrors(['unverified' => 'User not found.']);
        if ($user->email_verified_at) return redirect()->route('login')->with('success', 'Your email is already verified. You can log in.');

        // 30 SECOND BACKEND COOLDOWN CHECK
        if (Cache::has('resend_verify_cooldown_' . $user->id)) {
            return back()->with('unverified_identifier', $identifier)->withErrors(['unverified' => 'Please wait 30 seconds before resending the link.']);
        }

        $verifyUrl = route('verification.verify', [
            'id' => $user->id, 
            'hash' => sha1($user->email)
        ]);

        Mail::send('emails.verify_account', [
            'url' => $verifyUrl,
            'usernumber' => $user->usernumber,
            'email' => $user->email,
        ], function($message) use ($user) {
            $message->to($user->email)->subject('Welcome to MedVault - Verify Your Account');
        });

        // Set cooldown in cache for 30 seconds
        Cache::put('resend_verify_cooldown_' . $user->id, true, now()->addSeconds(30));

        return back()->with('success', 'Verification link has been resent! Please check your email.')
                     ->with('unverified_identifier', $identifier);
    }

    // --- REGISTRATION LOGIC ---

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age'           => 'required|integer|min:1|max:120',
            'phone'         => ['required', 'string', 'unique:users,phone', 'regex:/^\+639\d{9}$/'],
            'address'       => 'nullable|string|max:500',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        $duplicateUser = User::where('first_name', $validated['first_name'])
            ->where('last_name', $validated['last_name'])
            ->whereDate('date_of_birth', $validated['date_of_birth'])
            ->where(function($query) use ($validated) {
                if (empty($validated['middle_name'])) {
                    $query->whereNull('middle_name')->orWhere('middle_name', '');
                } else {
                    $query->where('middle_name', $validated['middle_name']);
                }
            })
            ->first();

        if ($duplicateUser) {
            return back()->withErrors([
                'first_name' => "An account with this name and date of birth already exists. Please log in or reset your password."
            ])->withInput();
        }

        do {
            $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        } while (User::where('usernumber', $randomNumber)->exists());

        $user = User::create([
            'first_name'    => $validated['first_name'],
            'middle_name'   => $validated['middle_name'],
            'last_name'     => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'age'           => $validated['age'],
            'phone'         => $validated['phone'],
            'address'       => $validated['address'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'usernumber'    => $randomNumber,
            'role'          => 'user',
        ]);

        $verifyUrl = route('verification.verify', [
            'id' => $user->id, 
            'hash' => sha1($user->email)
        ]);

        Mail::send('emails.verify_account', [
            'url' => $verifyUrl,
            'usernumber' => $user->usernumber,
            'email' => $user->email,
        ], function($message) use ($user) {
            $message->to($user->email)->subject('Welcome to MedVault - Verify Your Account');
        });

        return redirect()->route('login')->with('success', 
            "Registration successful! We have sent a verification link to your email. Your User Number is: {$randomNumber}. Please verify your email before logging in.");
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, (string) sha1($user->email))) {
            abort(403, 'Invalid or expired verification link.');
        }

        if (! $user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        return redirect()->route('login')->with('success', 'Your email has been successfully verified! You can now log in.');
    }
}