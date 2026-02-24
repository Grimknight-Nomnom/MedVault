@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Registered Patients</h2>
            <p class="text-muted small mb-0">Manage and view patient records.</p>
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
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr class="{{ is_null($patient->email_verified_at) ? 'bg-danger-subtle' : '' }}">
                            <td class="ps-4 fw-bold text-success">#{{ $patient->usernumber }}</td>
                            <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                            <td>
                                @if(is_null($patient->email_verified_at))
                                    <span class="badge bg-danger">Unverified</span>
                                @else
                                    <span class="badge bg-success">Verified</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Manual Verify Button --}}
                                    @if(is_null($patient->email_verified_at))
                                        <form action="{{ route('admin.patients.verify', $patient->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold" title="Manually Verify">
                                                <i class="fas fa-check-circle"></i> Verify
                                            </button>
                                        </form>
                                    @endif

                                    {{-- View Residency Document Button (FIXED ONCLICK) --}}
                                    @if($patient->patient_photo_path)
                                        <button type="button" 
                                            onclick="openImageModal('{{ asset('storage/' . $patient->patient_photo_path) }}', '{{ addslashes($patient->first_name . ' ' . $patient->last_name) }}', '{{ route('admin.patients.reject_residency', $patient->id) }}')"
                                            class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-white" title="View Indigency / Residency">
                                            <i class="fas fa-file-image"></i> Residency
                                        </button>
                                    @endif

                                    {{-- View Button --}}
                                    <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                        View
                                    </a>
                                    
                                    {{-- Delete Button --}}
                                    <button type="button" 
                                        onclick="openBootstrapDeleteModal('{{ route('admin.patients.delete', $patient->id) }}', 'Are you sure to delete this patient account? It will permanently delete their medical record.')"
                                        class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No patients found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($patients, 'hasPages') && $patients->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $patients->links() }}
                </div>
            @endif
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

{{-- IMAGE VIEWER MODAL WITH REJECT BUTTON --}}
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-image me-2"></i>Residency Document - <span id="imageModalPatientName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light">
                <img id="viewerImage" src="" alt="Residency Document" class="img-fluid rounded shadow-sm border mb-4" style="max-height: 60vh; object-fit: contain;">
                
                {{-- Reject Document Form --}}
                <div class="card border-danger text-start shadow-sm">
                    <div class="card-header bg-danger text-white fw-bold py-2">
                        <i class="fas fa-exclamation-triangle me-1"></i> Reject Document
                    </div>
                    <div class="card-body py-3">
                        <form id="rejectResidencyForm" method="POST" action="">
                            @csrf
                            <label class="form-label small fw-bold text-muted">Reason for Rejection (This will be shown to the patient)</label>
                            <div class="input-group">
                                <input type="text" name="reason" class="form-control" placeholder="e.g. The image is blurry, please retake in a well-lit area." required>
                                <button class="btn btn-outline-danger fw-bold px-4" type="submit">Send Warning & Delete Image</button>
                            </div>
                        </form>
                    </div>
                </div>
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

    // FIXED: Form action now correctly accepts the full route URL generated by Laravel
    function openImageModal(imageUrl, patientName, rejectUrl) {
        document.getElementById('viewerImage').src = imageUrl;
        document.getElementById('imageModalPatientName').innerText = patientName;
        
        // This sets the exact URL regardless of subfolders or hosting environments
        document.getElementById('rejectResidencyForm').action = rejectUrl;
        
        var imageModal = new bootstrap.Modal(document.getElementById('imageViewerModal'));
        imageModal.show();
    }
</script>
@endsection