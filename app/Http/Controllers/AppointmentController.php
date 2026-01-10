<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // List MY appointments only
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
                        ->orderBy('appointment_date', 'desc')
                        ->get();
                        
        return view('appointments.index', compact('appointments'));
    }

    // Show booking form
    public function create()
    {
        return view('appointments.create');
    }

    // Save the appointment
    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'reason' => 'required|string|max:500',
        ]);

        Appointment::create([
            'user_id' => Auth::id(), // Link to current user
            'appointment_date' => $request->appointment_date,
            'reason' => $request->reason,
            'status' => 'pending' // Default status
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment request sent!');
    }

    // -- ADMIN METHODS --

    // List ALL appointments for the Admin
    public function adminIndex()
    {
        // Eager load the 'user' relationship to show the patient's name
        $appointments = Appointment::with('user')->orderBy('appointment_date', 'asc')->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    // Update appointment status (Approve, Cancel, Complete)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,completed,cancelled'
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Appointment status updated to ' . ucfirst($request->status));
    }
}