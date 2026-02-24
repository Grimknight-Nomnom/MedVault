@extends('layouts.app')

@section('content')
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day.selected { background: #198754 !important; border-color: #198754 !important; }
    .custom-year-select { margin-left: 5px; padding: 2px; border-radius: 4px; border: 1px solid transparent; background: transparent; font-weight: 500; color: inherit; cursor: pointer; }
    .custom-year-select:hover { background: rgba(0,0,0,0.05); }
</style>

<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    {{-- DELETED THE DUPLICATE SUCCESS MESSAGE BLOCK HERE --}}

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-success text-white py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Personal Records</h4>
                    <p class="mb-0 small opacity-75">Please complete all required fields (*) to access clinic services.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
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
                                    <input type="text" name="date_of_birth" id="date_of_birth" class="form-control bg-white cursor-pointer" placeholder="Select Date..." value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Age <span class="text-danger">*</span></label>
                                    <input type="text" name="age" id="age" class="form-control bg-light" value="{{ old('age', $user->age) }}" required readonly tabindex="-1">
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
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Home Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" required placeholder="House No., Street, Barangay, City">
                                </div>

                                {{-- PROOF OF RESIDENCY --}}
                                <div class="col-md-12 mt-4">
                                    <label class="form-label fw-bold">Proof of Residency / Indigency <span class="text-danger">*</span></label>
                                    <div class="p-4 bg-light rounded border shadow-sm">
                                        @if($user->patient_photo_path)
                                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <i class="fas fa-file-image fa-3x text-info"></i>
                                                    <div>
                                                        <div class="fw-bold text-success mb-1">Document Uploaded Successfully</div>
                                                        <small class="text-muted">Your proof of residency is currently on file.</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="button" onclick="openImageModal('{{ asset('storage/' . $user->patient_photo_path) }}', 'Proof of Residency / Indigency')" class="btn btn-outline-primary fw-bold px-4 rounded-pill">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </button>
                                                    <button type="submit" form="delete-residency-form" class="btn btn-outline-danger fw-bold px-4 rounded-pill" onclick="return confirm('Are you sure you want to delete this document? You will be required to upload a new one to verify your address.');">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="file" name="patient_photo" class="d-none">
                                        @else
                                            <div class="alert alert-warning border-warning border-opacity-50 small mb-3">
                                                <i class="fas fa-exclamation-triangle me-1"></i> You must upload a valid Proof of Residency or Barangay Indigency to maintain clinic access.
                                            </div>
                                            <input type="file" name="patient_photo" class="form-control p-2" accept="image/jpeg, image/png, image/jpg" required>
                                            <div class="form-text small mt-2 text-muted">Please upload a clear copy of your document. (PNG/JPG, Max 5MB).</div>
                                        @endif
                                    </div>
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
                            <div class="row align-items-stretch">
                                
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 bg-light rounded border h-100 shadow-sm transition-all">
                                        <div class="form-check form-switch mb-1">
                                            <input class="form-check-input program-toggle" type="checkbox" name="is_philhealth_member" id="philhealth" data-target="philhealth_box" {{ $user->is_philhealth_member ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold ms-2" for="philhealth">
                                                I am a PhilHealth Member
                                            </label>
                                        </div>
                                        
                                        <div id="philhealth_box" class="mt-3 pt-3 border-top {{ $user->is_philhealth_member ? '' : 'd-none' }}">
                                            
                                            @if($user->philhealth_id_path)
                                                <div class="text-center p-3 border rounded bg-white mt-2">
                                                    <i class="fas fa-id-card fa-2x text-success mb-2"></i>
                                                    <div class="text-success small fw-bold mb-3">ID Successfully Uploaded</div>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" onclick="openImageModal('{{ asset('storage/' . $user->philhealth_id_path) }}', 'PhilHealth ID')" class="btn btn-sm btn-outline-primary fw-bold px-3">
                                                            <i class="fas fa-eye me-1"></i> View Image
                                                        </button>
                                                        <button type="submit" form="delete-philhealth-form" class="btn btn-sm btn-outline-danger fw-bold px-3" onclick="return confirm('Delete this ID? You will need to upload a new one to stay verified.');">
                                                            <i class="fas fa-trash me-1"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="file" name="philhealth_id" class="d-none" data-has-file="true">
                                            @else
                                                <label class="form-label small fw-bold text-muted mb-1">Upload PhilHealth ID Image <span class="text-danger">*</span></label>
                                                <input type="file" name="philhealth_id" class="form-control form-control-sm mb-2" accept="image/*" data-has-file="false" {{ $user->is_philhealth_member ? 'required' : '' }}>
                                                <div class="form-text" style="font-size: 0.75rem;">Required to save changes. Max: 5MB</div>
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="p-3 bg-light rounded border h-100 shadow-sm transition-all">
                                        <div class="form-check form-switch mb-1">
                                            <input class="form-check-input program-toggle" type="checkbox" name="is_senior_citizen_or_pwd" id="seniorPwd" data-target="senior_box" {{ $user->is_senior_citizen_or_pwd ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold ms-2" for="seniorPwd">
                                                I am a Senior Citizen or PWD
                                            </label>
                                        </div>
                                        
                                        <div id="senior_box" class="mt-3 pt-3 border-top {{ $user->is_senior_citizen_or_pwd ? '' : 'd-none' }}">
                                            
                                            @if($user->senior_pwd_id_path)
                                                <div class="text-center p-3 border rounded bg-white mt-2">
                                                    <i class="fas fa-id-card fa-2x text-success mb-2"></i>
                                                    <div class="text-success small fw-bold mb-3">ID Successfully Uploaded</div>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" onclick="openImageModal('{{ asset('storage/' . $user->senior_pwd_id_path) }}', 'Senior / PWD ID')" class="btn btn-sm btn-outline-primary fw-bold px-3">
                                                            <i class="fas fa-eye me-1"></i> View Image
                                                        </button>
                                                        <button type="submit" form="delete-senior-form" class="btn btn-sm btn-outline-danger fw-bold px-3" onclick="return confirm('Delete this ID? You will need to upload a new one to stay verified.');">
                                                            <i class="fas fa-trash me-1"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="file" name="senior_pwd_id" class="d-none" data-has-file="true">
                                            @else
                                                <label class="form-label small fw-bold text-muted mb-1">Upload Senior/PWD ID Image <span class="text-danger">*</span></label>
                                                <input type="file" name="senior_pwd_id" class="form-control form-control-sm mb-2" accept="image/*" data-has-file="false" {{ $user->is_senior_citizen_or_pwd ? 'required' : '' }}>
                                                <div class="form-text" style="font-size: 0.75rem;">Required to save changes. Max: 5MB</div>
                                            @endif
                                            
                                        </div>
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

                    {{-- Hidden Forms for Deleting IDs --}}
                    <form id="delete-philhealth-form" action="{{ route('profile.delete_id', 'philhealth') }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                    <form id="delete-senior-form" action="{{ route('profile.delete_id', 'senior_pwd') }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                    <form id="delete-residency-form" action="{{ route('profile.delete_id', 'residency') }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- IMAGE VIEWER MODAL --}}
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-image me-2"></i><span id="imageModalTitle">Document Viewer</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center bg-light">
                <img id="viewerImage" src="" alt="Document" class="img-fluid rounded shadow-sm border" style="max-height: 70vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // --- Image Viewer Modal Logic ---
    function openImageModal(imageUrl, title) {
        document.getElementById('viewerImage').src = imageUrl;
        document.getElementById('imageModalTitle').innerText = title;
        var imageModal = new bootstrap.Modal(document.getElementById('imageViewerModal'));
        imageModal.show();
    }

    // --- Program Checkbox Logic ---
    document.querySelectorAll('.program-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const targetBox = document.getElementById(this.getAttribute('data-target'));
            const fileInput = targetBox.querySelector('input[type="file"]');
            const hasFile = fileInput.getAttribute('data-has-file') === 'true';

            if(this.checked) {
                targetBox.classList.remove('d-none');
                if(!hasFile) fileInput.setAttribute('required', 'required');
            } else {
                targetBox.classList.add('d-none');
                if(fileInput) fileInput.removeAttribute('required'); 
            }
        });
    });

    // --- Date Picker & Age Logic ---
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