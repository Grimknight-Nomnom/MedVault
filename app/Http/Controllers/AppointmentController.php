<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str; 

class AppointmentController extends Controller
{
    private function getMaxSlots($date)
    {
        $setting = AppointmentSetting::where('date', $date)->first();
        
        // If admin set a custom limit, use it.
        if ($setting) {
            return $setting->max_appointments;
        }
        
        // Default Logic: Wednesday = 50, Others = 30
        return Carbon::parse($date)->dayOfWeek === Carbon::WEDNESDAY ? 50 : 30;
    }

    private function isProfileIncomplete()
    {
        $user = Auth::user();
        $requiredFields = ['first_name', 'last_name', 'date_of_birth', 'gender', 'civil_status', 'address', 'phone'];
        foreach ($requiredFields as $field) {
            if (empty($user->$field)) return true;
        }
        return false;
    }

    private function isPregnancyRestricted($date, $user)
    {
        $setting = AppointmentSetting::where('date', $date)->first();
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        
        $label = '';
        
        if ($setting && !empty($setting->label)) {
            $label = $setting->label;
        } elseif ($dayOfWeek === Carbon::TUESDAY || $dayOfWeek === Carbon::THURSDAY) { 
            // RESTRICTION: Tuesday OR Thursday = Pregnancy
            $label = 'Pregnancy';
        }

        if (Str::contains(Str::lower($label), 'pregnancy')) {
            $gender = Str::lower(trim($user->gender ?? ''));
            if ($gender !== 'female') {
                return true; 
            }
        }

        return false; 
    }

    // ================= PATIENT METHODS =================

    public function create()
    {
        if ($this->isProfileIncomplete()) {
            return redirect()->route('profile.edit')->with('error', 'Profile Incomplete.');
        }

        $hasActiveAppointment = Appointment::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        $date = Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        $dbCounts = Appointment::selectRaw('appointment_date, count(*) as total')
            ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->groupBy('appointment_date')
            ->pluck('total', 'appointment_date')
            ->toArray();

        $settings = AppointmentSetting::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy('date');

        $calendar = [];
        $today = Carbon::today()->format('Y-m-d');
        $startDayOfWeek = $startOfMonth->dayOfWeek;

        // Fill empty slots for previous month days
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendar[] = null;
        }

        // Loop through days of the month
        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $currentDate = $startOfMonth->copy()->setDay($day)->format('Y-m-d');
            $count = $dbCounts[$currentDate] ?? 0;
            
            $daySetting = $settings->get($currentDate);
            
            // Logic: Wednesday = 50, Others = 30
            $defaultLimit = Carbon::parse($currentDate)->dayOfWeek === Carbon::WEDNESDAY ? 50 : 30;
            $maxLimit = $daySetting ? $daySetting->max_appointments : $defaultLimit;
            
            $customLabel = $daySetting ? $daySetting->label : null;

            $isFull = $count >= $maxLimit;
            $isPast = $currentDate < $today;

            if ($isPast) {
                $statusClass = 'bg-light text-muted opacity-50 pe-none'; 
                $badgeClass = 'bg-secondary';
            } elseif ($isFull) {
                $statusClass = 'bg-danger-subtle border-danger text-danger'; 
                $badgeClass = 'bg-danger';
            } else {
                $statusClass = 'bg-white border hover-shadow text-dark'; 
                $badgeClass = ($count > ($maxLimit * 0.7)) ? 'bg-warning text-dark' : 'bg-success';
            }

            $calendar[] = [
                'date' => $currentDate,
                'day' => $day,
                'count' => $count,
                'max' => $maxLimit,
                'label' => $customLabel,
                'is_full' => $isFull,
                'is_past' => $isPast,
                'status_class' => $statusClass,
                'badge_class' => $badgeClass,
            ];
        }

        return view('appointments.create', compact('calendar', 'date', 'hasActiveAppointment'));
    }

    public function getSlots(Request $request)
    {
        $date = $request->query('date');
        if (!$date) return response()->json(['error' => 'Date required'], 400);

        $user = Auth::user();
        $appointments = Appointment::with('user')->where('appointment_date', $date)->orderBy('queue_number')->get();
        $maxLimit = $this->getMaxSlots($date);

        // --- CHECK RESTRICTION ---
        $isRestricted = $this->isPregnancyRestricted($date, $user);
        $restrictionMessage = $isRestricted ? "This date is reserved for Pregnancy checkups (Females Only)." : "";

        $maskedData = $appointments->map(function ($app) use ($user) {
            $isMe = $app->user_id === $user->id;
            $name = $isMe ? $app->user->first_name . ' ' . $app->user->last_name : substr($app->user->first_name ?? '', 0, 1) . "*** " . substr($app->user->last_name ?? '', 0, 1) . "***";
            return [
                'queue' => $app->queue_number,
                'name' => $name,
                'status' => ucfirst($app->status),
                'is_me' => $isMe
            ];
        });

        $count = $appointments->count();
        return response()->json([
            'date_formatted' => Carbon::parse($date)->format('F j, Y'),
            'slots_taken' => $count,
            'max_limit' => $maxLimit,
            'is_full' => $count >= $maxLimit,
            'user_has_booking' => $appointments->contains('user_id', $user->id),
            'next_queue' => $count + 1,
            'appointments' => $maskedData,
            'is_restricted' => $isRestricted,
            'restriction_message' => $restrictionMessage
        ]);
    }

    public function store(Request $request)
    {
        if ($this->isProfileIncomplete()) {
            return redirect()->route('profile.edit')->with('error', 'Profile Incomplete.');
        }

        $request->validate(['appointment_date' => 'required|date|after_or_equal:today', 'reason' => 'required|string|max:500']);
        
        $date = $request->appointment_date;
        $user = Auth::user();

        // --- SERVER-SIDE RESTRICTION CHECK ---
        if ($this->isPregnancyRestricted($date, $user)) {
            return back()->withErrors(['msg' => 'Access Denied: This date is reserved for Pregnancy checkups (Females Only).']);
        }

        if (Appointment::where('user_id', $user->id)->whereIn('status', ['pending', 'approved'])->exists()) {
            return redirect()->route('dashboard')->with('error', 'You already have an active appointment.');
        }

        $count = Appointment::where('appointment_date', $date)->count();
        $maxLimit = $this->getMaxSlots($date);

        if ($count >= $maxLimit) {
            return back()->withErrors(['msg' => 'Date is fully booked.']);
        }

        $maxQueue = Appointment::where('appointment_date', $date)->max('queue_number') ?? 0;
        Appointment::create([
            'user_id' => $user->id,
            'appointment_date' => $date,
            'queue_number' => $maxQueue + 1,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.index')->with('success', "Booked successfully!");
    }

    // NEW: Destroy Method for Deleting Appointment
    public function destroy(Appointment $appointment)
    {
        // Security check: Ensure the user deleting the appointment is the one who made it
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->delete();

        return redirect()->route('dashboard')->with('success', 'Appointment cancelled successfully.');
    }

    // ================= ADMIN METHODS =================

    public function adminIndex(Request $request)
    {
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        
        $appointments = Appointment::with('user')
            ->whereYear('appointment_date', $date->year)
            ->whereMonth('appointment_date', $date->month)
            ->get();

        $appointments->transform(function($app) {
            $app->patient_name = $app->user ? ($app->user->first_name . ' ' . $app->user->last_name) : 'Unknown';
            return $app;
        });

        $appointmentsByDate = $appointments->groupBy(function($app) {
            return Carbon::parse($app->appointment_date)->format('Y-m-d');
        });

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        $settings = AppointmentSetting::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy('date');

        return view('admin.appointments.index', compact('appointmentsByDate', 'date', 'appointments', 'settings'));
    }

    public function updateDailyLimit(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'limit' => 'required|integer|min:0|max:200',
            'label' => 'nullable|string|max:50'
        ]);

        AppointmentSetting::updateOrCreate(
            ['date' => $request->date],
            [
                'max_appointments' => $request->limit,
                'label' => $request->label
            ]
        );

        return back()->with('success', 'Settings updated for ' . $request->date);
    }

    // Standard CRUD
    public function index() {
        $appointments = Appointment::where('user_id', Auth::id())->orderBy('appointment_date', 'desc')->get();
        return view('appointments.index', compact('appointments'));
    }
    public function updateStatus(Request $request, $id) {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
    public function adminCreate() {
        $patients = User::where('role', 'user')->orderBy('last_name')->get();
        return view('admin.appointments.create', compact('patients'));
    }
    public function adminStore(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|max:255',
        ]);
        $exists = Appointment::where('user_id', $request->user_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('status', '!=', 'cancelled')->exists();
        if ($exists) {
            return back()->withErrors(['user_id' => 'This patient already has an appointment on this date.'])->withInput();
        }
        $maxQueue = Appointment::where('appointment_date', $request->appointment_date)->max('queue_number') ?? 0;
        Appointment::create([
            'user_id' => $request->user_id,
            'appointment_date' => $request->appointment_date,
            'queue_number' => $maxQueue + 1,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);
        return redirect()->route('admin.appointments.index')->with('success', 'Created.');
    }
}