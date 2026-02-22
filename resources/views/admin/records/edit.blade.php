@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('admin.patients.show', $record->user_id) }}" class="text-decoration-none text-muted small fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Back to Patient Record
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 max-w-2xl mx-auto" style="max-width: 800px;">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit Medical Record</h5>
            <p class="mb-0 small opacity-75">Update diagnosis for {{ $record->appointment->user->first_name }} {{ $record->appointment->user->last_name }}</p>
        </div>
        
        <div class="card-body p-4 p-md-5">
            
            {{-- Displaying Original Context --}}
            <div class="alert alert-primary bg-primary bg-opacity-10 border-0 border-start border-primary border-4 shadow-sm mb-4">
                <div class="mb-2"><i class="fas fa-calendar-alt me-2 text-primary"></i><strong>Original Consultation Date:</strong> {{ \Carbon\Carbon::parse($record->appointment->appointment_date)->format('F d, Y') }}</div>
                <div><i class="fas fa-history me-2 text-primary"></i><strong>Current Notes Log:</strong></div>
                <div class="small text-dark mt-1" style="white-space: pre-wrap;">{{ $record->notes }}</div>
            </div>

            <form action="{{ route('admin.records.update', $record->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-bold">Record Edited By <span class="text-danger">*</span></label>
                    <select name="edited_by" class="form-select border-primary shadow-none" required>
                        <option value="">Select Doctor or Nurse</option>
                        @foreach($staffList as $staff)
                            <option value="{{ $staff->name }}">{{ $staff->name }} ({{ $staff->role }})</option>
                        @endforeach
                    </select>
                    <div class="form-text small">Your name and today's date will be permanently logged in the notes.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Update Diagnosis / Findings <span class="text-danger">*</span></label>
                    <textarea name="diagnosis" class="form-control shadow-none" rows="3" required>{{ old('diagnosis', $record->diagnosis) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Update Prescription</label>
                    <textarea name="prescription" class="form-control shadow-none" rows="3" placeholder="List of medications, dosage, etc.">{{ old('prescription', $record->prescription) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Add Additional Notes (Optional)</label>
                    <textarea name="added_notes" class="form-control shadow-none" rows="2" placeholder="Explain what was updated or add new observations..."></textarea>
                    <div class="form-text text-success small fw-bold"><i class="fas fa-info-circle me-1"></i>This will safely append to the bottom of the existing notes.</div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-5 border-top pt-4">
                    <a href="{{ route('admin.patients.show', $record->user_id) }}" class="btn btn-light border px-4 fw-bold">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection