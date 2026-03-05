<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
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
            
            // Medical History is strictly required
            'allergies' => 'required|string',
            'current_medication' => 'required|string',
            'existing_medical_conditions' => 'required|string',
            
            'philhealth_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'senior_pwd_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            // Residency photo restored
            'patient_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', 
        ]);

        $user->is_philhealth_member = $request->has('is_philhealth_member');
        $user->is_senior_citizen_or_pwd = $request->has('is_senior_citizen_or_pwd');

        if ($request->hasFile('philhealth_id')) {
            if ($user->philhealth_id_path) {
                Storage::disk('public')->delete($user->philhealth_id_path);
            }
            $path = $request->file('philhealth_id')->store('id_uploads', 'public');
            $user->philhealth_id_path = $path;
        }

        if ($request->hasFile('senior_pwd_id')) {
            if ($user->senior_pwd_id_path) {
                Storage::disk('public')->delete($user->senior_pwd_id_path);
            }
            $path = $request->file('senior_pwd_id')->store('id_uploads', 'public');
            $user->senior_pwd_id_path = $path;
        }

if ($request->hasFile('patient_photo')) {
            if ($user->patient_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->patient_photo_path);
            }
            $path = $request->file('patient_photo')->store('patient_photos', 'public');
            $user->patient_photo_path = $path;
            
            $user->residency_rejection_reason = null;
            $user->admin_verified_at = null; // <-- This is what is crashing if the column doesn't exist yet!
        }


        // 2. Add this block BEFORE $user->save();
        if ($request->hasFile('patient_photo')) {
            // Delete the old photo if it exists
            if ($user->patient_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->patient_photo_path);
            }
            
            // Store new photo
            $path = $request->file('patient_photo')->store('patient_photos', 'public');
            $user->patient_photo_path = $path;
            
            // Reset verification status so the Admin can review the new document!
            $user->residency_rejection_reason = null;
            $user->admin_verified_at = null; 
        }

        $user->fill($validated);
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Personal records updated successfully. You may now book an appointment.');
    }

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
        
        // Restored Residency delete logic
        if ($type === 'residency' && $user->patient_photo_path) {
            Storage::disk('public')->delete($user->patient_photo_path);
            $user->patient_photo_path = null;
            $user->save();
            return back()->with('success', 'Proof of Residency deleted successfully. Please upload a new one.');
        }

        return back();
    }

    public function storeDependent(Request $request)
    {
        $parent = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'gender' => 'required|string|in:Male,Female,Other',
        ]);

        // STRICT AGE CALCULATION
        $dob = Carbon::parse($validated['date_of_birth']);
        $diff = $dob->diff(Carbon::now());
        
        if ($diff->y > 0) {
            $ageString = $diff->y . ($diff->y == 1 ? " year" : " years");
        } elseif ($diff->m > 0) {
            $ageString = $diff->m . ($diff->m == 1 ? " month" : " months");
        } elseif ($diff->d > 0) {
            $ageString = $diff->d . ($diff->d == 1 ? " day" : " days");
        } else {
            $ageString = "Newborn";
        }

        do {
            $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        } while (User::where('usernumber', $randomNumber)->exists());

        // Create the child account
        User::create([
            'parent_id' => $parent->id,
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'age' => $ageString,
            'civil_status' => 'Single',
            
            // Format a cleaner dummy email just in case
            'email' => strtolower(str_replace(' ', '', $validated['first_name'])) . '_' . $randomNumber . '@dependent.local', 
            
            // Securely copy the parent's hashed password
            'password' => $parent->password, 
            
            'phone' => $parent->phone, 
            'address' => $parent->address, 
            'role' => 'user',
            'usernumber' => $randomNumber,
            'email_verified_at' => $parent->email_verified_at, 
            'patient_photo_path' => $parent->patient_photo_path, // Restored child inheriting parent's residency
        ]);

        return back()->with('success', "Child account added! They can log in using their User ID (#{$randomNumber}) and your password.");
    }

    public function updateDependent(Request $request, $id)
    {
        $child = Auth::user()->children()->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'gender' => 'required|string|in:Male,Female,Other',
            
            // Medical History is strictly required for dependents
            'allergies' => 'required|string',
            'current_medication' => 'required|string',
            'existing_medical_conditions' => 'required|string',
        ]);

        // STRICT AGE CALCULATION
        $dob = Carbon::parse($validated['date_of_birth']);
        $diff = $dob->diff(Carbon::now());
        
        if ($diff->y > 0) {
            $ageString = $diff->y . ($diff->y == 1 ? " year" : " years");
        } elseif ($diff->m > 0) {
            $ageString = $diff->m . ($diff->m == 1 ? " month" : " months");
        } elseif ($diff->d > 0) {
            $ageString = $diff->d . ($diff->d == 1 ? " day" : " days");
        } else {
            $ageString = "Newborn";
        }

        $child->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'age' => $ageString,
            'allergies' => $validated['allergies'],
            'current_medication' => $validated['current_medication'],
            'existing_medical_conditions' => $validated['existing_medical_conditions'],
        ]);

        return back()->with('success', "Dependent profile for {$child->first_name} updated successfully.");
    }

    public function destroyDependent($id)
    {
        $child = Auth::user()->children()->findOrFail($id);
        $child->delete();
        return back()->with('success', 'Dependent profile removed successfully.');
    }
}