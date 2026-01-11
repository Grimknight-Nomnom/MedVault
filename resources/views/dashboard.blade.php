@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white border-0 rounded-3 overflow-hidden" style="background: linear-gradient(45deg, #0d6efd, #0dcaf0);">
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-6 fw-bold">Hello, {{ Auth::user()->first_name }}!</h1>
                        <p class="lead mb-4">Welcome to the Barangay Looc Clinic Dashboard. Track your appointments and view medical history securely.</p>
                        <a href="{{ route('appointments.create') }}" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i>Book New Appointment
                        </a>
                    </div>
                    <div class="col-md-4 text-center d-none d-md-block">
                        <i class="fas fa-user-injured fa-6x text-white opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm hover-shadow transition">
            <div class="card-body text-center p-4">
                <div class="mb-3 text-success">
                    <i class="fas fa-file-medical-alt fa-3x"></i>
                </div>
                <h4 class="fw-bold">Medical Records</h4>
                <p class="text-muted small">View your diagnosis history and prescriptions.</p>
                <a href="{{ route('patient.records') }}" class="btn btn-outline-success w-100 mt-2">View Records</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm hover-shadow transition">
            <div class="card-body text-center p-4">
                <div class="mb-3 text-info">
                    <i class="fas fa-id-card fa-3x"></i>
                </div>
                <h4 class="fw-bold">Personal Records</h4>
                <p class="text-muted small">Update your demographics and medical history.</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-info w-100 mt-2">Update Profile</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm hover-shadow transition">
            <div class="card-body text-center p-4">
                <div class="mb-3 text-warning">
                    <i class="fas fa-calendar-check fa-3x"></i>
                </div>
                <h4 class="fw-bold">My Appointments</h4>
                <p class="text-muted small">Check status of your requests or view history.</p>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-warning w-100 mt-2 text-dark">Check Status</a>
            </div>
        </div>
    </div>
</div>
@endsection