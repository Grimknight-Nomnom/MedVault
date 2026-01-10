<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AppointmentController; // Import this!

// Landing Page (Redirect to Login for now)
Route::get('/', function () {
    return redirect()->route('login');
});

// -- Authentication Routes --
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -- Registration Placeholder --
Route::get('/register', function() {
    return "Registration Page Coming Soon";
})->name('register');


// -- Protected Routes (Only accessible if logged in) --
Route::middleware(['auth'])->group(function () {
    
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

        // Appointment Management Routes (NEW)
        Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('admin.appointments.index');
        Route::patch('/appointments/{id}', [AppointmentController::class, 'updateStatus'])->name('admin.appointments.update');
    });

});