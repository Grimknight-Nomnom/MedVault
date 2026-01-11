<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// -- Authentication Routes --
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -- Registration Routes --
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// -- Protected Routes --
Route::middleware(['auth'])->group(function () {

    Route::get('/api/appointments/slots', [AppointmentController::class, 'getSlots'])->name('api.appointments.slots');

    // Patient: View History
    Route::get('/my-medical-records', [MedicalRecordController::class, 'myRecords'])->name('patient.records');
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // -- Patient Appointment Routes --
    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/book-appointment', [AppointmentController::class, 'store'])->name('appointments.store');

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // -- ADMIN GROUP ROUTES --
    Route::prefix('admin')->group(function () {
        
        // --- Medicine Inventory Routes (UPDATED) ---
        Route::get('/medicines', [MedicineController::class, 'index'])->name('admin.medicines.index');
        Route::post('/medicines', [MedicineController::class, 'store'])->name('admin.medicines.store');
        Route::get('/medicines/create', [MedicineController::class, 'create'])->name('admin.medicines.create');
        Route::delete('/medicines/{id}', [MedicineController::class, 'destroy'])->name('admin.medicines.delete');
        
        // New Routes for Refactored Medicine Module
        Route::get('/medicines/history', [MedicineController::class, 'history'])->name('admin.medicines.history');
        Route::get('/medicines/{id}/edit', [MedicineController::class, 'edit'])->name('admin.medicines.edit');
        Route::put('/medicines/{id}', [MedicineController::class, 'update'])->name('admin.medicines.update');
        Route::post('/medicines/{id}/release', [MedicineController::class, 'release'])->name('admin.medicines.release');

        // --- Appointment Management Routes ---
        Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments.index');
        Route::patch('/appointments/{id}', [AppointmentController::class, 'updateStatus'])->name('admin.appointments.update');

        // --- Medical Record Routes ---
        Route::get('/appointments/{id}/diagnose', [MedicalRecordController::class, 'create'])->name('admin.records.create');
        Route::post('/appointments/{id}/diagnose', [MedicalRecordController::class, 'store'])->name('admin.records.store');
    });

});