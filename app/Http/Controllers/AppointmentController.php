<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // ==========================================
    //  PATIENT METHODS
    // ==========================================

    /**
     * Patient: Display the Booking Calendar
     */
    public function create()
    {
        // 1. RESTRICTION: Check for ongoing appointments
        $hasActive = Appointment::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasActive) {
            return redirect()->route('dashboard')
                ->with('error', 'You have an ongoing appointment. You cannot book another until your current appointment is completed.');
        }

        // 2. Build Calendar
        $date = Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        $dbCounts = Appointment::selectRaw('appointment_date, count(*) as total')
            ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->groupBy('appointment_date')
            ->pluck('total', 'appointment_date')
            ->toArray();

        $calendar = [];
        $today = Carbon::today()->format('Y-m-d');
        $startDayOfWeek = $startOfMonth->dayOfWeek;

        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendar[] = null;
        }

        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $currentDate = $startOfMonth->copy()->setDay($day)->format('Y-m-d');
            $count = $dbCounts[$currentDate] ?? 0;
            $isFull = $count >= 30;
            $isPast = $currentDate < $today;

            if ($isPast) {
                $statusClass = 'bg-light text-muted opacity-50 pe-none'; 
                $badgeClass = 'bg-secondary';
            } elseif ($isFull) {
                $statusClass = 'bg-danger-subtle border-danger text-danger'; 
                $badgeClass = 'bg-danger';
            } else {
                $statusClass = 'bg-white border hover-shadow text-dark'; 
                $badgeClass = ($count > 20) ? 'bg-warning text-dark' : 'bg-success';
            }

            $calendar[] = [
                'date' => $currentDate,
                'day' => $day,
                'count' => $count,
                'is_full' => $isFull,
                'is_past' => $isPast,
                'status_class' => $statusClass,
                'badge_class' => $badgeClass,
            ];
        }

        return view('appointments.create', compact('calendar', 'date'));
    }

    /**
     * Patient API: Get Slots for Modal
     */
    public function getSlots(Request $request)
    {
        $date = $request->query('date');
        $user = Auth::user();

        if (!$date) return response()->json(['error' => 'Date required'], 400);

        $appointments = Appointment::with('user')
            ->where('appointment_date', $date)
            ->orderBy('queue_number')
            ->get();

        $maskedData = $appointments->map(function ($app) use ($user) {
            $isMe = $app->user_id === $user->id;
            
            if ($isMe) {
                $name = $app->user->first_name . ' ' . $app->user->last_name;
            } else {
                $f = substr($app->user->first_name ?? '', 0, 1);
                $l = substr($app->user->last_name ?? '', 0, 1);
                $name = "{$f}*** {$l}***";
            }

            return [
                'queue' => $app->queue_number,
                'name' => $name,
                'status' => ucfirst($app->status),
                'is_me' => $isMe
            ];
        });

        $count = $appointments->count();
        $userHasBooking = $appointments->contains('user_id', $user->id);

        return response()->json([
            'date_formatted' => Carbon::parse($date)->format('F j, Y'),
            'slots_taken' => $count,
            'is_full' => $count >= 30,
            'user_has_booking' => $userHasBooking,
            'next_queue' => $count + 1,
            'appointments' => $maskedData
        ]);
    }

    /**
     * Patient: Store Appointment
     */
    public function store(Request $request)
    {
        // 1. RESTRICTION: Check for ongoing appointments again
        $hasActive = Appointment::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasActive) {
            return redirect()->route('dashboard')
                ->with('error', 'Action Denied: You already have an active appointment.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|max:500',
        ]);

        $date = $request->appointment_date;
        $userId = Auth::id();

        $exists = Appointment::where('appointment_date', $date)
                    ->where('user_id', $userId)->exists();
        if ($exists) {
            return back()->withErrors(['msg' => 'You already have an appointment today.']);
        }

        $count = Appointment::where('appointment_date', $date)->count();
        if ($count >= 30) {
            return back()->withErrors(['msg' => 'Date is fully booked.']);
        }

        $maxQueue = Appointment::where('appointment_date', $date)->max('queue_number') ?? 0;
        $queueNumber = $maxQueue + 1;

        Appointment::create([
            'user_id' => $userId,
            'appointment_date' => $date,
            'queue_number' => $queueNumber,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.index')
            ->with('success', "Booked successfully! Your Queue Number is #{$queueNumber}");
    }
    
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
                        ->orderBy('appointment_date', 'desc')->get();
        return view('appointments.index', compact('appointments'));
    }

    // ==========================================
    //  ADMIN METHODS
    // ==========================================

    public function adminIndex(Request $request)
    {
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        $year = $date->year;
        $month = $date->month;

        $appointments = Appointment::with('user')
            ->whereYear('appointment_date', $year)
            ->whereMonth('appointment_date', $month)
            ->orderBy('appointment_date')
            ->orderBy('queue_number')
            ->get();

        $appointments->transform(function($app) {
            $app->patient_name = $app->user ? ($app->user->first_name . ' ' . $app->user->last_name) : 'Unknown';
            $app->email = $app->user->email ?? 'N/A';
            return $app;
        });

        $appointmentsByDate = $appointments->groupBy(function($app) {
            return Carbon::parse($app->appointment_date)->format('Y-m-d');
        });

        return view('admin.appointments.index', compact('appointmentsByDate', 'date', 'appointments'));
    }

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