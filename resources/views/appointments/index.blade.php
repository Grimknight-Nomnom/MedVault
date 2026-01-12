@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">My Appointments</h2>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-2"></i>Book New
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            @if($appointments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3 opacity-50"></i>
                    <p class="text-muted">You have no appointment history.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Queue #</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 pe-4">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $apt)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">
                                    #{{ str_pad($apt->queue_number, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    {{-- Safely parse the date even if model casting fails --}}
                                    {{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}
                                </td>
                                <td>
                                    @if($apt->status == 'pending')
                                        <span class="badge bg-warning text-dark border border-warning">Pending</span>
                                    @elseif($apt->status == 'approved')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">Approved</span>
                                    @elseif($apt->status == 'cancelled')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Cancelled</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">Completed</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-muted small">
                                    {{ Str::limit($apt->reason, 50) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection