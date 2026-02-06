@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">Inventory History</h2>
    <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-secondary">
        &larr; Back to Inventory
    </a>
</div>

<div class="card shadow border-0 mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.medicines.history') }}" method="GET" class="d-flex gap-2">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Search by Medicine, Patient Name, Action, or Date (e.g., Jan 10)..." 
                   value="{{ request('search') }}">
            
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-search"></i> Search
            </button>
            
            @if(request('search'))
                <a href="{{ route('admin.medicines.history') }}" class="btn btn-outline-danger">
                    Clear
                </a>
            @endif
        </form>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Medicine</th>
                        <th>Action</th>
                        <th>Qty Change</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $log)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $log->performed_at->format('F d, Y') }}</div>
                            <small class="text-muted">{{ $log->performed_at->format('h:i A') }}</small>
                        </td>
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
                            @elseif($log->action_type == 'Expired')
                                <span class="badge bg-danger">Expired</span>
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