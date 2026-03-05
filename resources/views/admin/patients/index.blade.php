@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    @if(session('warning'))
        <div class="alert alert-warning fw-bold shadow-sm mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('warning') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Registered Patients</h2>
            <p class="text-muted small mb-0">Manage and view patient records.</p>
        </div>
        
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route(auth()->user()->role . '.patients.index', ['status' => 'unverified']) }}" class="btn btn-warning fw-bold text-dark shadow-sm">
                <i class="fas fa-filter me-1"></i> Unverified Only
            </a>

            @if(request()->has('status') || request()->has('search') && request('search') != '')
                <a href="{{ route(auth()->user()->role . '.patients.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm">Clear Filter</a>
            @endif
            
            <form action="{{ route(auth()->user()->role . '.patients.index') }}" method="GET" class="d-flex gap-2 ms-xl-3">
                <input type="text" name="search" class="form-control border-success shadow-none" 
                       placeholder="Search Name or ID..." value="{{ request('search') }}" style="width: 220px;">
                <button type="submit" class="btn btn-success shadow-sm"><i class="fas fa-search"></i></button>
            </form>
        </div>
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
                        <tr class="{{ is_null($patient->admin_verified_at) || is_null($patient->email_verified_at) ? 'bg-danger-subtle' : '' }}">
                            <td class="ps-4 fw-bold text-success">#{{ $patient->usernumber }}</td>
                            <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                            <td>
                                <div class="d-flex flex-column align-items-start gap-1">
                                    @if(is_null($patient->email_verified_at))
                                        <span class="badge bg-warning text-dark"><i class="fas fa-envelope"></i> Email Unverified</span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Email Verified</span>
                                    @endif

                                    @if(is_null($patient->admin_verified_at))
                                        <span class="badge bg-danger"><i class="fas fa-id-card"></i> Residency Pending</span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Residency Approved</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    
                                    {{-- BOTH ADMIN AND STAFF CAN VERIFY EMAILS AND DOCUMENTS --}}
                                    @if(in_array(auth()->user()->role, ['admin', 'staff']))
                                        
                                        {{-- MANUAL VERIFY EMAIL BUTTON --}}
                                        @if(is_null($patient->email_verified_at))
                                            <form action="{{ route(auth()->user()->role . '.patients.verify', $patient->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark shadow-sm" title="Manually Verify Email">
                                                    <i class="fas fa-envelope-circle-check"></i> Verify Email
                                                </button>
                                            </form>
                                        @endif

                                        {{-- VIEW RESIDENCY DOCUMENT BUTTON --}}
                                        @if($patient->patient_photo_path)
                                            @if(is_null($patient->admin_verified_at))
                                                <button type="button" 
                                                    onclick="openImageModal('{{ asset('storage/' . $patient->patient_photo_path) }}', '{{ addslashes($patient->first_name . ' ' . $patient->last_name) }}', '{{ route(auth()->user()->role . '.patients.reject_residency', $patient->id) }}', '{{ route(auth()->user()->role . '.patients.approve_residency', $patient->id) }}')"
                                                    class="btn btn-danger btn-sm rounded-pill px-3 fw-bold text-white shadow-sm" title="View Indigency / Residency">
                                                    <i class="fas fa-file-image"></i> Not approved residency
                                                </button>
                                            @else
                                                <button type="button" 
                                                    onclick="openImageModal('{{ asset('storage/' . $patient->patient_photo_path) }}', '{{ addslashes($patient->first_name . ' ' . $patient->last_name) }}', '{{ route(auth()->user()->role . '.patients.reject_residency', $patient->id) }}', '{{ route(auth()->user()->role . '.patients.approve_residency', $patient->id) }}')"
                                                    class="btn btn-success btn-sm rounded-pill px-3 fw-bold text-white shadow-sm" title="View Indigency / Residency">
                                                    <i class="fas fa-file-image"></i> Approved residency
                                                </button>
                                            @endif
                                        @endif
                                    @endif

                                    {{-- ONLY ADMIN CAN VIEW PROFILE AND DELETE ACCOUNT --}}
                                    @if(auth()->user()->role === 'admin')
                                        {{-- View Button --}}
                                        <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                            View
                                        </a>
                                        
                                        {{-- Delete Button --}}
                                        <button type="button" 
                                            onclick="openBootstrapDeleteModal('{{ route('admin.patients.delete', $patient->id) }}', 'Are you sure to delete this patient account? It will permanently delete their medical record.')"
                                            class="btn btn-danger btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                    {{-- DISPLAY "READ ONLY" FOR STAFF IF EVERYTHING IS ALREADY VERIFIED --}}
                                    @if(auth()->user()->role === 'staff' && !is_null($patient->email_verified_at) && (!is_null($patient->admin_verified_at) || !$patient->patient_photo_path))
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 shadow-sm">Read Only</span>
                                    @endif

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

@if(auth()->user()->role === 'admin')
{{-- GLOBAL BOOTSTRAP DELETE MODAL (ADMIN ONLY) --}}
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
                <button type="button" class="btn btn-secondary px-4 fw-bold rounded-pill shadow-sm" data-bs-dismiss="modal">Cancel</button>
                <form id="bootstrapDeleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 fw-bold rounded-pill shadow-sm">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- IMAGE VIEWER MODAL WITH DYNAMIC APPROVE/REJECT UI (BOTH STAFF AND ADMIN) --}}
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-image me-2"></i>Residency Document - <span id="imageModalPatientName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light">
                <img id="viewerImage" src="" alt="Residency Document" class="img-fluid rounded shadow-sm border mb-4" style="max-height: 50vh; object-fit: contain;">
                
                {{-- VISIBLE TO BOTH ADMIN AND STAFF --}}
                @if(in_array(auth()->user()->role, ['admin', 'staff']))
                <div id="adminResidencyControls" class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-success text-start shadow-sm h-100">
                            <div class="card-header bg-success text-white fw-bold py-2">
                                <i class="fas fa-check-circle me-1"></i> Approve Document
                            </div>
                            <div class="card-body py-3 d-flex flex-column justify-content-center">
                                <p class="small text-muted mb-3">If the document is valid, click below to verify the patient and allow them to book appointments.</p>
                                <form id="approveResidencyForm" method="POST" action="">
                                    @csrf
                                    <button class="btn btn-success fw-bold w-100 shadow-sm" type="submit">Approve & Verify</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-danger text-start shadow-sm h-100">
                            <div class="card-header bg-danger text-white fw-bold py-2">
                                <i class="fas fa-exclamation-triangle me-1"></i> Reject Document
                            </div>
                            <div class="card-body py-3">
                                <form id="rejectResidencyForm" method="POST" action="">
                                    @csrf
                                    <label class="form-label small fw-bold text-muted mb-1">Reason for Rejection <span class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control mb-3" rows="2" placeholder="e.g. The image is blurry, please retake..." required></textarea>
                                    <button class="btn btn-outline-danger fw-bold w-100 shadow-sm" type="submit" onclick="return confirm('Are you sure you want to delete this document and send a warning?');">Send Warning & Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
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

    function openImageModal(imageUrl, patientName, rejectUrl, approveUrl) {
        document.getElementById('viewerImage').src = imageUrl;
        document.getElementById('imageModalPatientName').innerText = patientName;
        
        if (rejectUrl && approveUrl) {
            document.getElementById('rejectResidencyForm').action = rejectUrl;
            document.getElementById('approveResidencyForm').action = approveUrl;
        }
        
        var imageModal = new bootstrap.Modal(document.getElementById('imageViewerModal'));
        imageModal.show();
    }
</script>
@endsection