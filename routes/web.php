<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\AdminAnnouncementController;
use App\Models\Appointment;
use App\Models\Announcement;
use App\Models\Staff;

Route::get('/clear-cache', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "Cache cleared successfully!";
});

// --- Public Routes ---
Route::get('/', function () {
    $announcements = Announcement::where('is_active', true)->latest()->get();
    $staff = Staff::all();
    
    return view('welcome', compact('announcements', 'staff'));
})->name('welcome');

// --- Authentication ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// --- Email Verification ---
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('verification.resend'); 

// --- Password Reset Routes ---
Route::controller(PasswordResetController::class)->group(function () {
    Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetCode')->name('password.email');
    Route::get('/verify-code', 'showVerifyCodeForm')->name('password.verify');
    Route::post('/verify-code', 'verifyCode')->name('password.verify.post');
    Route::post('/resend-reset-code', 'resendCode')->name('password.resend_code'); 
    Route::get('/reset-password', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

// --- Protected Routes (Requires Login) ---
Route::middleware(['auth'])->group(function () {

    // Profile & User Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // NEW: Route to delete uploaded IDs
    Route::delete('/profile/delete-id/{type}', [ProfileController::class, 'deleteIdImage'])->name('profile.delete_id');

    // Patient Dashboard
    Route::get('/dashboard', function () {
        Appointment::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->where('appointment_date', '<', now()->startOfDay())
            ->update(['status' => 'completed']);

        $activeAppointment = Appointment::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->latest('appointment_date')
            ->first();

        return view('dashboard', compact('activeAppointment'));
    })->name('dashboard');
    
    Route::get('/my-medical-records', [MedicalRecordController::class, 'myRecords'])->name('patient.records');
    Route::get('/medicines-availability', [MedicineController::class, 'patientIndex'])->name('patient.medicines.index');

    // Patient Appointments
    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/book-appointment', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // API Helpers (Patient side)
    Route::get('/api/appointments/slots', [AppointmentController::class, 'getSlots'])->name('api.appointments.slots');

    // --- Admin Routes Group ---
    Route::prefix('admin')->middleware(['auth', 'can:admin'])->group(function () {
        
        // Manage Staff Routes 
        Route::post('/staff', [\App\Http\Controllers\AdminAnnouncementController::class, 'storeStaff'])->name('admin.staff.store');
        Route::put('/staff/{staff}', [\App\Http\Controllers\AdminAnnouncementController::class, 'updateStaff'])->name('admin.staff.update');
        Route::delete('/staff/{staff}', [\App\Http\Controllers\AdminAnnouncementController::class, 'destroyStaff'])->name('admin.staff.destroy');

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // API Routes for Charts
        Route::get('/api/trends', [MedicineController::class, 'getTrendsData'])->name('admin.trends.api');
        Route::get('/api/report', [MedicineController::class, 'getPeekData'])->name('admin.report.api');

        // Historical Report Page (Full Page)
        Route::get('/historical-report', [MedicineController::class, 'getHistoricalReport'])->name('admin.historical.report');

        // Announcements
        Route::resource('announcements', AdminAnnouncementController::class)
            ->names([
                'index' => 'admin.announcements.index',
                'create' => 'admin.announcements.create',
                'store' => 'admin.announcements.store',
                'edit' => 'admin.announcements.edit',
                'update' => 'admin.announcements.update',
                'destroy' => 'admin.announcements.delete',
            ]);

        // Appointments
        Route::controller(AppointmentController::class)->group(function () {
            Route::get('/appointments', 'adminIndex')->name('admin.appointments.index');
            Route::post('/appointments/limit', 'updateDailyLimit')->name('admin.appointments.limit');
            Route::get('/appointments/create', 'adminCreate')->name('admin.appointments.create');
            Route::post('/appointments', 'adminStore')->name('admin.appointments.store');
            Route::patch('/appointments/{id}', 'updateStatus')->name('admin.appointments.update');
        });

        // Medicine Inventory
        Route::controller(MedicineController::class)->group(function () {
            Route::get('/medicines', 'index')->name('admin.medicines.index');
            Route::get('/medicines/create', 'create')->name('admin.medicines.create');
            Route::post('/medicines', 'store')->name('admin.medicines.store');
            Route::get('/medicines/history', 'history')->name('admin.medicines.history');
            Route::get('/medicines/{id}/edit', 'edit')->name('admin.medicines.edit');
            Route::put('/medicines/{id}', 'update')->name('admin.medicines.update');
            Route::delete('/medicines/{id}', 'destroy')->name('admin.medicines.delete');
            Route::post('/medicines/{id}/release', 'release')->name('admin.medicines.release');
        });

        // Patients
        Route::controller(AdminController::class)->group(function () {
            Route::get('/patients', 'indexPatients')->name('admin.patients.index');
            Route::get('/patients/{id}', 'showPatient')->name('admin.patients.show');
            Route::delete('/patients/{id}', 'destroy')->name('admin.patients.delete');
            Route::post('/patients/{id}/force-verify', 'verifyPatient');
        });

        // Records
        Route::get('/appointments/{id}/diagnose', [MedicalRecordController::class, 'create'])->name('admin.records.create');
        Route::post('/appointments/{id}/diagnose', [MedicalRecordController::class, 'store'])->name('admin.records.store');
    });
});