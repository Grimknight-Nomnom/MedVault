@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark">Admin Dashboard</h2>
        <p class="text-muted">Overview of clinic operations.</p>
    </div>
    <span class="text-muted"><i class="far fa-clock me-1"></i> {{ date('F d, Y') }}</span>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card h-100 border-start border-4 border-primary">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="fas fa-pills fa-2x text-primary"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Medicines</h6>
                    <h3 class="fw-bold mb-0">{{ \App\Models\Medicine::count() }}</h3>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="{{ route('admin.medicines.index') }}" class="text-primary text-decoration-none small fw-bold">Manage Inventory <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-start border-4 border-warning">
            <div class="card-body d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Pending Requests</h6>
                    <h3 class="fw-bold mb-0">{{ \App\Models\Appointment::where('status', 'pending')->count() }}</h3>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="{{ route('admin.appointments.index') }}" class="text-warning text-decoration-none small fw-bold">Review Requests <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-start border-4 border-success">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="fas fa-users fa-2x text-success"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Registered Patients</h6>
                    <h3 class="fw-bold mb-0">{{ \App\Models\User::where('role', 'user')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-rocket me-2 text-primary"></i>Quick Actions</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('admin.medicines.create') }}" class="btn btn-outline-primary"><i class="fas fa-plus me-1"></i> Add Medicine</a>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-dark"><i class="fas fa-list me-1"></i> View Schedule</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
            <h5 class="card-title text-muted fw-bold">Total Appointments (Today)</h5>
            @php
                // Fetch count directly for the dashboard view
                $todayCount = \App\Models\Appointment::whereDate('appointment_date', \Carbon\Carbon::today())->count();
            @endphp
            <h2 class="display-4 fw-bold text-primary">{{ $todayCount }}</h2>
            <p class="text-muted">Scheduled for {{ date('M d, Y') }}</p>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                View Calendar
            </a>
        </div>
    </div>
</div>

@endsection