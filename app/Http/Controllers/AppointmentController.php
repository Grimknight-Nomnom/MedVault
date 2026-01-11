<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * Display the Calendar View
     */
    public function create()
    {
        // Fetch slot counts for the current month to populate the calendar grid
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $counts = Appointment::selectRaw('appointment_date, count(*) as total')
            ->whereBetween('appointment_date', [$start, $end])
            ->groupBy('appointment_date')
            ->pluck('total', 'appointment_date'); // Returns ['2026-01-12' => 5, ...]

        return view('appointments.create', compact('counts'));
    }

    /**
     * API: Get appointments for a specific date (for the Modal)
     */
    public function getSlots(Request $request)
    {
        $date = $request->query('date');
        $user = Auth::user();

        // 1. Fetch appointments for the date
        $appointments = Appointment::with('user')
            ->where('appointment_date', $date)
            ->orderBy('queue_number')
            ->get();

        // 2. Transform & Anonymize Data
        $slots = $appointments->map(function ($app) use ($user) {
            $isMe = $app->user_id === $user->id;
            
            // Name Masking Logic
            if ($isMe) {
                $name = $app->user->first_name . ' ' . $app->user->last_name;
            } else {
                // Mask: "John Doe" -> "J*** D***"
                $f = $app->user->first_name;
                $l = $app->user->last_name;
                $name = substr($f, 0, 1) . '*** ' . substr($l, 0, 1) . '***';
            }

            return [
                'queue_number' => $app->queue_number,
                'name' => $name,
                'status' => ucfirst($app->status),
                'is_me' => $isMe,
            ];
        });

        // 3. Status Flags
        $count = $appointments->count();
        $limit = 30;
        $userHasBooking = $appointments->contains('user_id', $user->id);

        return response()->json([
            'date_formatted' => Carbon::parse($date)->format('F j, Y'),
            'slots' => $slots,
            'slots_taken' => $count,
            'limit' => $limit,
            'is_full' => $count >= $limit,
            'user_has_booking' => $userHasBooking,
            'next_queue' => $count + 1
        ]);
    }

    /**
     * Store a new Appointment (Queue Logic)
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|max:500',
        ]);

        $date = $request->appointment_date;
        $userId = Auth::id();

        // 1. Validation: One booking per day
        $exists = Appointment::where('user_id', $userId)
            ->where('appointment_date', $date)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['appointment_date' => 'You already have an appointment on this date.']);
        }

        // 2. Validation: Daily Limit (Max 30)
        $currentCount = Appointment::where('appointment_date', $date)->count();
        if ($currentCount >= 30) {
            return back()->withErrors(['appointment_date' => 'Sorry, this date is fully booked.']);
        }

        // 3. Generate Queue Number (Concurrency safe-ish)
        // Get the highest queue number for that day and add 1
        $maxQueue = Appointment::where('appointment_date', $date)->max('queue_number') ?? 0;
        $nextQueue = $maxQueue + 1;

        // 4. Create Record
        Appointment::create([
            'user_id' => $userId,
            'appointment_date' => $date,
            'queue_number' => $nextQueue,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.index')
            ->with('success', "Booking Confirmed! You are Queue #{$nextQueue} for " . Carbon::parse($date)->format('M d'));
    }
    
    // Existing index method...
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
                        ->orderBy('appointment_date', 'desc')
                        ->get();      
        return view('appointments.index', compact('appointments'));
    }
}