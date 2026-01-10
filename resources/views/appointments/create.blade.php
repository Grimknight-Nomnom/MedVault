@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white">Book an Appointment</div>
            <div class="card-body">
                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label>Preferred Date & Time</label>
                        <input type="datetime-local" name="appointment_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Reason for Visit</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="e.g., Fever, Checkup, etc." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection