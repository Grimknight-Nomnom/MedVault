@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">Medicine Inventory</h2>
    <div>
        <a href="{{ route('admin.medicines.history') }}" class="btn btn-secondary me-2">
            <i class="bi bi-clock-history"></i> View History
        </a>
        <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Medicine
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4 border-0">
    <div class="card-body p-3">
        <form action="{{ route('admin.medicines.index') }}" method="GET" class="d-flex gap-2 align-items-center">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" 
                       placeholder="Search by medicine name or category..." 
                       value="{{ request('search') }}">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </div>
            @if(request()->filled('search'))
                <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-secondary">Clear</a>
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
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Expiry (Month/Year)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $medicine)
                    <tr>
                        <td class="fw-bold">{{ $medicine->name }}</td>
                        <td><span class="badge bg-info text-dark">{{ $medicine->category }}</span></td>
                        <td>
                            @if($medicine->stock_quantity < 10)
                                <span class="badge bg-danger">Low: {{ $medicine->stock_quantity }}</span>
                            @else
                                <span class="badge bg-success">{{ $medicine->stock_quantity }}</span>
                            @endif
                        </td>
                        <td>{{ $medicine->expiry_date->format('F Y') }}</td>
                        <td class="text-end">
                            <button type="button" 
                                    class="btn btn-sm btn-info text-white me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#releaseModal{{ $medicine->id }}">
                                Release
                            </button>

                            <a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="btn btn-sm btn-warning text-dark me-1">Edit</a>

                            <form action="{{ route('admin.medicines.delete', $medicine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this medicine?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="releaseModal{{ $medicine->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">Release Medicine: {{ $medicine->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.medicines.release', $medicine->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Select Patient</label>
                                            <select name="patient_id" class="form-select" required>
                                                <option value="" disabled selected>Choose a patient...</option>
                                                @foreach($patients as $patient)
                                                    <option value="{{ $patient->id }}">
                                                        {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->usernumber }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Quantity to Release</label>
                                            <input type="number" name="quantity" class="form-control" min="1" max="{{ $medicine->stock_quantity }}" required>
                                            <small class="text-muted">Current Stock: {{ $medicine->stock_quantity }}</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-info text-white">Confirm Release</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No medicines found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection