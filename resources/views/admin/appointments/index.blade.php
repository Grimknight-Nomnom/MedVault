@extends('layouts.app')

@section('content')
<h2>Manage Appointments</h2>

<div class="card shadow mt-4">
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Date & Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $apt)
                <tr>
                    <td>{{ $apt->id }}</td>
                    <td>{{ $apt->user->name }} <br> <small class="text-muted">{{ $apt->user->phone }}</small></td>
                    <td>{{ $apt->appointment_date->format('M d, Y h:i A') }}</td>
                    <td>{{ $apt->reason }}</td>
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
                    <td>
                        <div class="d-flex gap-2">
                            @if($apt->status == 'pending')
                            <form action="{{ route('admin.appointments.update', $apt->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button class="btn btn-success btn-sm">Approve</button>
                            </form>
                            @endif

                            @if($apt->status != 'cancelled' && $apt->status != 'completed')
                            <form action="{{ route('admin.appointments.update', $apt->id) }}" method="POST" onsubmit="return confirm('Cancel this appointment?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                            @endif

                            @if($apt->status == 'approved')
                            <form action="{{ route('admin.appointments.update', $apt->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button class="btn btn-primary btn-sm">Complete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection