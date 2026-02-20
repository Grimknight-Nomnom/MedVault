<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\MedicineHistory;
use App\Models\AppointmentSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ... (Keep existing dashboard and captureExpiredMedicines methods) ...

    public function dashboard(Request $request)
    {
        // --- 1. DASHBOARD STATS ---
        $this->captureExpiredMedicines();

        $todayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::today())->count();
        $totalMedicines = Medicine::count();
        $totalAppointments = Appointment::count();
        $totalPatients = User::where('role', 'user')->count();
        $lowStock = Medicine::where('stock_quantity', '<', 10)->get();
        $expiringSoon = Medicine::where('expiry_date', '<=', now()->addDays(30))->get();
        
        // Monthly details for the chart
        $monthlyDetails = MedicineHistory::whereMonth('performed_at', now()->month)
            ->whereYear('performed_at', now()->year)
            ->whereIn('action_type', ['Released', 'Expired'])
            ->get();

        // --- 2. CALENDAR LOGIC (Unified) ---
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Fetch RAW appointments for the view (Grouped logic)
        $appointments = Appointment::with('user')
            ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->get();

        // Transform for View logic (Add names and formatted dates)
        $appointments->transform(function($app) {
            $app->patient_name = $app->user ? ($app->user->first_name . ' ' . $app->user->last_name) : 'Unknown';
            $app->calendar_date = Carbon::parse($app->appointment_date)->format('Y-m-d');
            return $app;
        });

        // Create the Missing Variable: $appointmentsByDate
        $appointmentsByDate = $appointments->groupBy('calendar_date');

        // Fetch Settings
        $settings = AppointmentSetting::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy('date');

        // Generate Grid Array (For dashboard-style grid if needed)
        $calendar = [];
        $today = Carbon::today()->format('Y-m-d');
        $startDayOfWeek = $startOfMonth->dayOfWeek;
        
        // Fill empty slots
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

        // --- 3. RETURN EVERYTHING ---
        return view('admin.dashboard', compact(
            // Stats
            'todayAppointmentsCount', 'totalMedicines', 'totalAppointments', 'totalPatients',
            'lowStock', 'expiringSoon', 'monthlyDetails',
            // Calendar
            'calendar', 'date', 'appointments', 'appointmentsByDate', 'settings'
        ));
    }

    private function captureExpiredMedicines()
    {
        $today = Carbon::today()->format('Y-m-d');
        $medicines = Medicine::where('expiry_date', '<=', $today)->where('stock_quantity', '>', 0)->get();

        foreach ($medicines as $medicine) {
            $alreadyLogged = MedicineHistory::where('medicine_name', $medicine->name)
                ->where('action_type', 'Expired')
                ->whereDate('performed_at', Carbon::today())
                ->exists();

            if (!$alreadyLogged) {
                MedicineHistory::create([
                    'medicine_name' => $medicine->name,
                    'action_type' => 'Expired',
                    'quantity_changed' => -$medicine->stock_quantity,
                    'description' => "Medicine expired on " . $medicine->expiry_date,
                    'performed_at' => now(),
                ]);
                $medicine->update(['stock_quantity' => 0]);
            }
        }
    }

    public function indexPatients(Request $request)
    {
        $query = User::whereIn('role', ['user', 'User', 'users']);
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%");
            });
        }
        $patients = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.patients.index', compact('patients'));
    }

    public function showPatient($id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);
        $consultations = Appointment::where('user_id', $id)
            ->where('status', 'completed')
            ->with('medicalRecord')
            ->orderBy('appointment_date', 'desc')
            ->get();
        return view('admin.patients.show', compact('patient', 'consultations'));
    }

    public function destroy($id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);
        $patient->delete();
        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted.');
    }

    // --- NEW: Manual Patient Verification ---
    public function verifyPatient($id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);
        
        if (is_null($patient->email_verified_at)) {
            $patient->update(['email_verified_at' => now()]);
            return redirect()->back()->with('success', "Patient {$patient->first_name} manually verified successfully.");
        }

        return redirect()->back()->with('info', 'Patient is already verified.');
    }
}