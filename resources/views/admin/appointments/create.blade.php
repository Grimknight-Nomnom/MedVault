@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .flatpickr-day.selected { background: #0d6efd !important; border-color: #0d6efd !important; }
    
    /* Tweaks to match Bootstrap 5 inputs for Select2 */
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0 0.375rem 0.375rem 0 !important;
        padding: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            {{-- DYNAMIC ROUTE: Back to List --}}
            <a href="{{ route(auth()->user()->role . '.appointments.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-user-plus fa-lg"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Book Walk-In Appointment</h4>
                    <p class="text-muted small">Manually schedule a patient for a checkup</p>
                </div>

                <div class="card-body p-4">
                    
                    @if(session('booking_error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {!! session('booking_error') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- DYNAMIC ROUTE: Form Action --}}
                    <form action="{{ route(auth()->user()->role . '.appointments.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold text-secondary small text-uppercase">Select Patient</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user"></i></span>
                                <select name="user_id" id="user_id" class="form-select border-start-0" required>
                                    <option value="">-- Choose a Patient --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('user_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->last_name }}, {{ $patient->first_name }} (ID: {{ $patient->usernumber ?? $patient->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-text small">Only registered patients appear in this list.</div>
                        </div>

                        <div class="mb-4">
                            <label for="appointment_date" class="form-label fw-semibold text-secondary small text-uppercase">Appointment Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" 
                                       name="appointment_date" 
                                       id="appointment_date" 
                                       class="form-control border-start-0 bg-white cursor-pointer" 
                                       placeholder="Select Date..."
                                       value="{{ old('appointment_date', date('Y-m-d')) }}"
                                       required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="form-label fw-semibold text-secondary small text-uppercase">Reason for Visit</label>
                            <textarea name="reason" 
                                      id="reason" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Example: High fever, routine checkup, stomach pain..." 
                                      required>{{ old('reason') }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold">
                                <i class="fas fa-check-circle me-2"></i> Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 on the Patient dropdown
        $('#user_id').select2({
            placeholder: "-- Choose a Patient --",
            width: '100%',
            allowClear: true
        });
    });

    flatpickr("#appointment_date", {
        dateFormat: "Y-m-d",
        minDate: "today", 
        allowInput: true
    });
</script>
@endsection