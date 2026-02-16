<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        // If it looks like an email, treat as email. Otherwise, treat as usernumber.
        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'usernumber';

        // 3. Attempt Auth
        $credentials = [
            $fieldType => $input,
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/dashboard');
        }

        // 4. Failed Login
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
        'phone'         => 'nullable|string|max:20',
        'address'       => 'nullable|string|max:500',
        'email'         => 'required|string|email|max:255|unique:users',
        'password'      => 'required|string|min:8|confirmed',
    ]);

    // 2. STRICT DUPLICATE ACCOUNT PREVENTION
    // We check first_name, last_name, and date_of_birth to identify duplicates.
    $duplicateUser = User::where('first_name', $validated['first_name'])
        ->where('last_name', $validated['last_name'])
        ->whereDate('date_of_birth', $validated['date_of_birth'])
        ->where(function($query) use ($validated) {
            // If the new registration has no middle name, match against existing records 
            // that are also NULL or empty strings.
            if (empty($validated['middle_name'])) {
                $query->whereNull('middle_name')->orWhere('middle_name', '');
            } else {
                $query->where('middle_name', $validated['middle_name']);
            }
        })
        ->first();

    if ($duplicateUser) {
        // Associate the error with 'first_name' so it appears prominently on the form.
        return back()->withErrors([
            'first_name' => "An account with this name and date of birth already exists. Please log in or reset your password."
        ])->withInput();
    }

    // 3. Generate Unique 3-Digit User Number
    do {
        $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
    } while (User::where('usernumber', $randomNumber)->exists());

    // 4. Create User
    User::create([
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

    // 5. Redirect
    return redirect()->route('login')->with('success', 
        "Registration successful! Your User Number is: {$randomNumber}. Please log in using this number or your email.");
}
}