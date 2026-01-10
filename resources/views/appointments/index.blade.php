@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Appointments</h2>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">Book New</a>
</div>

<div class="card">
    <div class="card-body">
        @if($appointments->isEmpty())
            <p class="text-center text-muted">You have no appointment history.</p>
        @else
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $apt)
                    <tr>
                        <td>{{ $apt->appointment_date->format('M d, Y h:i A') }}</td>
                        <td>
                            @if($apt->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($apt->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($apt->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">Completed</span>
                            @endif
                        </td>
                        <td>{{ $apt->reason }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection