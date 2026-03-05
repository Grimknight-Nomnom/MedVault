<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

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
            
            // --- UNVERIFIED CHECK: Boots them out and shows the resend link ---
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'login_identifier' => 'You must verify your email address before logging in. Please check your inbox.',
                ])->onlyInput('login_identifier')->with('show_resend_link', true);
            }
            // ------------------------------------------------------------------

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('show_admin_alerts', true);
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
            'age'           => 'required|string|max:100',
            // --- ADD THESE TWO NEW VALIDATION RULES ---
            'gender'        => 'required|string|in:Male,Female',
            'civil_status'  => 'required|string|in:Single,Married,Widowed,Separated',
            // ------------------------------------------
            'phone'         => ['required', 'string', 'unique:users,phone', 'regex:/^\+639\d{9}$/'],
            'address'       => 'nullable|string|max:500',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'patient_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', 
        ], [
            'patient_photo.image' => 'The uploaded file must be a valid image format.',
            'patient_photo.mimes' => 'The document must be a file of type: jpeg, png, jpg.',
            'patient_photo.max' => 'The document may not be greater than 5 Megabytes.',
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

        $photoPath = null;
        if ($request->hasFile('patient_photo')) {
            $photoPath = $request->file('patient_photo')->store('patient_photos', 'public');
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
            // --- ADD THEM TO THE CREATE ARRAY ---
            'gender'        => $validated['gender'],
            'civil_status'  => $validated['civil_status'],
            // ------------------------------------
            'phone'         => $validated['phone'],
            'address'       => $validated['address'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'usernumber'    => $randomNumber,
            'role'          => 'user',
            'patient_photo_path' => $photoPath, 
        ]);

        // Trigger verification email
        event(new Registered($user));

        return redirect()->route('login')->with('success', 
            "Registration successful! Your User Number is: {$randomNumber}. Please check your email to verify your account before logging in.");
    }

    // --- EMAIL VERIFICATION LOGIC ---

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid or expired verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Your email is already verified. You can now log in.');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->route('login')->with('success', 'Email successfully verified! You can now log in.');
    }

    // --- RESEND VERIFICATION LOGIC (NEW) ---
    public function resendVerification(Request $request)
    {
        $request->validate([
            'login_identifier' => 'required'
        ]);

        $input = $request->input('login_identifier');
        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'usernumber';

        $user = User::where($fieldType, $input)->first();

        if (!$user) {
            return back()->withErrors(['login_identifier' => 'No account found with that identifier.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Your email is already verified. You can now log in.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'A new verification link has been sent to your email address.');
    }
}