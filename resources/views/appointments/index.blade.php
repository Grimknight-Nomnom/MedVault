@extends('layouts.app')

@section('content')
<style>
    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>

<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="fas fa-calendar-alt me-2 text-warning"></i>My Appointments</h2>
            <p class="text-muted small mb-0">View and manage upcoming check-ups for you and your dependents.</p>
        </div>
        <a href="{{ route('appointments.create') }}" class="btn btn-success fw-bold rounded-pill shadow-sm px-4 py-2">
            <i class="fas fa-plus-circle me-2"></i>Book New Appointment
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm fw-bold mb-4 rounded-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm fw-bold mb-4 rounded-3">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    @if($appointments->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3 opacity-25"></i>
                <h5 class="fw-bold text-secondary">No Appointments Found</h5>
                <p class="text-muted small">You or your family don't have any appointments booked yet.</p>
                <a href="{{ route('appointments.create') }}" class="btn btn-outline-success rounded-pill fw-bold mt-2 px-4">Book Now</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($appointments as $appointment)
                @php
                    // --- IDENTIFY THE PATIENT (Parent vs Child) ---
                    $isMe = $appointment->user_id === Auth::id();
                    $patientName = $isMe ? 'Myself (' . Auth::user()->first_name . ')' : ($appointment->user ? $appointment->user->first_name : 'Dependent');
                    $badgeClass = $isMe ? 'bg-success' : 'bg-info text-dark';
                    $iconClass = $isMe ? 'fa-user' : 'fa-child';
                    
                    // --- STATUS COLORS ---
                    $statusColors = [
                        'pending' => 'bg-warning text-dark',
                        'approved' => 'bg-primary',
                        'completed' => 'bg-success',
                        'cancelled' => 'bg-danger',
                        'incomplete' => 'bg-secondary'
                    ];
                    $statusBadge = $statusColors[strtolower($appointment->status)] ?? 'bg-secondary';
                @endphp

                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-shadow transition-all {{ strtolower($appointment->status) === 'cancelled' ? 'opacity-75' : '' }}">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                            
                            {{-- Status Badge --}}
                            <span class="badge {{ $statusBadge }} rounded-pill px-3 py-2 text-uppercase fw-bold" style="font-size: 0.7rem;">
                                {{ $appointment->status }}
                            </span>
                            
                            {{-- PATIENT IDENTIFIER BADGE --}}
                            <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 shadow-sm border border-white">
                                <i class="fas {{ $iconClass }} me-1"></i> {{ $patientName }}
                            </span>

                        </div>
                        
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-success me-3 shadow-sm border" style="width: 50px; height: 50px;">
                                    <i class="fas fa-calendar-day fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</h5>
                                    
                                    @if(strtolower($appointment->status) !== 'cancelled')
                                        <p class="text-muted small mb-0 fw-bold">Queue Number: <span class="text-primary fs-6">#{{ str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT) }}</span></p>
                                    @else
                                        <p class="text-danger small mb-0 fw-bold">Queue forfeited</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="bg-light p-3 rounded-3 border border-secondary border-opacity-10">
                                <p class="mb-0 small text-dark">
                                    <strong class="text-muted d-block mb-1"><i class="fas fa-comment-medical me-1"></i> Reason for Visit:</strong>
                                    {{ $appointment->reason }}
                                </p>
                            </div>
                        </div>
                        
                        {{-- Only allow cancellation if the appointment hasn't happened yet and isn't already cancelled/completed --}}
                        @if(in_array(strtolower($appointment->status), ['pending', 'approved']) && \Carbon\Carbon::parse($appointment->appointment_date)->startOfDay()->gte(\Carbon\Carbon::today()))
                            <div class="card-footer bg-white border-top-0 px-4 pb-4 pt-0 text-end">
                                <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="fas fa-times-circle me-1"></i> Cancel Booking
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection