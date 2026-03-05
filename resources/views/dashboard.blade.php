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
    $user = Auth::user();
    
    // --- CHANGED: Now checks admin_verified_at instead of email_verified_at ---
    $isVerified = !is_null($user->admin_verified_at);
    
    $missingPhoto = empty($user->patient_photo_path);
    $hasRejection = !empty($user->residency_rejection_reason);

    $needsOwnResidency = $user->needs_own_residency;
    $adultChildrenNeedingResidency = $user->children ? $user->children->filter->needs_own_residency : collect();

    $requiredFields = ['first_name', 'last_name', 'date_of_birth', 'gender', 'civil_status', 'address', 'phone'];
    $missingDemographics = false;
    foreach($requiredFields as $field) {
        if(empty($user->$field)) {
            $missingDemographics = true;
            break;
        }
    }
    
    // Overall incomplete flag
    $isProfileIncomplete = $missingPhoto || $missingDemographics || $needsOwnResidency;

    // --- NEW: Fetch recently cancelled appointments to notify the user ---
    $familyIdsForCancellation = collect([$user->id])->merge($user->children ? $user->children->pluck('id') : collect());
    $recentCancellations = \App\Models\Appointment::whereIn('user_id', $familyIdsForCancellation)
        ->where('status', 'cancelled')
        ->whereNotNull('cancellation_reason')
        ->where('updated_at', '>=', now()->subDays(7)) // Show cancellations from the last 7 days
        ->orderBy('updated_at', 'desc')
        ->get();
@endphp

<div class="container py-4">

{{-- CANCELLED APPOINTMENT ALERTS --}}
    @if(isset($recentCancellations) && $recentCancellations->count() > 0)
        @foreach($recentCancellations as $cancelledAppt)
            <div class="alert alert-danger border-start border-danger border-4 shadow-sm mb-4 position-relative">
                
                {{-- CLOSE BUTTON TO DELETE THE RECORD --}}
                <form action="{{ route('appointments.destroy', $cancelledAppt->id) }}" method="POST" class="position-absolute top-0 end-0 mt-2 me-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-close shadow-none" title="Dismiss Notification" aria-label="Close"></button>
                </form>

                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-times fa-2x me-3 text-danger"></i>
                    <div class="w-100 pe-4"> {{-- Added pe-4 to prevent text from hitting the close button --}}
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="alert-heading fw-bold mb-0">Appointment Cancelled by Admin</h5>
                            <span class="small text-muted">{{ $cancelledAppt->updated_at->diffForHumans() }}</span>
                        </div>
                        <p class="mb-2 small">
                            The scheduled appointment for <strong>{{ $cancelledAppt->user_id === Auth::id() ? 'You' : $cancelledAppt->user->first_name }}</strong> on <strong>{{ \Carbon\Carbon::parse($cancelledAppt->appointment_date)->format('F d, Y') }}</strong> has been cancelled.
                        </p>
                        <div class="bg-white p-2 rounded border border-danger border-opacity-25 text-dark fw-bold mb-0">
                            <i class="fas fa-comment-dots text-danger me-2"></i> Reason: "{{ $cancelledAppt->cancellation_reason }}"
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- ALERT LOGIC: Only show ONE relevant warning at a time to prevent double messages --}}
    
    @if($hasRejection && $missingPhoto)
        <div class="alert alert-danger border-start border-danger border-4 shadow-lg mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-times-circle fa-3x me-3 text-danger"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1 text-danger">Residency Document Rejected</h5>
                    <p class="mb-1 small">The admin could not verify your document. Reason:</p>
                    <div class="bg-white p-2 rounded border border-danger border-opacity-25 text-dark fw-bold mb-2">
                        "{{ $user->residency_rejection_reason }}"
                    </div>
                    <p class="mb-0 small">Please retake the photo or upload a clearer, readable image to proceed.</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-danger ms-auto fw-bold px-4 py-2 shadow-sm rounded-pill">Upload New Document</a>
            </div>
        </div>

    {{-- If the logged in user is 18+ and needs to upload their own ID --}}
    @elseif($needsOwnResidency)
        <div class="alert alert-danger border-start border-danger border-4 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-id-card fa-2x me-3 text-danger"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Adult Dependent Update Required</h5>
                    <p class="mb-0 small">You are now 18 or older. You must <strong>upload your own Proof of Residency</strong> in your own name to continue booking appointments.</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-danger btn-sm ms-auto fw-bold px-4">Upload Now</a>
            </div>
        </div>

    @elseif($missingPhoto)
        <div class="alert alert-danger border-start border-danger border-4 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-image fa-2x me-3 text-danger"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Proof of Residency Required</h5>
                    <p class="mb-0 small">You must <strong>upload a valid Proof of Residency / Indigency</strong> before the admin can verify your account.</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-danger btn-sm ms-auto fw-bold px-4">Upload Now</a>
            </div>
        </div>

    {{-- If the logged in PARENT has children who turned 18 --}}
    @elseif($adultChildrenNeedingResidency->count() > 0)
        <div class="alert alert-warning border-start border-warning border-4 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-2x me-3 text-warning"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Dependent Update Required</h5>
                    <p class="mb-0 small">Your dependent(s) <strong>{{ $adultChildrenNeedingResidency->pluck('first_name')->implode(', ') }}</strong> are now 18 or older. They must log in to their own account to upload their own Proof of Residency before they can be booked for appointments.</p>
                </div>
            </div>
        </div>

    @elseif(!$isVerified)
        <div class="alert alert-warning border-start border-warning border-4 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-clock fa-2x me-3 text-warning"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Account Pending Verification</h5>
                    <p class="mb-0 small">Your proof of residency is on file. You can view your records, but <strong>you cannot book an appointment until the admin approves your account.</strong></p>
                </div>
            </div>
        </div>
    @elseif($missingDemographics)
        <div class="alert alert-warning border-start border-warning border-4 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Action Required: Complete Your Profile</h5>
                    <p class="mb-0 small">Please fill out the rest of your personal records (Gender, Civil Status, etc.) to book an appointment.</p>
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
                            
                            @if($missingPhoto || $needsOwnResidency)
                                <a href="{{ route('profile.edit') }}" class="btn btn-light text-danger fw-bold px-4 py-2 rounded-pill shadow-sm">
                                    <i class="fas fa-upload me-2"></i>Upload Residency Document
                                </a>
                            @elseif(!$isVerified)
                                <button class="btn btn-secondary text-white fw-bold px-4 py-2 rounded-pill shadow-sm" disabled style="cursor: not-allowed;">
                                    <i class="fas fa-lock me-2"></i>Pending admin verification
                                </button>
                            @elseif($missingDemographics)
                                <a href="{{ route('profile.edit') }}" class="btn btn-light text-warning fw-bold px-4 py-2 rounded-pill shadow-sm">
                                    <i class="fas fa-user-edit me-2"></i>Complete Profile to Book
                                </a>
                            @else
                                <a href="{{ route('appointments.create') }}" class="btn btn-light text-success fw-bold px-4 py-2 rounded-pill shadow-sm">
                                    <i class="fas fa-plus-circle me-2"></i>Book New Appointment
                                </a>
                            @endif
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="opacity-50" style="width: 180px; height: 180px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- LOOP THROUGH ALL ACTIVE APPOINTMENTS (PARENT + CHILDREN) --}}
    @if(isset($activeAppointments) && $activeAppointments->count() > 0)
        @foreach($activeAppointments as $appt)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 border-start border-5 border-primary shadow-sm">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-primary mb-1 d-flex align-items-center">
                                <i class="fas fa-calendar-check me-2"></i>Active Appointment
                                
                                @if($appt->user_id === Auth::id())
                                    <span class="badge bg-success ms-2 small">For Me</span>
                                @else
                                    <span class="badge bg-info text-dark ms-2 small"><i class="fas fa-child me-1"></i>For {{ $appt->user->first_name }}</span>
                                @endif
                            </h5>
                            <p class="mb-0 text-muted">
                                Scheduled for: <strong>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('F d, Y') }}</strong>
                            </p>
                            <div class="mt-2 d-flex align-items-center gap-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                    Status: {{ ucfirst($appt->status) }}
                                </span>
                                
                                <form action="{{ route('appointments.destroy', $appt->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                                        <i class="fas fa-trash-alt me-1"></i> Cancel Appointment
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="text-center">
                            <small class="text-uppercase text-muted fw-bold d-block">Queue Number</small>
                            <h2 class="display-4 fw-bold text-primary mb-0">#{{ str_pad($appt->queue_number, 3, '0', STR_PAD_LEFT) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-top border-4 border-info">
                <div class="card-body text-center p-4 d-flex flex-column">
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="fas fa-user-circle fa-3x"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Personal Records</h4>
                    <p class="text-muted small mb-4">Update your profile, upload your ID, and basic demographics.</p>
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