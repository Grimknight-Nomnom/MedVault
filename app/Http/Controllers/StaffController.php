<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\MedicineHistory;
use App\Models\AppointmentSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function dashboard(Request $request)
    {
        // Automatically mark past pending/approved appointments as incomplete
        Appointment::whereIn('status', ['pending', 'approved'])
            ->where('appointment_date', '<', Carbon::today())
            ->update(['status' => 'incomplete']);

        // Gather read-only statistics
        $todayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::today())->count();
        $totalMedicines = Medicine::count();
        $totalAppointments = Appointment::count();
        $totalPatients = User::where('role', 'user')->count();
        
        $lowStock = Medicine::where('stock_quantity', '<', 10)->get();
        $expiringSoon = Medicine::where('expiry_date', '<=', now()->addDays(30))->get();
        
        $monthlyDetails = MedicineHistory::whereMonth('performed_at', now()->month)
            ->whereYear('performed_at', now()->year)
            ->whereIn('action_type', ['Released', 'Expired'])
            ->get();

        // Calendar Logic
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $appointments = Appointment::with('user')
            ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->get();

        $appointments->transform(function($app) {
            $app->patient_name = $app->user ? ($app->user->first_name . ' ' . $app->user->last_name) : 'Unknown';
            $app->calendar_date = Carbon::parse($app->appointment_date)->format('Y-m-d');
            return $app;
        });

        $appointmentsByDate = $appointments->groupBy('calendar_date');

        $settings = AppointmentSetting::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy('date');

        $calendar = [];
        $today = Carbon::today()->format('Y-m-d');
        $startDayOfWeek = $startOfMonth->dayOfWeek;
        
        for ($i = 0; $i < $startDayOfWeek; $i++) { $calendar[] = null; }

        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $currentDate = $startOfMonth->copy()->setDay($day)->format('Y-m-d');
            $dayCount = $appointmentsByDate->get($currentDate, collect())->count();
            $daySetting = $settings->get($currentDate);
            $maxLimit = $daySetting ? $daySetting->max_appointments : (Carbon::parse($currentDate)->dayOfWeek === Carbon::WEDNESDAY ? 50 : 30);

            $calendar[] = [
                'date' => $currentDate,
                'day' => $day,
                'count' => $dayCount,
                'max' => $maxLimit,
                'label' => $daySetting ? $daySetting->label : null,
                'is_full' => $dayCount >= $maxLimit,
                'is_past' => $currentDate < $today,
            ];
        }

        return view('staff.dashboard', compact(
            'todayAppointmentsCount', 'totalMedicines', 'totalAppointments', 'totalPatients',
            'lowStock', 'expiringSoon', 'monthlyDetails',
            'calendar', 'date', 'appointments', 'appointmentsByDate', 'settings'
        ));
    }
}