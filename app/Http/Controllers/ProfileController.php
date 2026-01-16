<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the personal profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            // Demographics
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:0',
            'gender' => 'required|string|in:Male,Female,Other',
            'civil_status' => 'required|string|in:Single,Married,Widowed,Separated',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20', // Contact Number
            'address' => 'required|string|max:500',

            // Medical History
            'allergies' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'existing_medical_conditions' => 'nullable|string',
        ]);

        // Handle Checkboxes manually for Programs
        $user->is_philhealth_member = $request->has('is_philhealth_member');
        $user->is_senior_citizen_or_pwd = $request->has('is_senior_citizen_or_pwd');

        // Update fields
        $user->fill($validated);
        $user->save();

        // Redirect back to dashboard to prompt them to book if they were blocked before
        return redirect()->route('dashboard')->with('success', 'Personal records updated successfully. You may now book an appointment.');
    }
}