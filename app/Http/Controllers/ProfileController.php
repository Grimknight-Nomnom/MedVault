<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            // Demographics
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|string|max:50',
            'gender' => 'required|string|in:Male,Female,Other',
            'civil_status' => 'required|string|in:Single,Married,Widowed,Separated',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',

            // Medical History
            'allergies' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'existing_medical_conditions' => 'nullable|string',
            
            // Image Validation (Max 5MB each)
            'philhealth_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'senior_pwd_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user->is_philhealth_member = $request->has('is_philhealth_member');
        $user->is_senior_citizen_or_pwd = $request->has('is_senior_citizen_or_pwd');

        // Handle PhilHealth ID Upload
        if ($request->hasFile('philhealth_id')) {
            // Delete old one if exists
            if ($user->philhealth_id_path) {
                Storage::disk('public')->delete($user->philhealth_id_path);
            }
            $path = $request->file('philhealth_id')->store('id_uploads', 'public');
            $user->philhealth_id_path = $path;
        }

        // Handle Senior/PWD ID Upload
        if ($request->hasFile('senior_pwd_id')) {
            // Delete old one if exists
            if ($user->senior_pwd_id_path) {
                Storage::disk('public')->delete($user->senior_pwd_id_path);
            }
            $path = $request->file('senior_pwd_id')->store('id_uploads', 'public');
            $user->senior_pwd_id_path = $path;
        }

        $user->fill($validated);
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Personal records updated successfully. You may now book an appointment.');
    }

    // --- NEW: Delete Image Method ---
    public function deleteIdImage($type)
    {
        $user = Auth::user();

        if ($type === 'philhealth' && $user->philhealth_id_path) {
            Storage::disk('public')->delete($user->philhealth_id_path);
            $user->philhealth_id_path = null;
            $user->save();
            return back()->with('success', 'PhilHealth ID deleted successfully. Please upload a new one if you are still a member.');
        }

        if ($type === 'senior_pwd' && $user->senior_pwd_id_path) {
            Storage::disk('public')->delete($user->senior_pwd_id_path);
            $user->senior_pwd_id_path = null;
            $user->save();
            return back()->with('success', 'Senior/PWD ID deleted successfully. Please upload a new one if you are still a member.');
        }

        return back();
    }
}