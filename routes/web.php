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

Route::get('/run-migrations', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "Database migration completed successfully!";
    } catch (\Exception $e) {
        return "Migration failed: " . $e->getMessage();
    }
});

Route::get('/', function () {
    $announcements = Announcement::where('is_active', true)->latest()->get();
    $staff = Staff::all();
    return view('welcome', compact('announcements', 'staff'));
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('verification.resend'); 

Route::controller(PasswordResetController::class)->group(function () {
    Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetCode')->name('password.email');
    Route::get('/verify-code', 'showVerifyCodeForm')->name('password.verify');
    Route::post('/verify-code', 'verifyCode')->name('password.verify.post');
    Route::post('/resend-reset-code', 'resendCode')->name('password.resend_code'); 
    Route::get('/reset-password', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete-id/{type}', [ProfileController::class, 'deleteIdImage'])->name('profile.delete_id');
    
    // --- DEPENDENT ROUTES ---
    Route::post('/profile/dependent', [ProfileController::class, 'storeDependent'])->name('profile.dependent.store');
    Route::put('/profile/dependent/{id}', [ProfileController::class, 'updateDependent'])->name('profile.dependent.update');
    Route::delete('/profile/dependent/{id}', [ProfileController::class, 'destroyDependent'])->name('profile.dependent.destroy');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        $userIds = collect([$user->id])->merge($user->children->pluck('id'));

        Appointment::whereIn('user_id', $userIds)
            ->whereIn('status', ['pending', 'approved'])
            ->where('appointment_date', '<', now()->startOfDay())
            ->update(['status' => 'incomplete']);

        // --- FIXED: Fetch ALL active appointments for the family, not just the first one ---
        $activeAppointments = Appointment::with('user')
            ->whereIn('user_id', $userIds)
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('dashboard', compact('activeAppointments'));
    })->name('dashboard');
    
    Route::get('/my-medical-records', [MedicalRecordController::class, 'myRecords'])->name('patient.records');
    Route::get('/medicines-availability', [MedicineController::class, 'patientIndex'])->name('patient.medicines.index');

    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/book-appointment', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    Route::get('/api/appointments/slots', [AppointmentController::class, 'getSlots'])->name('api.appointments.slots');

    Route::prefix('admin')->middleware(['auth', 'can:admin'])->group(function () {
        Route::post('/admin/patients/{id}/reject-residency', [App\Http\Controllers\AdminController::class, 'rejectResidency'])->name('admin.patients.reject_residency');
        
        Route::post('/staff', [\App\Http\Controllers\AdminAnnouncementController::class, 'storeStaff'])->name('admin.staff.store');
        Route::put('/staff/{staff}', [\App\Http\Controllers\AdminAnnouncementController::class, 'updateStaff'])->name('admin.staff.update');
        Route::delete('/staff/{staff}', [\App\Http\Controllers\AdminAnnouncementController::class, 'destroyStaff'])->name('admin.staff.destroy');

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/api/trends', [MedicineController::class, 'getTrendsData'])->name('admin.trends.api');
        Route::get('/api/report', [MedicineController::class, 'getPeekData'])->name('admin.report.api');
        Route::get('/historical-report', [MedicineController::class, 'getHistoricalReport'])->name('admin.historical.report');

        Route::resource('announcements', AdminAnnouncementController::class)
            ->names([
                'index' => 'admin.announcements.index',
                'create' => 'admin.announcements.create',
                'store' => 'admin.announcements.store',
                'edit' => 'admin.announcements.edit',
                'update' => 'admin.announcements.update',
                'destroy' => 'admin.announcements.delete',
            ]);

        Route::controller(AppointmentController::class)->group(function () {
            Route::get('/appointments', 'adminIndex')->name('admin.appointments.index');
            Route::post('/appointments/limit', 'updateDailyLimit')->name('admin.appointments.limit');
            Route::post('/appointments/bulk-limit', 'bulkUpdateLimit')->name('admin.appointments.bulk_limit');
            Route::get('/appointments/create', 'adminCreate')->name('admin.appointments.create');
            Route::post('/appointments', 'adminStore')->name('admin.appointments.store');
            Route::patch('/appointments/{id}', 'updateStatus')->name('admin.appointments.update');
        });

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

        Route::controller(AdminController::class)->group(function () {
            Route::get('/patients', 'indexPatients')->name('admin.patients.index');
            Route::get('/patients/{id}', 'showPatient')->name('admin.patients.show');
            Route::delete('/patients/{id}', 'destroy')->name('admin.patients.delete');
            Route::get('/patients/{id}/edit', 'editPatient')->name('admin.patients.edit');
            Route::put('/patients/{id}', 'updatePatient')->name('admin.patients.update');
            Route::put('/patients/{id}/change-password', 'changePatientPassword')->name('admin.patients.change_password');
            Route::post('/patients/{id}/force-verify', 'verifyPatient')->name('admin.patients.verify');
            Route::delete('/patients/{id}/delete-id/{type}', 'deletePatientId')->name('admin.patients.delete_id');
        });

        Route::get('/appointments/{id}/diagnose', [MedicalRecordController::class, 'create'])->name('admin.records.create');
        Route::post('/appointments/{id}/diagnose', [MedicalRecordController::class, 'store'])->name('admin.records.store');
        Route::get('/records/{record}/edit', [MedicalRecordController::class, 'edit'])->name('admin.records.edit');
        Route::put('/records/{record}', [MedicalRecordController::class, 'update'])->name('admin.records.update');
    });
});