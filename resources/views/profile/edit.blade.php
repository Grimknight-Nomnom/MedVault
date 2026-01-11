@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Personal Record</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="text-primary border-bottom pb-2 mb-3">Demographics</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $user->middle_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Age</label>
                                <input type="number" name="age" class="form-control" value="{{ old('age', $user->age) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Civil Status</label>
                                <select name="civil_status" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="Single" {{ $user->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ $user->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ $user->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                            </div>
                        </div>

                        <h5 class="text-primary border-bottom pb-2 mb-3">Medical History (Self-Reported)</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">Allergies</label>
                                <textarea name="allergies" class="form-control" rows="2" placeholder="List any allergies...">{{ old('allergies', $user->allergies) }}</textarea>
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

                        <h5 class="text-primary border-bottom pb-2 mb-3">Health Programs</h5>
                        <div class="mb-4">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_philhealth_member" id="philhealth" {{ $user->is_philhealth_member ? 'checked' : '' }}>
                                <label class="form-check-label" for="philhealth">I am a PhilHealth Member</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_senior_citizen_or_pwd" id="seniorPwd" {{ $user->is_senior_citizen_or_pwd ? 'checked' : '' }}>
                                <label class="form-check-label" for="seniorPwd">I am a Senior Citizen or PWD</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection