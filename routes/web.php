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

Route::get('/link-storage', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return "Storage link created successfully!";
    } catch (\Exception $e) {
        return "Failed to create storage link: " . $e->getMessage();
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

// <-- ADDED: Catch the verification notice and bounce them back to login
Route::get('/email/verify', function () {
    Auth::logout();
    return redirect()->route('login')->withErrors([
        'login_identifier' => 'You must verify your email address to access this page. Please check your inbox.'
    ]);
})->name('verification.notice');

// <-- MODIFIED: Added 'signed' middleware
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
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

// <-- MODIFIED: Added 'verified' middleware here to protect the dashboard and internal pages
Route::middleware(['auth', 'verified'])->group(function () {

// Add this inside the main `middleware(['auth', 'verified'])` block, next to the admin prefix

// --- STAFF ROUTES ---
Route::prefix('staff')->middleware(['auth', 'verified', 'can:staff'])->group(function () {
    // Staff Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\StaffController::class, 'dashboard'])->name('staff.dashboard');

    // Announcements (Staff can view, create, and edit, but NO DELETE)
    Route::get('/announcements', [\App\Http\Controllers\AdminAnnouncementController::class, 'index'])->name('staff.announcements.index');
    Route::get('/announcements/create', [\App\Http\Controllers\AdminAnnouncementController::class, 'create'])->name('staff.announcements.create');
    Route::post('/announcements', [\App\Http\Controllers\AdminAnnouncementController::class, 'store'])->name('staff.announcements.store');
    Route::get('/announcements/{announcement}/edit', [\App\Http\Controllers\AdminAnnouncementController::class, 'edit'])->name('staff.announcements.edit');
    Route::put('/announcements/{announcement}', [\App\Http\Controllers\AdminAnnouncementController::class, 'update'])->name('staff.announcements.update');

    // Staff Member Management (Staff can add and edit, but NO DELETE)
    Route::post('/staff', [\App\Http\Controllers\AdminAnnouncementController::class, 'storeStaff'])->name('staff.staff.store');
    Route::put('/staff/{staff}', [\App\Http\Controllers\AdminAnnouncementController::class, 'updateStaff'])->name('staff.staff.update');

    // ADD THESE: Read-Only Announcements (Active Posts)
    Route::get('/announcements', [\App\Http\Controllers\AdminAnnouncementController::class, 'index'])->name('staff.announcements.index');

    // ADD THESE: Chart API Routes for Staff Dashboard
    Route::get('/api/trends', [\App\Http\Controllers\MedicineController::class, 'getTrendsData'])->name('staff.trends.api');
    Route::get('/api/report', [\App\Http\Controllers\MedicineController::class, 'getPeekData'])->name('staff.report.api');

    // RESTRICTED ACCOUNT VIEWING (Read-Only)
    Route::get('/patients', [\App\Http\Controllers\AdminController::class, 'indexPatients'])->name('staff.patients.index');
    Route::get('/patients/{id}', [\App\Http\Controllers\AdminController::class, 'showPatient'])->name('staff.patients.show');

    // MEDICINE INVENTORY (Read-Only)
    Route::get('/medicines', [\App\Http\Controllers\MedicineController::class, 'index'])->name('staff.medicines.index');
    Route::get('/medicines/history', [\App\Http\Controllers\MedicineController::class, 'history'])->name('staff.medicines.history');

    // ADD THESE: Patient Verification Routes for Staff
    Route::post('/patients/{id}/force-verify', [\App\Http\Controllers\AdminController::class, 'verifyPatient'])->name('staff.patients.verify');
    Route::post('/patients/{id}/reject-residency', [\App\Http\Controllers\AdminController::class, 'rejectResidency'])->name('staff.patients.reject_residency');
    Route::post('/patients/{id}/approve-residency', [\App\Http\Controllers\AdminController::class, 'approveResidency'])->name('staff.patients.approve_residency');

    // ADD THESE: Export Medicines
    Route::get('/medicines/export-expired', [\App\Http\Controllers\MedicineController::class, 'exportExpired'])->name('staff.medicines.export_expired');

    // ADD THESE: Book Walk-In Appointments
    Route::get('/appointments/create', [\App\Http\Controllers\AppointmentController::class, 'adminCreate'])->name('staff.appointments.create');
    Route::post('/appointments', [\App\Http\Controllers\AppointmentController::class, 'adminStore'])->name('staff.appointments.store');

    // APPOINTMENTS (Read-Only)
    Route::get('/appointments', [\App\Http\Controllers\AppointmentController::class, 'adminIndex'])->name('staff.appointments.index');
});

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

    Route::prefix('admin')->middleware(['can:admin'])->group(function () {
Route::post('/patients/{id}/reject-residency', [App\Http\Controllers\AdminController::class, 'rejectResidency'])->name('admin.patients.reject_residency');
        Route::post('/patients/{id}/approve-residency', [App\Http\Controllers\AdminController::class, 'approveResidency'])->name('admin.patients.approve_residency');
        
        Route::post('/staff', [\App\Http\Controllers\AdminAnnouncementController::class, 'storeStaff'])->name('admin.staff.store');
Route::put('/staff/{staff}', [\App\Http\Controllers\AdminAnnouncementController::class, 'updateStaff'])->name('admin.staff.update');
Route::delete('/staff/{staff}', [\App\Http\Controllers\AdminAnnouncementController::class, 'destroyStaff'])->name('admin.staff.destroy');
Route::post('/staff/{id}/deactivate', [\App\Http\Controllers\AdminAnnouncementController::class, 'deactivateStaff'])->name('admin.staff.deactivate');
Route::post('/staff/{id}/reactivate', [\App\Http\Controllers\AdminAnnouncementController::class, 'reactivateStaff'])->name('admin.staff.reactivate');

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
            Route::get('/medicines/export-expired', 'exportExpired')->name('admin.medicines.export_expired');
            
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
            
            Route::post('/patients/{id}/pregnancy-record', 'createPregnancyRecord')->name('admin.patients.create_pregnancy');
            Route::put('/patients/{id}/pregnancy-record', 'updatePregnancyRecord')->name('admin.patients.update_pregnancy');
            Route::put('/patients/{id}/pregnancy-record/complete', 'completePregnancyRecord')->name('admin.patients.complete_pregnancy');
            
            Route::post('/patients/{id}/immunization-record', 'createImmunizationRecord')->name('admin.patients.create_immunization');
            Route::put('/patients/{id}/immunization-record', 'updateImmunizationRecord')->name('admin.patients.update_immunization');
            Route::put('/patients/{id}/immunization-record/complete', 'completeImmunizationRecord')->name('admin.patients.complete_immunization');
        });

Route::get('/appointments/{id}/diagnose', [MedicalRecordController::class, 'create'])->name('admin.records.create');
        Route::post('/appointments/{id}/diagnose', [MedicalRecordController::class, 'store'])->name('admin.records.store');
        Route::get('/records/{record}/edit', [MedicalRecordController::class, 'edit'])->name('admin.records.edit');
        Route::put('/records/{record}', [MedicalRecordController::class, 'update'])->name('admin.records.update');
    }); // <-- End of admin group

    // --- ADD THE STAFF ROUTE GROUP HERE ---
    Route::prefix('staff')->middleware(['can:staff'])->group(function () {
        // Staff Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\StaffController::class, 'dashboard'])->name('staff.dashboard');

        // Patients (Read-Only)
        Route::get('/patients', [AdminController::class, 'indexPatients'])->name('staff.patients.index');
        Route::get('/patients/{id}', [AdminController::class, 'showPatient'])->name('staff.patients.show');

        // Medicines (Read-Only)
        Route::get('/medicines', [MedicineController::class, 'index'])->name('staff.medicines.index');
        Route::get('/medicines/history', [MedicineController::class, 'history'])->name('staff.medicines.history');

        // Appointments (Read-Only)
        Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('staff.appointments.index');
    });


    // ---------------------------------------

}); // <-- End of auth, verified group