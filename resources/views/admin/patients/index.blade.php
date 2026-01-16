@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Registered Patients</h2>
            <p class="text-muted small">Manage and view patient records.</p>
        </div>
        <form action="{{ route('admin.patients.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control border-success shadow-none" 
                   placeholder="Search Name or ID..." value="{{ request('search') }}" style="width: 250px;">
            <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Patient List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase small text-muted">
                            <th class="py-3 ps-4">User ID</th>
                            <th class="py-3">Patient Name</th>
                            <th class="py-3 text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td class="ps-4 fw-bold text-success">#{{ $patient->usernumber }}</td>
                            <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold me-1">
                                    View
                                </a>
                                
                                {{-- Trigger the Bootstrap Delete Modal --}}
                                <button type="button" 
                                    onclick="openBootstrapDeleteModal('{{ route('admin.patients.delete', $patient->id) }}', 'Are you sure to delete this patient account? It will permanently delete their medical record.')"
                                    class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">No patients found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- GLOBAL BOOTSTRAP DELETE MODAL --}}
<div class="modal fade" id="bootstrapDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p id="deleteModalBodyMessage">Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
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
        document.getElementById('bootstrapDeleteForm').action = actionUrl;
        document.getElementById('deleteModalBodyMessage').innerText = message;
        var deleteModal = new bootstrap.Modal(document.getElementById('bootstrapDeleteModal'));
        deleteModal.show();
    }
</script>
@endsection