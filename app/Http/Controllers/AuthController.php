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
            
            $request->session()->regenerate();

            // NEW: Added a flash session variable to trigger the modal strictly after login
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
            'phone'         => $validated['phone'],
            'address'       => $validated['address'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'usernumber'    => $randomNumber,
            'role'          => 'user',
            'patient_photo_path' => $photoPath, 
        ]);

        return redirect()->route('login')->with('success', 
            "Registration successful! Your User Number is: {$randomNumber}. You can now sign in, but please wait for admin approval before you can book an appointment.");
    }
}