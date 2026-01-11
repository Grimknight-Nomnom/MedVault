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
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|string',
            'civil_status' => 'nullable|string',
            'allergies' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'existing_medical_conditions' => 'nullable|string',
        ]);

        // Handle Checkboxes manually
        $user->is_philhealth_member = $request->has('is_philhealth_member');
        $user->is_senior_citizen_or_pwd = $request->has('is_senior_citizen_or_pwd');

        // Update fields
        $user->fill($validated);
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Personal record updated successfully.');
    }
}