<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * List MY appointments only (Patient View).
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
                        ->orderBy('appointment_date', 'desc')
                        ->get();
                        
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show booking form (Patient View).
     */
    public function create()
    {
        return view('appointments.create');
    }

    /**
     * Save the appointment (Patient View).
     * Updated to support Date-only storage (no time).
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today', // Changed to date & after_or_equal
            'reason' => 'required|string|max:500',
        ]);

        Appointment::create([
            'user_id' => Auth::id(), // Link to current user
            'appointment_date' => $request->appointment_date,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment request sent successfully!');
    }

    // -- ADMIN METHODS --

    /**
     * Admin: Display the monthly calendar view.
     * This fetches appointments for the selected month/year and groups them by date.
     */
    public function adminIndex(Request $request)
    {
        // 1. Get Month/Year from request or default to Today
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        $year = $date->year;
        $month = $date->month;

        // 2. Fetch appointments for this specific month, eager loading the User/Patient
        // We order by ID so multiple appointments on the same day stay consistent
        $appointments = Appointment::with('user')
            ->whereYear('appointment_date', $year)
            ->whereMonth('appointment_date', $month)
            ->get();

        // 3. Transform the collection for the Frontend JS
        // We add 'patient_name' and 'email' properties directly to the object 
        // so the JavaScript modal can access them easily as `app.patient_name`
        $appointments->transform(function($app) {
            // Handle name dynamically (in case you are using first_name/last_name split or just name)
            if ($app->user) {
                // Check if 'name' exists, otherwise combine first/last
                $app->patient_name = $app->user->name ?? ($app->user->first_name . ' ' . $app->user->last_name);
                $app->email = $app->user->email;
            } else {
                $app->patient_name = 'Unknown Patient';
                $app->email = 'N/A';
            }
            return $app;
        });

        // 4. Group by day for the PHP Calendar Grid loop: ['2026-01-10' => Collection]
        $appointmentsByDate = $appointments->groupBy(function($app) {
            return Carbon::parse($app->appointment_date)->format('Y-m-d');
        });

        return view('admin.appointments.index', compact('appointmentsByDate', 'date', 'appointments'));
    }

    /**
     * Update appointment status (Approve, Cancel, Complete).
     */
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