@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day.selected { background: #198754 !important; border-color: #198754 !important; }
    .custom-year-select { margin-left: 5px; padding: 2px; border-radius: 4px; border: 1px solid transparent; background: transparent; font-weight: 500; color: inherit; cursor: pointer; }
    .custom-year-select:hover { background: rgba(0,0,0,0.05); }
</style>

<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('admin.patients.show', $patient->id) }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left me-1"></i> Back to Patient Record
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success text-white py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Patient Information</h4>
                    <p class="mb-0 small opacity-75">Modifying records for {{ $patient->first_name }} {{ $patient->last_name }} (ID: #{{ $patient->usernumber }})</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <h5 class="text-success fw-bold border-bottom pb-2 mb-4">
                                <i class="fas fa-id-card me-2"></i>Demographics
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $patient->first_name) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $patient->middle_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $patient->last_name) }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="text" name="date_of_birth" id="date_of_birth" class="form-control bg-white cursor-pointer" value="{{ old('date_of_birth', optional($patient->date_of_birth)->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Age <span class="text-danger">*</span></label>
                                    <input type="text" name="age" id="age" class="form-control bg-light" value="{{ old('age', $patient->age) }}" required readonly tabindex="-1">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select...</option>
                                        <option value="Male" {{ $patient->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $patient->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Civil Status <span class="text-danger">*</span></label>
                                    <select name="civil_status" class="form-select" required>
                                        <option value="">Select...</option>
                                        <option value="Single" {{ $patient->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ $patient->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed" {{ $patient->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ $patient->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $patient->email) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Contact Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $patient->phone) }}" required placeholder="+639123456789">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Home Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $patient->address) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h5 class="text-success fw-bold border-bottom pb-2 mb-4">
                                <i class="fas fa-notes-medical me-2"></i>Medical History
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">Allergies</label>
                                    <textarea name="allergies" class="form-control" rows="2">{{ old('allergies', $patient->allergies) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Current Medications</label>
                                    <textarea name="current_medication" class="form-control" rows="2">{{ old('current_medication', $patient->current_medication) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Existing Medical Conditions</label>
                                    <textarea name="existing_medical_conditions" class="form-control" rows="2">{{ old('existing_medical_conditions', $patient->existing_medical_conditions) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h5 class="text-success fw-bold border-bottom pb-2 mb-4">
                                <i class="fas fa-hands-helping me-2"></i>Gov't Programs
                            </h5>
                            <div class="row align-items-stretch">
                                
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 bg-light rounded border h-100 shadow-sm">
                                        <div class="form-check form-switch mb-1">
                                            <input class="form-check-input program-toggle" type="checkbox" name="is_philhealth_member" id="philhealth" data-target="philhealth_box" {{ $patient->is_philhealth_member ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold ms-2" for="philhealth">PhilHealth Member</label>
                                        </div>
                                        <div id="philhealth_box" class="mt-3 pt-3 border-top {{ $patient->is_philhealth_member ? '' : 'd-none' }}">
                                            @if($patient->philhealth_id_path)
                                                <div class="text-success small fw-bold mb-2"><i class="fas fa-check-circle me-1"></i> ID on file</div>
                                            @endif
                                            <label class="form-label small text-muted mb-1">Upload New ID (Optional)</label>
                                            <input type="file" name="philhealth_id" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="p-3 bg-light rounded border h-100 shadow-sm">
                                        <div class="form-check form-switch mb-1">
                                            <input class="form-check-input program-toggle" type="checkbox" name="is_senior_citizen_or_pwd" id="seniorPwd" data-target="senior_box" {{ $patient->is_senior_citizen_or_pwd ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold ms-2" for="seniorPwd">Senior Citizen / PWD</label>
                                        </div>
                                        <div id="senior_box" class="mt-3 pt-3 border-top {{ $patient->is_senior_citizen_or_pwd ? '' : 'd-none' }}">
                                            @if($patient->senior_pwd_id_path)
                                                <div class="text-success small fw-bold mb-2"><i class="fas fa-check-circle me-1"></i> ID on file</div>
                                            @endif
                                            <label class="form-label small text-muted mb-1">Upload New ID (Optional)</label>
                                            <input type="file" name="senior_pwd_id" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-light border px-4">Cancel</a>
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.querySelectorAll('.program-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            document.getElementById(this.getAttribute('data-target')).classList.toggle('d-none', !this.checked);
        });
    });

    flatpickr("#date_of_birth", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        allowInput: true,
        onReady: function(selectedDates, dateStr, instance) {
            const yearInputWrapper = instance.currentYearElement.parentNode;
            const yearDropdown = document.createElement("select");
            yearDropdown.className = "custom-year-select flatpickr-monthDropdown-months";
            
            const currentYear = new Date().getFullYear();
            for (let i = currentYear; i >= 1920; i--) {
                const option = document.createElement("option");
                option.value = i;
                option.text = i;
                yearDropdown.appendChild(option);
            }
            
            yearDropdown.value = instance.currentYear;
            yearDropdown.addEventListener("change", function(e) {
                instance.changeYear(Number(e.target.value));
            });
            instance.config.onYearChange.push(function() {
                yearDropdown.value = instance.currentYear;
            });
            yearInputWrapper.parentNode.replaceChild(yearDropdown, yearInputWrapper);
        },
        onChange: function(selectedDates, dateStr, instance) {
            calculateAge(dateStr);
        }
    });

    function calculateAge(dobInput) {
        if (!dobInput) {
            document.getElementById('age').value = "";
            return;
        }

        let dob;
        if (dobInput.includes('-')) {
            const parts = dobInput.split('-');
            const year = parseInt(parts[0], 10);
            const month = parseInt(parts[1], 10) - 1; 
            const day = parseInt(parts[2], 10);
            dob = new Date(year, month, day);
        } else {
            dob = new Date(dobInput);
        }

        if (isNaN(dob.getTime())) return;

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        let years = today.getFullYear() - dob.getFullYear();
        let months = today.getMonth() - dob.getMonth();
        let days = today.getDate() - dob.getDate();

        if (days < 0) months--;
        if (months < 0) { years--; months += 12; }

        let ageText = "";
        
        if (years > 0) {
            ageText += years + (years === 1 ? " year" : " years");
        }
        
        if (months > 0) {
            if (years > 0) ageText += ", ";
            ageText += months + (months === 1 ? " month" : " months");
        }
        
        if (years <= 0 && months <= 0) {
            ageText = "Less than 1 month";
        }

        const ageField = document.getElementById('age');
        if (ageField) {
            ageField.value = ageText;
        }
    }

    window.onload = function() {
        const existingDate = document.getElementById('date_of_birth').value;
        if(existingDate) {
            calculateAge(existingDate);
        }
    };
</script>
@endsection