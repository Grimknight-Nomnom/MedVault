<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
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
        // 1. Validate the single input field
        $request->validate([
            'login_identifier' => 'required', // Can be email OR user number
            'password' => 'required'
        ]);

        $input = $request->input('login_identifier');
        
        // 2. Determine Login Type
        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'usernumber';

        // 3. Attempt Auth
        $credentials = [
            $fieldType => $input,
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentials)) {
            
            // --- NEW: STRICT EMAIL VERIFICATION RESTRICTION ---
            // If the user is a patient (not an admin) and hasn't verified their email yet
            if (Auth::user()->role !== 'admin' && is_null(Auth::user()->email_verified_at)) {
                // Log them back out immediately
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Send them back with an error
                return back()->withErrors([
                    'login_identifier' => 'You must verify your email address before logging in. Please check your inbox for the verification link.',
                ])->onlyInput('login_identifier');
            }

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/dashboard');
        }

        // 4. Failed Login (Wrong password or ID)
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

    // --- REGISTRATION LOGIC ---

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validate Fields
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
        ], [
            'phone.regex'  => 'The phone number must be a valid Philippine mobile number (e.g., +639123456789).',
            'phone.unique' => 'This phone number is already registered to another account.',
        ]);

        // 2. STRICT DUPLICATE ACCOUNT PREVENTION
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

        // 3. Generate Unique 3-Digit User Number
        do {
            $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        } while (User::where('usernumber', $randomNumber)->exists());

        // 4. Create User
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

        // --- 5. Generate Secure Verification URL ---
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addHours(24), // Link expires in 24 hours
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // --- 6. Send Verification Email ---
        Mail::send('emails.verify_account', [
            'url' => $verifyUrl,
            'usernumber' => $user->usernumber,
            'email' => $user->email,
        ], function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Welcome to MedVault - Verify Your Account');
        });

        // 7. Redirect with Instructions
        return redirect()->route('login')->with('success', 
            "Registration successful! We have sent a verification link to your email. Your User Number is: {$randomNumber}. Please verify your email before logging in.");
    }

    // --- EMAIL VERIFICATION CONFIRMATION METHOD ---

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Check if the hash matches the user's email
        if (! hash_equals((string) $hash, (string) sha1($user->email))) {
            abort(403, 'Invalid or expired verification link.');
        }

        // Update the database to mark email as verified
        if (! $user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        return redirect()->route('login')->with('success', 'Your email has been successfully verified! You can now log in.');
    }
}