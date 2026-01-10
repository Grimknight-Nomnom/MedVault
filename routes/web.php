<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController; // Ensure this is imported

// Landing Page (Show the welcome view)
Route::get('/', function () {
    return view('welcome');
});

// -- Authentication Routes --
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -- Registration Routes (UPDATED) --
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// -- Protected Routes (Only accessible if logged in) --
Route::middleware(['auth'])->group(function () {

    // Patient: View History
    Route::get('/my-medical-records', [MedicalRecordController::class, 'myRecords'])->name('patient.records');
    
    // Patient Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // -- Patient Appointment Routes --
    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/book-appointment', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/book-appointment', [AppointmentController::class, 'store'])->name('appointments.store');

    // Admin Dashboard (Base Route)
    Route::get('/admin/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // -- ADMIN GROUP ROUTES --
    Route::prefix('admin')->group(function () {
        
        // Medicine Inventory Routes
        Route::get('/medicines', [MedicineController::class, 'index'])->name('admin.medicines.index');
        Route::get('/medicines/create', [MedicineController::class, 'create'])->name('admin.medicines.create');
        Route::post('/medicines', [MedicineController::class, 'store'])->name('admin.medicines.store');
        Route::delete('/medicines/{id}', [MedicineController::class, 'destroy'])->name('admin.medicines.delete');

        // Appointment Management Routes
        Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments.index');
        Route::patch('/appointments/{id}', [AppointmentController::class, 'updateStatus'])->name('admin.appointments.update');

        // Admin: Create Medical Record
        Route::get('/appointments/{id}/diagnose', [MedicalRecordController::class, 'create'])->name('admin.records.create');
        Route::post('/appointments/{id}/diagnose', [MedicalRecordController::class, 'store'])->name('admin.records.store');
    });

});