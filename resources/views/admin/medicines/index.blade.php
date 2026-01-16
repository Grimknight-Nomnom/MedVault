@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Medicine Inventory</h2>
            <p class="text-muted small">Manage stock, track expiry dates, and dispense medicines.</p>
        </div>
        <div>
            <a href="{{ route('admin.medicines.history') }}" class="btn btn-secondary me-2 shadow-sm rounded-pill px-4">
                <i class="fas fa-history me-2"></i>View History
            </a>
            <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="fas fa-plus me-2"></i>Add Medicine
            </a>
        </div>
    </div>

    {{-- Search Card --}}
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.medicines.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted ps-3"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-2 shadow-none" 
                           placeholder="Search by medicine name or category..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary px-4" type="submit">Search</button>
                </div>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.medicines.index') }}" class="btn btn-light border text-muted" title="Clear Search">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Main Inventory Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-pills me-2"></i>Medicine List</h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small">
                        <tr>
                            <th class="py-3 ps-4">Name</th>
                            <th class="py-3">Category</th>
                            <th class="py-3 text-center">Stock</th>
                            <th class="py-3">Expiry Date</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $medicine->name }}</td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3">
                                    {{ $medicine->category }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($medicine->stock_quantity < 10)
                                    <span class="badge bg-danger rounded-pill px-3">Low: {{ $medicine->stock_quantity }}</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">{{ $medicine->stock_quantity }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $medicine->expiry_date < now() ? 'text-danger fw-bold' : 'text-dark' }}">
                                    {{ \Carbon\Carbon::parse($medicine->expiry_date)->format('M Y') }}
                                </span>
                                @if($medicine->expiry_date < now())
                                    <i class="fas fa-exclamation-circle text-danger ms-1" title="Expired"></i>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    {{-- Release Button --}}
                                    <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#releaseModal{{ $medicine->id }}" title="Dispense">
                                        <i class="fas fa-box-open"></i> Release
                                    </button>
                                    
                                    {{-- Edit Button --}}
                                    <a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- Delete Button (Triggering Bootstrap Modal) --}}
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        onclick="openBootstrapDeleteModal('{{ route('admin.medicines.delete', $medicine->id) }}', 'Are you sure to delete this medicine? It will permanently delete this medicine.')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                {{-- Release Modal (Inside Loop for ID context) --}}
                                <div class="modal fade" id="releaseModal{{ $medicine->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title fw-bold"><i class="fas fa-hand-holding-medical me-2"></i>Dispense Medicine</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.medicines.release', $medicine->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body text-start p-4">
                                                    <div class="mb-4 p-3 bg-light rounded border border-info border-opacity-25">
                                                        <h6 class="fw-bold text-dark mb-1">{{ $medicine->name }}</h6>
                                                        <small class="text-muted">Current Stock: <span class="badge bg-success">{{ $medicine->stock_quantity }}</span></small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-uppercase text-secondary">Select Patient</label>
                                                        <select name="patient_id" class="form-select" required>
                                                            <option value="" disabled selected>Choose a patient...</option>
                                                            @foreach($patients as $patient)
                                                                <option value="{{ $patient->id }}">
                                                                    {{ $patient->first_name }} {{ $patient->last_name }} 
                                                                    @if($patient->usernumber) (ID: {{ $patient->usernumber }}) @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label fw-bold small text-uppercase text-secondary">Quantity</label>
                                                        <input type="number" name="quantity" class="form-control" min="1" max="{{ $medicine->stock_quantity }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-top-0">
                                                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-info text-white fw-bold px-4 rounded-pill">Confirm Release</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted opacity-50">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <p>No medicines found in inventory.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($medicines instanceof \Illuminate\Pagination\LengthAwarePaginator && $medicines->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $medicines->links() }}
            </div>
        @endif
    </div>
</div>

{{-- GLOBAL DELETE MODAL (BOOTSTRAP VERSION) --}}
<div class="modal fade" id="bootstrapDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3">
                    <i class="fas fa-trash-alt fa-3x opacity-50"></i>
                </div>
                <p class="text-muted fw-bold mb-2">Are you sure?</p>
                <p class="text-secondary small" id="deleteModalBodyMessage">You are about to delete a record.</p>
            </div>
            <div class="modal-footer bg-light border-top-0 justify-content-center">
                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
                <form id="bootstrapDeleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 fw-bold">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openBootstrapDeleteModal(actionUrl, message) {
        // Set the form action dynamically
        document.getElementById('bootstrapDeleteForm').action = actionUrl;
        
        // Set the message dynamically
        if(message) {
            document.getElementById('deleteModalBodyMessage').innerText = message;
        }
        
        // Show the modal using Bootstrap API
        var deleteModal = new bootstrap.Modal(document.getElementById('bootstrapDeleteModal'));
        deleteModal.show();
    }
</script>

@endsection