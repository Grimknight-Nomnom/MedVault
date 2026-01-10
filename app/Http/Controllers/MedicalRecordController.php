<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    // -- ADMIN: Show form to add diagnosis --
    public function create($appointment_id)
    {
        $appointment = Appointment::with('user')->findOrFail($appointment_id);
        
        return view('admin.records.create', compact('appointment'));
    }

    // -- ADMIN: Save the diagnosis --
    public function store(Request $request, $appointment_id)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($appointment_id);

        // Create Record
        MedicalRecord::create([
            'user_id' => $appointment->user_id, // The patient
            'appointment_id' => $appointment->id,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        // Auto-complete the appointment if not already
        $appointment->update(['status' => 'completed']);

        return redirect()->route('admin.appointments.index')->with('success', 'Medical record saved and appointment completed!');
    }

    // -- PATIENT: View my own medical history --
    public function myRecords()
    {
        $records = MedicalRecord::where('user_id', Auth::id())
                    ->with('appointment') // Eager load appointment date
                    ->latest()
                    ->get();

        return view('records.my_history', compact('records'));
    }
}