@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark"><i class="fas fa-folder-open me-2 text-primary"></i>My Medical History</h2>
        <p class="text-muted">A complete timeline of your diagnoses and dispensed medicines.</p>
    </div>

    @if($records->isEmpty())
        <div class="alert alert-light border shadow-sm text-center py-5">
            <i class="fas fa-clipboard-list fa-3x text-muted mb-3 opacity-50"></i>
            <h5 class="text-secondary fw-bold">No medical records found.</h5>
        </div>
    @else
        @php
            // Calculate the total number of actual doctor diagnoses (ignoring medicine releases)
            $diagnosisCounter = $records->reject(function($record) {
                return $record->diagnosis === 'MEDICINE_DISPENSED' || str_starts_with($record->diagnosis, 'Medicine Dispensed:');
            })->count();
        @endphp

        <div class="row mt-3">
            @foreach($records as $record)
            
            {{-- CHECK IF THIS RECORD IS A MEDICINE RELEASE (NEW FORMAT) --}}
            @if($record->diagnosis === 'MEDICINE_DISPENSED')
                
                @php
                    // Parse the piped string we created in the controller
                    $parts = explode('|', $record->notes);
                    $qty = $parts[0] ?? 'N/A';
                    $desc = $parts[1] ?? 'No description provided.';
                    $releasedBy = $parts[2] ?? 'Unknown Staff';
                @endphp

                {{-- BLUE CARD: Dispensed Medicine --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-primary border-opacity-50">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold"><i class="fas fa-calendar-day me-2"></i>{{ $record->created_at->format('F d, Y') }}</span>
                            <span class="badge bg-light text-primary rounded-pill">Medicine Released</span>
                        </div>
                        <div class="card-body bg-primary bg-opacity-10">
                            <h5 class="card-title text-primary fw-bold mb-3">
                                <i class="fas fa-pills me-2"></i>{{ $record->prescription }}
                            </h5>
                            <div class="bg-white p-3 rounded border shadow-sm">
                                <p class="mb-2 text-dark"><strong>Quantity:</strong> {{ $qty }} pieces</p>
                                <p class="mb-0 text-dark"><strong>Description:</strong> <br> <span class="text-muted">{{ $desc }}</span></p>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 text-muted small pb-3">
                            <i class="fas fa-user-nurse me-1"></i> Released by: <span class="fw-bold">{{ $releasedBy }}</span>
                        </div>
                    </div>
                </div>

            {{-- BACKWARD COMPATIBILITY: OLD FORMAT (Records created before the recent fix) --}}
            @elseif(str_starts_with($record->diagnosis, 'Medicine Dispensed:'))
                
                @php
                    $cleanPrescription = $record->prescription;
                    // Automatically remove the "Medicine: [name]" line from the old database entries
                    $cleanPrescription = preg_replace('/Medicine:\s*.*(\r?\n|$)/', '', $cleanPrescription);
                    // Automatically replace "Usage Instructions:" with "Description:"
                    $cleanPrescription = str_replace('Usage Instructions:', 'Description:', $cleanPrescription);
                @endphp

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-primary border-opacity-50">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold"><i class="fas fa-calendar-day me-2"></i>{{ $record->created_at->format('F d, Y') }}</span>
                            <span class="badge bg-light text-primary rounded-pill">Medicine Released</span>
                        </div>
                        <div class="card-body bg-primary bg-opacity-10">
                            <h5 class="card-title text-primary fw-bold mb-3"><i class="fas fa-pills me-2"></i>{{ str_replace('Medicine Dispensed: ', '', $record->diagnosis) }}</h5>
                            <div class="bg-white p-3 rounded border shadow-sm">
                                <p class="mb-0 text-dark">{!! nl2br(e($cleanPrescription)) !!}</p>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 text-muted small pb-3">
                            <i class="fas fa-info-circle me-1"></i> {{ str_replace('Released by: ', 'Released by: ', $record->notes) }}
                        </div>
                    </div>
                </div>

            {{-- BLACK CARD: Standard Doctor Diagnosis --}}
            @else
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-dark">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                            <span class="fw-bold"><i class="fas fa-calendar-day me-2"></i>{{ $record->created_at->format('F d, Y') }}</span>
                            <span class="badge bg-secondary rounded-pill">Diagnosis #{{ $diagnosisCounter-- }}</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-dark fw-bold mb-3">
                                <i class="fas fa-stethoscope me-2"></i>Diagnosis: {{ $record->diagnosis }}
                            </h5>
                            <div class="p-3 bg-light rounded border border-secondary border-opacity-25 mb-3">
                                <p class="mb-0"><strong>Prescription:</strong><br>
                                {!! nl2br(e($record->prescription)) !!}</p>
                            </div>

                            @if($record->notes)
                            <div class="text-muted small">
                                <strong>Doctor's Notes:</strong> <br>
                                {{ $record->notes }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            @endforeach
        </div>
    @endif
</div>
@endsection