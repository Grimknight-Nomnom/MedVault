@extends('layouts.app')

@section('content')
<h2>My Medical History</h2>

@if($records->isEmpty())
    <div class="alert alert-info">No medical records found.</div>
@else
    <div class="row mt-3">
        @foreach($records as $record)
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <span>Date: {{ $record->created_at->format('M d, Y') }}</span>
                    <small>Visit ID: #{{ $record->appointment_id }}</small>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary">Diagnosis: {{ $record->diagnosis }}</h5>
                    <hr>
                    <p><strong>Prescription:</strong><br>
                    {!! nl2br(e($record->prescription)) !!}</p>

                    @if($record->notes)
                    <p class="text-muted"><small>Notes: {{ $record->notes }}</small></p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection