@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">Inventory History</h2>
    <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-secondary">
        &larr; Back to Inventory
    </a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>Medicine</th>
                        <th>Action</th>
                        <th>Qty Change</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $log)
                    <tr>
                        <td class="text-muted small">{{ $log->performed_at->format('M d, Y h:i A') }}</td>
                        <td class="fw-bold">{{ $log->medicine_name }}</td>
                        <td>
                            @if($log->action_type == 'Added')
                                <span class="badge bg-success">Added</span>
                            @elseif($log->action_type == 'Edited')
                                <span class="badge bg-warning text-dark">Edited</span>
                            @elseif($log->action_type == 'Released')
                                <span class="badge bg-info text-dark">Released</span>
                            @elseif($log->action_type == 'Deleted')
                                <span class="badge bg-danger">Deleted</span>
                            @else
                                <span class="badge bg-secondary">{{ $log->action_type }}</span>
                            @endif
                        </td>
                        <td>
                            @if($log->quantity_changed > 0)
                                <span class="text-success">+{{ $log->quantity_changed }}</span>
                            @elseif($log->quantity_changed < 0)
                                <span class="text-danger">{{ $log->quantity_changed }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $log->description }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No history records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection