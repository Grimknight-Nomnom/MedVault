<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetController; // Added this import

// --- Public Routes ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- Authentication ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// --- Password Reset Routes (Public) ---
Route::controller(PasswordResetController::class)->group(function () {
    // Step 1: Enter Email
    Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetCode')->name('password.email');
    
    // Step 2: Verify Code
    Route::get('/verify-code', 'showVerifyCodeForm')->name('password.verify');
    Route::post('/verify-code', 'verifyCode')->name('password.verify.post');
    
    // Step 3: Set New Password
    Route::get('/reset-password', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

// --- Protected Routes (Requires Login) ---
Route::middleware(['auth'])->group(function () {

    // Profile & User Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Patient Dashboard & Records
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/my-medical-records', [MedicalRecordController::class, 'myRecords'])->name('patient.records');

    // Patient Medicines
    Route::get('/medicines-availability', [MedicineController::class, 'patientIndex'])->name('patient.medicines.index');

    // Patient Appointments
    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/book-appointment', [AppointmentController::class, 'store'])->name('appointments.store');

    // API Helpers (AJAX)
    Route::get('/api/appointments/slots', [AppointmentController::class, 'getSlots'])->name('api.appointments.slots');
    Route::get('/api/admin/monthly-report', [MedicineController::class, 'getMonthlyReport'])->name('admin.report.api');

    // --- Admin Routes Group ---
    Route::prefix('admin')->group(function () {
        

    Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments.index');
    
    // NEW: Manual Appointment Creation Routes
    Route::get('/appointments/create', [AppointmentController::class, 'adminCreate'])->name('admin.appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'adminStore'])->name('admin.appointments.store');
    
    Route::patch('/appointments/{id}', [AppointmentController::class, 'updateStatus'])->name('admin.appointments.update');
    
        // Dashboard
        Route::get('/dashboard', [MedicineController::class, 'adminDashboard'])->name('admin.dashboard');

        // Historical Report (AJAX)
        Route::get('/historical-report', [MedicineController::class, 'getHistoricalReport'])->name('admin.historical.report');

        // Medicine Inventory Management
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

        // Appointment Management
        Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments.index');
        Route::patch('/appointments/{id}', [AppointmentController::class, 'updateStatus'])->name('admin.appointments.update');

        // Medical Record / Diagnosis
        Route::get('/appointments/{id}/diagnose', [MedicalRecordController::class, 'create'])->name('admin.records.create');
        Route::post('/appointments/{id}/diagnose', [MedicalRecordController::class, 'store'])->name('admin.records.store');

        // Patient Management
        Route::controller(AdminController::class)->group(function () {
            Route::get('/patients', 'indexPatients')->name('admin.patients.index');
            Route::get('/patients/{id}', 'showPatient')->name('admin.patients.show');
        });
    });
});