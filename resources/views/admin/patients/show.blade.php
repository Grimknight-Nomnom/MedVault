@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
        <div class="text-end">
            <span class="badge bg-success fs-6">
                User ID: #{{ $patient->usernumber }}
            </span>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm fw-bold mb-4 rounded-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm fw-bold mb-4 rounded-3">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body text-center pt-5 pb-4">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $patient->address ?? 'No Address Provided' }}
                    </p>
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge bg-light text-dark border">{{ $patient->age }}</span>
                        <span class="badge bg-light text-dark border">{{ $patient->civil_status ?? 'Single' }}</span>
                    </div>
                    <hr class="opacity-10">
                    
                    {{-- Patient Details --}}
                    <div class="row text-start small mt-3">
                        <div class="col-6 mb-2">
                            <label class="text-muted d-block">Gender</label>
                            <span class="fw-bold">{{ $patient->gender ?? 'N/A' }}</span>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="text-muted d-block">Birthday</label>
                            <span class="fw-bold">{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        
                        <div class="col-6 mb-2">
                            <label class="text-muted d-block">Email</label>
                            <span class="fw-bold text-success text-break">{{ $patient->email }}</span>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="text-muted d-block">Contact</label>
                            <span class="fw-bold">{{ $patient->phone ?? 'N/A' }}</span>
                        </div>

                        {{-- Residency Document Section --}}
                        <div class="col-12 mt-3 pt-3 border-top">
                            <label class="text-muted d-block mb-2">Proof of Residency</label>
                            @if($patient->patient_photo_path)
                                <button type="button" onclick="openResidencyModal('{{ asset('storage/' . $patient->patient_photo_path) }}')" class="btn btn-sm btn-outline-info w-100 rounded-pill fw-bold">
                                    <i class="fas fa-file-image me-1"></i> View Indigency Certificate
                                </button>
                            @else
                                <span class="text-danger small fw-bold fst-italic">No Document Uploaded</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex gap-2 border-top pt-4">
                        <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-outline-success w-50 rounded-pill fw-bold shadow-sm" style="font-size: 0.9rem;">
                            <i class="fas fa-user-edit me-1"></i> Edit Info
                        </a>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#resetPasswordModal" class="btn btn-outline-warning w-50 rounded-pill fw-bold shadow-sm" style="font-size: 0.9rem;">
                            <i class="fas fa-key me-1"></i> Password
                        </button>
                    </div>

                    {{-- DYNAMIC BUTTONS: Specialized Record Buttons --}}
                    <div class="mt-3 d-flex flex-column gap-2 border-top pt-3">
                        
                        @php
                            $ageInYears = $patient->date_of_birth ? $patient->date_of_birth->diffInYears(\Carbon\Carbon::now()) : 0;
                            $isOverTwo = $ageInYears > 2;
                            $isUnderFour = $ageInYears < 4;
                        @endphp

                        {{-- PREGNANCY RECORD LOGIC --}}
                        @if($patient->has_pregnancy_record)
                            <button type="button" class="btn btn-outline-danger rounded-pill fw-bold shadow-sm w-100" style="font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#viewPregnancyModal">
                                <i class="fas fa-eye me-1"></i> View Pregnancy Record
                            </button>
                        @elseif(strtolower($patient->gender) === 'male')
                            <button type="button" class="btn btn-secondary rounded-pill fw-bold shadow-sm w-100 opacity-50" style="font-size: 0.85rem;" disabled>
                                <i class="fas fa-ban me-1"></i> Pregnancy N/A (Male)
                            </button>
                        @elseif($isUnderFour)
                            <button type="button" class="btn btn-secondary rounded-pill fw-bold shadow-sm w-100 opacity-50" style="font-size: 0.85rem;" disabled>
                                <i class="fas fa-ban me-1"></i> Pregnancy N/A (< 4 yrs)
                            </button>
                        @else
                            <button type="button" class="btn btn-danger rounded-pill fw-bold shadow-sm w-100" style="font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#confirmPregnancyModal">
                                <i class="fas fa-baby me-1"></i> Create Pregnancy Record
                            </button>
                        @endif

                        {{-- IMMUNIZATION RECORD LOGIC --}}
                        @if($patient->has_immunization_record)
                            <button type="button" class="btn btn-outline-primary rounded-pill fw-bold shadow-sm w-100" style="font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#viewImmunizationModal">
                                <i class="fas fa-eye me-1"></i> View Immunization Record
                            </button>
                        @elseif($isOverTwo)
                            <button type="button" class="btn btn-secondary rounded-pill fw-bold shadow-sm w-100 opacity-50" style="font-size: 0.85rem;" disabled>
                                <i class="fas fa-ban me-1"></i> Immunization N/A (> 2 yrs)
                            </button>
                        @else
                            <button type="button" class="btn btn-primary rounded-pill fw-bold shadow-sm w-100" style="font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#confirmImmunizationModal">
                                <i class="fas fa-syringe me-1"></i> Create Immunization Record
                            </button>
                        @endif
                    </div>
                    
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-success mb-0"><i class="fas fa-file-medical-alt me-2"></i>Health Background</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    
                    {{-- Programs Section --}}
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase mb-2">Programs</label>
                        <div>
                            @if($patient->is_philhealth_member)
                                <div class="d-flex align-items-center mb-2 gap-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">PhilHealth Member</span>
                                    
                                    @if($patient->philhealth_id_path)
                                        <a href="{{ asset('storage/' . $patient->philhealth_id_path) }}" target="_blank" class="btn btn-sm btn-light border text-primary rounded-pill py-0 px-2" title="View ID" style="line-height: 1.2;">
                                            <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <form action="{{ route('admin.patients.delete_id', ['id' => $patient->id, 'type' => 'philhealth']) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this PhilHealth ID?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-pill py-0 px-2" title="Delete ID" style="line-height: 1.2;">
                                                <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary text-white" style="font-size: 0.65rem;">No Image</span>
                                    @endif
                                </div>
                            @endif

                            @if($patient->is_senior_citizen_or_pwd)
                                <div class="d-flex align-items-center mb-2 gap-2">
                                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning">Senior / PWD</span>
                                    
                                    @if($patient->senior_pwd_id_path)
                                        <a href="{{ asset('storage/' . $patient->senior_pwd_id_path) }}" target="_blank" class="btn btn-sm btn-light border text-primary rounded-pill py-0 px-2" title="View ID" style="line-height: 1.2;">
                                            <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <form action="{{ route('admin.patients.delete_id', ['id' => $patient->id, 'type' => 'senior_pwd']) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this Senior/PWD ID?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-pill py-0 px-2" title="Delete ID" style="line-height: 1.2;">
                                                <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary text-white" style="font-size: 0.65rem;">No Image</span>
                                    @endif
                                </div>
                            @endif
                            
                            @if(!$patient->is_philhealth_member && !$patient->is_senior_citizen_or_pwd)
                                <span class="text-muted small">No programs enrolled.</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Allergies</label>
                        <p class="mb-0 small fw-bold text-danger">
                            {{ $patient->allergies ?? 'None Reported' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Existing Conditions</label>
                        <p class="mb-0 small text-dark">
                            {{ $patient->existing_medical_conditions ?? 'None Reported' }}
                        </p>
                    </div>

                    <div>
                        <label class="small text-muted fw-bold text-uppercase">Maintenance Meds</label>
                        <p class="mb-0 small text-dark">
                            {{ $patient->current_medication ?? 'None Reported' }}
                        </p>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-success text-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Clinic Consultation History</h5>
                    
                    {{-- DYNAMIC HEADER VIEW BUTTONS --}}
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        @if($patient->has_pregnancy_record)
                            <button type="button" class="btn btn-sm btn-light text-danger fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#viewPregnancyModal">
                                <i class="fas fa-eye me-1"></i> Pregnancy
                            </button>
                        @endif
                        @if($patient->has_immunization_record)
                            <button type="button" class="btn btn-sm btn-light text-primary fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#viewImmunizationModal">
                                <i class="fas fa-eye me-1"></i> Immunization
                            </button>
                        @endif
                        <span class="badge bg-white text-success ms-1">{{ $consultations->count() }} Records</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Date</th>
                                    <th class="py-3">Diagnosis / Findings</th>
                                    <th class="py-3">Prescription / Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($consultations as $apt)
                                <tr>
                                    <td class="ps-4" style="width: 20%;">
                                        <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('l') }}</small>
                                    </td>
                                    
                                    <td style="width: 35%;">
                                        @if($apt->medicalRecord)
                                            <div class="d-flex justify-content-between align-items-start pe-2">
                                                <span class="fw-bold text-primary">{{ $apt->medicalRecord->diagnosis }}</span>
                                                <a href="{{ route('admin.records.edit', $apt->medicalRecord->id) }}" class="btn btn-sm btn-light border text-primary rounded-pill py-0 px-2 shadow-sm" title="Edit Record">
                                                    <i class="fas fa-edit" style="font-size: 0.75rem;"></i> Edit
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted small fst-italic">No record filed</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if($apt->medicalRecord)
                                            <div class="small text-dark mb-1">
                                                <strong>Rx:</strong> {{ Str::limit($apt->medicalRecord->prescription, 50) }}
                                            </div>
                                            @if($apt->medicalRecord->notes)
                                            <div class="small text-muted mt-1 p-2 bg-light rounded border border-secondary border-opacity-10 d-flex text-start" style="font-size: 0.8rem;">
                                                <i class="fas fa-history me-2 mt-1"></i>
                                                <span style="white-space: pre-wrap; word-break: break-word;">{{ trim($apt->medicalRecord->notes) }}</span>
                                            </div>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="text-muted opacity-50">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <p>No consultation history found for this patient.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: CONFIRM PREGNANCY CREATION --}}
<div class="modal fade" id="confirmPregnancyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-circle me-2"></i>Confirm Initialization</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="fs-5 mb-2">Are you sure you want to create a <strong>Pregnancy Record</strong> for {{ $patient->first_name }}?</p>
                <p class="text-muted small mb-0">This will permanently unlock the pregnancy tracking dashboard for this patient.</p>
            </div>
            <div class="modal-footer bg-light justify-content-center border-0 pt-0">
                <button type="button" class="btn btn-secondary px-4 fw-bold rounded-pill shadow-sm" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.patients.create_pregnancy', $patient->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4 fw-bold rounded-pill shadow-sm">Yes, Create Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: CONFIRM IMMUNIZATION CREATION --}}
<div class="modal fade" id="confirmImmunizationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-circle me-2"></i>Confirm Initialization</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="fs-5 mb-2">Are you sure you want to create an <strong>Immunization Record</strong> for {{ $patient->first_name }}?</p>
                <p class="text-muted small mb-0">This will permanently unlock the immunization tracking dashboard for this patient.</p>
            </div>
            <div class="modal-footer bg-light justify-content-center border-0 pt-0">
                <button type="button" class="btn btn-secondary px-4 fw-bold rounded-pill shadow-sm" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.patients.create_immunization', $patient->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">Yes, Create Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- INCLUDE THE BIG MODALS FROM PARTIALS --}}
@if($patient->has_pregnancy_record)
    @include('admin.patients.Partials.pregnancy_modal')
@endif

@if($patient->has_immunization_record)
    @include('admin.patients.Partials.immunization_modal')
@endif


{{-- CHANGE PASSWORD MODAL --}}
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning text-dark py-3 rounded-top-4">
                <h5 class="modal-title fw-bold" id="resetPasswordModalLabel"><i class="fas fa-key me-2"></i>Reset Patient Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.patients.change_password', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Current / Old Password <span class="text-danger">*</span></label>
                        <input type="password" name="old_password" class="form-control" required placeholder="Enter the current password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="8" placeholder="Must be at least 8 characters">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="8" placeholder="Re-type new password">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="{{ route('password.request') }}" class="text-success small fw-bold text-decoration-none" target="_blank">
                            <i class="fas fa-envelope me-1"></i> Forgot Password?
                        </a>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light border px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- RESIDENCY IMAGE MODAL --}}
<div class="modal fade" id="residencyImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-image me-2"></i>Proof of Residency</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light">
                <img id="residencyViewerImage" src="" alt="Residency Document" class="img-fluid rounded shadow-sm border" style="max-height: 70vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
    function openResidencyModal(imageUrl) {
        document.getElementById('residencyViewerImage').src = imageUrl;
        var imageModal = new bootstrap.Modal(document.getElementById('residencyImageModal'));
        imageModal.show();
    }
</script>
@endsection