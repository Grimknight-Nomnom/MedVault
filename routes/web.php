<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Landing Page (Redirect to Login for now)
Route::get('/', function () {
    return redirect()->route('login');
});

// -- Authentication Routes --
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -- Registration Placeholder (We will build this later if needed) --
Route::get('/register', function() {
    return "Registration Page Coming Soon";
})->name('register');


// -- Protected Routes (Only accessible if logged in) --
Route::middleware(['auth'])->group(function () {
    
    // Patient Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin Dashboard (Middleware check for role usually goes here)
    Route::get('/admin/dashboard', function () {
        // Simple role check for security
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

});