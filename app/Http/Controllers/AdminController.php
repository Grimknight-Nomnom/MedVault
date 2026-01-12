<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of registered patients.
     */
    public function indexPatients(Request $request)
    {
        $query = User::where('role', 'user');

        // Simple Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%");
            });
        }

        // Order by latest registration
        $patients = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Display the specified patient profile.
     */
    public function showPatient($id)
    {
        // 1. Fetch User
        $patient = User::where('role', 'user')->findOrFail($id);

        // 2. Fetch Consultation History
        // We get appointments that are 'completed' and have an associated medical record
        // Assuming there is a relationship set up, or we can fetch appointments and eager load the record
        $consultations = Appointment::where('user_id', $id)
            ->where('status', 'completed')
            ->with('medicalRecord') // Ensure this relationship exists in Appointment model
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('admin.patients.show', compact('patient', 'consultations'));
    }
}