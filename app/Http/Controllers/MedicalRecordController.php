<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Appointment;
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
        
        return view('admin.records.create', compact('appointment'));
    }

    /**
     * ADMIN: Save the diagnosis and finalize the appointment.
     */
public function store(Request $request, $appointment_id)
{
    $request->validate([
        'diagnosis' => 'required|string',
        'prescription' => 'nullable|string',
        'notes' => 'nullable|string',
    ]);

$appointment = Appointment::findOrFail($appointment_id);

    // Create the Medical Record permanently
    MedicalRecord::create([
        'user_id' => $appointment->user_id,
        'appointment_id' => $appointment->id,
        'diagnosis' => $request->diagnosis,
        'prescription' => $request->prescription,
        'notes' => $request->notes,
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