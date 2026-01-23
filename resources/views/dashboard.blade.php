@extends('layouts.app')

@section('content')
<style>
    /* Professional Light Green Clinic Theme */
    body { background-color: #f0fdf4; } 
    
    .card { 
        border: none; 
        border-radius: 1.25rem; 
        transition: all 0.3s ease;
    }
    
    .card:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }

    .btn-green-primary {
        background-color: #16a34a; 
        border-color: #16a34a;
        color: white;
        font-weight: 600;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
    }

    .btn-green-primary:hover {
        background-color: #15803d; 
        border-color: #15803d;
        color: white;
    }

    .icon-box {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border-radius: 1rem;
    }
</style>

@php
    // Check for missing Demographics fields directly in the view for UI logic
    $user = Auth::user();
    $requiredFields = ['first_name', 'last_name', 'date_of_birth', 'gender', 'civil_status', 'address', 'phone'];
    $isProfileIncomplete = false;
    foreach($requiredFields as $field) {
        if(empty($user->$field)) {
            $isProfileIncomplete = true;
            break;
        }
    }
@endphp

<div class="container py-4">
    
    @if($isProfileIncomplete)
    <div class="alert alert-warning border-start border-warning border-4 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">Action Required: Complete Your Personal Records</h5>
                <p class="mb-0 small">You must fill out your demographics and medical history before you can book an appointment.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-sm ms-auto fw-bold px-4">Complete Now</a>
        </div>
    </div>
    @endif

    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-success text-white border-0 shadow-lg" style="background: linear-gradient(135deg, #16a34a, #4ade80);">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-md-8 text-center text-md-start">
                            <h1 class="display-5 fw-bold mb-3">Hello, {{ Auth::user()->first_name ?? 'User' }}!</h1>
                            <p class="lead mb-4 opacity-90">Manage your health and check for available free medications in real-time.</p>
                            
                            @if($isProfileIncomplete)
                                <a href="{{ route('profile.edit') }}" class="btn btn-light text-danger fw-bold px-4 py-2 rounded-pill shadow-sm">
                                    <i class="fas fa-user-edit me-2"></i>Complete Personal Records to Book
                                </a>
                            @else
                                <a href="{{ route('appointments.create') }}" class="btn btn-light text-success fw-bold px-4 py-2 rounded-pill shadow-sm">
                                    <i class="fas fa-plus-circle me-2"></i>Book New Appointment
                                </a>
                            @endif
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <i class="fas fa-heartbeat fa-6x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($activeAppointment) && $activeAppointment)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 border-start border-5 border-primary shadow-sm">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">
                            <i class="fas fa-calendar-check me-2"></i>Active Appointment
                        </h5>
                        <p class="mb-0 text-muted">
                            Scheduled for: <strong>{{ $activeAppointment->appointment_date->format('F d, Y') }}</strong>
                        </p>
                        <div class="mt-2 d-flex align-items-center gap-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                Status: {{ ucfirst($activeAppointment->status) }}
                            </span>
                            
                            <form action="{{ route('appointments.destroy', $activeAppointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                                    <i class="fas fa-trash-alt me-1"></i> Cancel Appointment
                                </button>
                            </form>
                            </div>
                    </div>
                    <div class="text-center">
                        <small class="text-uppercase text-muted fw-bold d-block">Your Queue</small>
                        <h2 class="display-4 fw-bold text-primary mb-0">#{{ str_pad($activeAppointment->queue_number, 3, '0', STR_PAD_LEFT) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-top border-4 border-info">
                <div class="card-body text-center p-4 d-flex flex-column">
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="fas fa-user-circle fa-3x"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Personal Records</h4>
                    <p class="text-muted small mb-4">Update your profile, contact details, and basic demographics.</p>
                    <div class="mt-auto">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-info w-100 rounded-pill fw-bold">
                            {{ $isProfileIncomplete ? 'Update Now (Required)' : 'Update Profile' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-top border-4 border-success">
                <div class="card-body text-center p-4 d-flex flex-column">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fas fa-pills fa-3x"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Medicines</h4>
                    <p class="text-muted small mb-4">View list of medicine possible available for free at our clinic.</p>
                    <div class="mt-auto">
                        <a href="{{ route('patient.medicines.index') }}" class="btn btn-outline-success w-100 rounded-pill fw-bold">
                            <i class="fas fa-search me-2"></i>Check Medicine
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-top border-4 border-warning">
                <div class="card-body text-center p-4 d-flex flex-column">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-calendar-alt fa-3x"></i>
                    </div>
                    <h4 class="fw-bold mb-3">My Appointments</h4>
                    <p class="text-muted small mb-4">Track your upcoming check-ups or view your visitation history.</p>
                    <div class="mt-auto">
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-warning text-dark w-100 rounded-pill fw-bold">View Schedule</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 justify-content-center">
        <div class="col-md-4">
            <a href="{{ route('patient.records') }}" class="btn btn-link text-success text-decoration-none fw-bold d-block text-center">
                <i class="fas fa-history me-2"></i>View Full Medical History
            </a>
        </div>
    </div>
</div>
@endsection