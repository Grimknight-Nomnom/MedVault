<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Staff; // <-- ADDED STAFF MODEL
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    /**
     * ADMIN: Show the form to add a diagnosis for a specific appointment.
     */
    public function create($appointment_id)
    {
        // Eager load the user (patient) to display their name in the form
        $appointment = Appointment::with('user')->findOrFail($appointment_id);
        
        // Fetch only staff members with the role of Doctor or Nurse
        $staffList = Staff::whereIn('role', ['Doctor', 'Nurse', 'doctor', 'nurse', 'DOCTOR', 'NURSE'])
                          ->orderBy('name')
                          ->get();
        
        return view('admin.records.create', compact('appointment', 'staffList'));
    }

    /**
     * ADMIN: Save the diagnosis and finalize the appointment.
     */
    public function store(Request $request, $appointment_id)
    {
        $request->validate([
            'diagnosed_by' => 'required|string', // <-- Added validation
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($appointment_id);

        // Format the notes to include the diagnosing staff member
        $finalNotes = "Diagnosed by: " . $request->diagnosed_by;
        if ($request->filled('notes')) {
            $finalNotes .= " | Notes: " . $request->notes;
        }

        // Create the Medical Record permanently
        MedicalRecord::create([
            'user_id' => $appointment->user_id,
            'appointment_id' => $appointment->id,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $finalNotes, // Save the combined notes here
        ]);

        // Transition the appointment to 'completed' status
        $appointment->update(['status' => 'completed']);

        // Redirect with the updated success message
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Medical record saved and appointment marked as completed.');
    }

    /**
     * PATIENT: View their own medical history.
     */
    public function myRecords()
    {
        $records = MedicalRecord::where('user_id', Auth::id())
                    ->with('appointment') // Eager load appointment details (like dates)
                    ->latest()
                    ->get();

        return view('records.my_history', compact('records'));
    }
}