@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-primary mb-1">Medicine Inventory History</h2>
            <p class="text-muted small mb-0">Track all changes to medicine inventory.</p>
        </div>
        <a href="{{ route(auth()->user()->role . '.medicines.index') }}" class="btn btn-secondary shadow-sm rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Back to Inventory
        </a>
    </div>

    {{-- Search Card --}}
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-body p-3">
            <form action="{{ route(auth()->user()->role . '.medicines.history') }}" method="GET" class="d-flex gap-2 align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted ps-3"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-2 shadow-none" 
                           placeholder="Search by medicine name, action, or date..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary px-4" type="submit">Search</button>
                </div>
                @if(request()->filled('search'))
                    <a href="{{ route(auth()->user()->role . '.medicines.history') }}" class="btn btn-light border text-muted" title="Clear Search">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- History Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Activity Log</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3" style="width: 15%;">Date & Time</th>
                            <th class="py-3" style="width: 20%;">Medicine</th>
                            <th class="py-3 text-center" style="width: 12%;">Action</th>
                            <th class="py-3 text-center" style="width: 10%;">Qty Change</th>
                            <th class="py-3" style="width: 43%;">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $item)
                        <tr>
                            {{-- Date & Time Column --}}
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $item->formatted_date }}</div>
                                <small class="text-muted">{{ $item->formatted_time }}</small>
                            </td>

                            {{-- Medicine Name Column --}}
                            <td>
                                <span class="fw-bold text-dark">{{ $item->medicine_name }}</span>
                            </td>

                            {{-- Action Badge Column --}}
                            <td class="text-center">
                                @if($item->action_type === 'Added')
                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3">
                                        <i class="fas fa-plus-circle me-1"></i>Added
                                    </span>
                                @elseif($item->action_type === 'Deleted')
                                    <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3">
                                        <i class="fas fa-trash-alt me-1"></i>Deleted
                                    </span>
                                @elseif($item->action_type === 'Edited')
                                    <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-3">
                                        <i class="fas fa-edit me-1"></i>Edited
                                    </span>
                                @elseif($item->action_type === 'Released')
                                    <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3">
                                        <i class="fas fa-hand-holding-medical me-1"></i>Released
                                    </span>
                                @elseif($item->action_type === 'Expired')
                                    @if($item->action_taken)
                                        <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ ucfirst($item->action_taken) }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Expired
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-3">
                                        {{ $item->action_type }}
                                    </span>
                                @endif
                            </td>

                            {{-- Qty Change Column --}}
                            <td class="text-center">
                                @if($item->quantity_changed > 0)
                                    <span class="badge bg-success rounded-pill px-3 py-2 fs-6">+{{ $item->quantity_changed }}</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">{{ $item->quantity_changed }}</span>
                                @endif
                            </td>

                            {{-- Description Column --}}
                            <td>
                                <div>
                                    {{ $item->display_description }}
                                </div>
                                {{-- Show notes dropdown if expired with action notes --}}
                                @if($item->action_type === 'Expired' && $item->action_notes)
                                    <div class="mt-2">
                                        <small>
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-2" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#notes{{ $item->id }}"
                                                    aria-expanded="false">
                                                <i class="fas fa-chevron-down me-1"></i>View Notes
                                            </button>
                                        </small>
                                        <div class="collapse mt-2" id="notes{{ $item->id }}">
                                            <div class="alert alert-info border-0 small p-2">
                                                {{ $item->action_notes }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-history fa-3x opacity-25 mb-3"></i>
                                <p>No history records found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection