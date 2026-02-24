<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\MedicineHistory;
use App\Models\AppointmentSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; 

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $this->captureExpiredMedicines();

        Appointment::whereIn('status', ['pending', 'approved'])
            ->where('appointment_date', '<', Carbon::today())
            ->update(['status' => 'incomplete']);

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

        // --- NEW QUERIES FOR ALERT MODAL ON LOGIN ---
        $unverifiedPatients = User::where('role', 'user')->whereNull('email_verified_at')->get();
        $outOfStockMeds = Medicine::where('stock_quantity', '<=', 0)->get();
        $expiredMedsAlert = Medicine::whereDate('expiry_date', '<', Carbon::today())->get();

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

        return view('admin.dashboard', compact(
            'todayAppointmentsCount', 'totalMedicines', 'totalAppointments', 'totalPatients',
            'lowStock', 'expiringSoon', 'monthlyDetails',
            'calendar', 'date', 'appointments', 'appointmentsByDate', 'settings',
            'unverifiedPatients', 'outOfStockMeds', 'expiredMedsAlert' // Passed to view
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
            $search = ltrim($request->search, '#');
            
            $query->where(function($q) use ($search) {
                $q->where('usernumber', 'like', "%$search%")
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

    public function verifyPatient($id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);
        
        if (is_null($patient->email_verified_at)) {
            $patient->update(['email_verified_at' => now()]);
            return redirect()->back()->with('success', "Patient {$patient->first_name} manually verified successfully.");
        }

        return redirect()->back()->with('info', 'Patient is already verified.');
    }

    public function rejectResidency(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $user = User::findOrFail($id);

        if ($user->patient_photo_path) {
            Storage::disk('public')->delete($user->patient_photo_path);
        }

        $user->update([
            'patient_photo_path' => null,
            'residency_rejection_reason' => $request->reason,
        ]);

        return back()->with('success', 'Document rejected. The patient has been notified to re-upload.');
    }

    public function deletePatientId($id, $type)
    {
        $patient = User::where('role', 'user')->findOrFail($id);

        if ($type === 'philhealth' && $patient->philhealth_id_path) {
            Storage::disk('public')->delete($patient->philhealth_id_path);
            $patient->philhealth_id_path = null;
            $patient->save();
            return redirect()->back()->with('success', "PhilHealth ID deleted for {$patient->first_name}.");
        }

        if ($type === 'senior_pwd' && $patient->senior_pwd_id_path) {
            Storage::disk('public')->delete($patient->senior_pwd_id_path);
            $patient->senior_pwd_id_path = null;
            $patient->save();
            return redirect()->back()->with('success', "Senior/PWD ID deleted for {$patient->first_name}.");
        }

        return redirect()->back()->with('error', 'Image not found.');
    }

    public function editPatient($id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, $id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|string|max:50',
            'gender' => 'required|string|in:Male,Female,Other',
            'civil_status' => 'required|string|in:Single,Married,Widowed,Separated',
            'email' => 'required|string|email|max:255|unique:users,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'allergies' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'existing_medical_conditions' => 'nullable|string',
            'philhealth_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'senior_pwd_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $patient->is_philhealth_member = $request->has('is_philhealth_member');
        $patient->is_senior_citizen_or_pwd = $request->has('is_senior_citizen_or_pwd');

        if ($request->hasFile('philhealth_id')) {
            if ($patient->philhealth_id_path) {
                Storage::disk('public')->delete($patient->philhealth_id_path);
            }
            $path = $request->file('philhealth_id')->store('id_uploads', 'public');
            $patient->philhealth_id_path = $path;
        }

        if ($request->hasFile('senior_pwd_id')) {
            if ($patient->senior_pwd_id_path) {
                Storage::disk('public')->delete($patient->senior_pwd_id_path);
            }
            $path = $request->file('senior_pwd_id')->store('id_uploads', 'public');
            $patient->senior_pwd_id_path = $path;
        }

        $patient->fill($validated);
        $patient->save();

        return redirect()->route('admin.patients.show', $patient->id)->with('success', 'Patient information updated successfully.');
    }

    public function changePatientPassword(Request $request, $id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $patient->password)) {
            return back()->with('error', 'The old password does not match our records.');
        }

        $patient->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', "Password successfully reset for {$patient->first_name}.");
    }
}