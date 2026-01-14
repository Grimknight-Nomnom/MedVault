@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            {{-- Back Button --}}
            <a href="{{ route('admin.appointments.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block">
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
                    {{-- Error Alerts --}}
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

                    <form action="{{ route('admin.appointments.store') }}" method="POST">
                        @csrf

                        {{-- 1. Select Patient --}}
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold text-secondary small text-uppercase">Select Patient</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user"></i></span>
                                <select name="user_id" id="user_id" class="form-select border-start-0" required>
                                    <option value="">-- Choose a Patient --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('user_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->last_name }}, {{ $patient->first_name }} (ID: {{ $patient->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-text small">Only registered patients appear in this list.</div>
                        </div>

                        {{-- 2. Select Date --}}
                        <div class="mb-4">
                            <label for="appointment_date" class="form-label fw-semibold text-secondary small text-uppercase">Appointment Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" 
                                       name="appointment_date" 
                                       id="appointment_date" 
                                       class="form-control border-start-0" 
                                       value="{{ old('appointment_date', date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}" 
                                       required>
                            </div>
                        </div>

                        {{-- 3. Reason --}}
                        <div class="mb-4">
                            <label for="reason" class="form-label fw-semibold text-secondary small text-uppercase">Reason for Visit</label>
                            <textarea name="reason" 
                                      id="reason" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Example: High fever, routine checkup, stomach pain..." 
                                      required>{{ old('reason') }}</textarea>
                        </div>

                        {{-- Submit Button --}}
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
@endsectionjanu