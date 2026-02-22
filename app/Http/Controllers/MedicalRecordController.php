<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Staff; 
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    /**
     * ADMIN: Show the form to add a diagnosis for a specific appointment.
     */
    public function create($appointment_id)
    {
        $appointment = Appointment::with('user')->findOrFail($appointment_id);
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
            'diagnosed_by' => 'required|string', 
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($appointment_id);

        $finalNotes = "Diagnosed by: " . $request->diagnosed_by;
        if ($request->filled('notes')) {
            $finalNotes .= " | Notes: " . $request->notes;
        }

        MedicalRecord::create([
            'user_id' => $appointment->user_id,
            'appointment_id' => $appointment->id,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $finalNotes, 
        ]);

        $appointment->update(['status' => 'completed']);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Medical record saved and appointment marked as completed.');
    }

    /**
     * ADMIN: Show the form to edit an existing medical record.
     */
    public function edit(MedicalRecord $record)
    {
        $record->load('appointment.user');
        
        $staffList = Staff::whereIn('role', ['Doctor', 'Nurse', 'doctor', 'nurse', 'DOCTOR', 'NURSE'])
                          ->orderBy('name')
                          ->get();
                          
        return view('admin.records.edit', compact('record', 'staffList'));
    }

    /**
     * ADMIN: Update the existing record and append to the notes audit trail.
     */
    public function update(Request $request, MedicalRecord $record)
    {
        $request->validate([
            'edited_by' => 'required|string',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'added_notes' => 'nullable|string',
        ]);

        // Safely append the Edited By and Current Date to the existing audit trail
        $appendNote = "\n\n[Edited on " . now()->format('M d, Y') . " by " . $request->edited_by . "]";
        if ($request->filled('added_notes')) {
            $appendNote .= " | Update: " . $request->added_notes;
        }

        $record->update([
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $record->notes . $appendNote,
        ]);

        // Redirect back to the patient's specific profile page
        return redirect()->route('admin.patients.show', $record->user_id)
            ->with('success', 'Medical record successfully updated.');
    }

    /**
     * PATIENT: View their own medical history.
     */
    public function myRecords()
    {
        $records = MedicalRecord::where('user_id', Auth::id())
                    ->with('appointment') 
                    ->latest()
                    ->get();

        return view('records.my_history', compact('records'));
    }
}