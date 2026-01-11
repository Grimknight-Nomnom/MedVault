@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-3">
                <a href="{{ route('admin.appointments.index') }}" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Schedule
                </a>
            </div>

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-medical me-2"></i> 
                        Medical Record: {{ $appointment->user->first_name }} {{ $appointment->user->last_name }}
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <div class="bg-light p-3 rounded mb-4 border-start border-4 border-info">
                        <div class="row">
                            <div class="col-sm-6">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Appointment Date</small>
                                <span class="fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Reason for Visit</small>
                                <span class="fw-bold">{{ $appointment->reason }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.records.store', $appointment->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Diagnosis</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-stethoscope text-primary"></i></span>
                                <input type="text" name="diagnosis" class="form-control" 
                                       placeholder="e.g. Acute Bronchitis" required>
                            </div>
                            <div class="form-text small">Enter the primary medical finding.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Prescription / Medication</label>
                            <textarea name="prescription" class="form-control" rows="3" 
                                      placeholder="e.g. Amoxicillin 500mg - 3x a day for 7 days"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Doctor's Clinical Notes</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Internal observation notes, follow-up requirements, etc."></textarea>
                        </div>

                        
                        <div class="mt-5 pt-3 border-top">
                            <div class="alert alert-info small border-0 shadow-sm mb-4">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle me-3 mt-1 fs-5"></i>
                                    <div>
                                        Saving this record will automatically mark the appointment as 
                                        <strong>Completed</strong> and remove it from the active schedule. 
                                        The patient will be able to view this history in their dashboard.
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Save Record & Complete Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection