@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-primary">
            <div class="card-header bg-primary text-white">
                Add Medical Record for Patient: <strong>{{ $appointment->user->name }}</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.records.store', $appointment->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="fw-bold">Diagnosis</label>
                        <input type="text" name="diagnosis" class="form-control" placeholder="e.g. Acute Bronchitis" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Prescription</label>
                        <textarea name="prescription" class="form-control" rows="3" placeholder="e.g. Amoxicillin 500mg - 3x a day for 7 days"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Doctor's Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Record & Complete Appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection