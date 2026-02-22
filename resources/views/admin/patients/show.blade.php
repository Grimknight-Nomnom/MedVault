@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Alert for Image Deletion & Updates --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                        
                        {{-- Email & Contact Aligned --}}
                        <div class="col-6 mb-2">
                            <label class="text-muted d-block">Email</label>
                            <span class="fw-bold text-success text-break">{{ $patient->email }}</span>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="text-muted d-block">Contact</label>
                            <span class="fw-bold">{{ $patient->phone ?? 'N/A' }}</span>
                        </div>
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
                <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Clinic Consultation History</h5>
                    <span class="badge bg-white text-success">{{ $consultations->count() }} Records</span>
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
                                    
                                    {{-- FIXED: Pre-wrap bug removed using Flexbox layout & span --}}
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
@endsection