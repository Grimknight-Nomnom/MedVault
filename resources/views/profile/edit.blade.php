@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger border-start border-danger border-4 shadow-sm mb-4">
        <i class="fas fa-ban me-2"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success text-white py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Personal Records</h4>
                    <p class="mb-0 small opacity-75">Please complete all required fields (*) to access clinic services.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <h5 class="text-success fw-bold border-bottom pb-2 mb-4">
                                <i class="fas fa-id-card me-2"></i>Demographics
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $user->middle_name) }}" placeholder="Optional">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Age <span class="text-danger">*</span></label>
                                    <input type="number" name="age" class="form-control" value="{{ old('age', $user->age) }}" required min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select...</option>
                                        <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Civil Status <span class="text-danger">*</span></label>
                                    <select name="civil_status" class="form-select" required>
                                        <option value="">Select...</option>
                                        <option value="Single" {{ $user->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ $user->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed" {{ $user->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ $user->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
    <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>
</div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Contact Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required placeholder="09123456789">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Home Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" required placeholder="House No., Street, Barangay, City">
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h5 class="text-success fw-bold border-bottom pb-2 mb-4">
                                <i class="fas fa-notes-medical me-2"></i>Medical History
                            </h5>
                            <div class="alert alert-light border border-secondary border-opacity-10 small mb-3">
                                <i class="fas fa-info-circle me-1 text-info"></i> Please list 'None' or 'N/A' if not applicable.
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">Allergies</label>
                                    <textarea name="allergies" class="form-control" rows="2" placeholder="e.g. Penicillin, Peanuts...">{{ old('allergies', $user->allergies) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Current Medications</label>
                                    <textarea name="current_medication" class="form-control" rows="2" placeholder="List medications you are currently taking...">{{ old('current_medication', $user->current_medication) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Existing Medical Conditions</label>
                                    <textarea name="existing_medical_conditions" class="form-control" rows="2" placeholder="e.g., Hypertension, Diabetes...">{{ old('existing_medical_conditions', $user->existing_medical_conditions) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h5 class="text-success fw-bold border-bottom pb-2 mb-4">
                                <i class="fas fa-hands-helping me-2"></i>Gov't Programs
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-check form-switch p-3 bg-light rounded border">
                                        <input class="form-check-input" type="checkbox" name="is_philhealth_member" id="philhealth" {{ $user->is_philhealth_member ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold ms-2" for="philhealth">
                                            I am a PhilHealth Member
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check form-switch p-3 bg-light rounded border">
                                        <input class="form-check-input" type="checkbox" name="is_senior_citizen_or_pwd" id="seniorPwd" {{ $user->is_senior_citizen_or_pwd ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold ms-2" for="seniorPwd">
                                            I am a Senior Citizen or PWD
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-light border px-4">Cancel</a>
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Save & Update Records
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection