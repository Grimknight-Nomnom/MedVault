@if($user->has_immunization_record && $user->immunizationRecord)
@php 
    $ir = $user->immunizationRecord; 
    $logs = \App\Models\ImmunizationLog::where('user_id', $user->id)->orderBy('date', 'desc')->get();
@endphp
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-book-medical me-2"></i>Baby & Immunization Record - {{ $user->first_name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                
                {{-- Birth Details --}}
                <h6 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="fas fa-baby me-2"></i>Birth Details</h6>
                <div class="bg-white p-3 rounded shadow-sm border mb-4">
                    <div class="row g-3">
                        <div class="col-md-3"><small class="text-muted d-block text-uppercase">Time of Birth</small><span class="fw-bold">{{ $ir->birth_time ? \Carbon\Carbon::parse($ir->birth_time)->format('h:i A') : 'N/A' }}</span></div>
                        <div class="col-md-3"><small class="text-muted d-block text-uppercase">Birth Weight</small><span class="fw-bold">{{ $ir->birth_weight ?: 'N/A' }}</span></div>
                        <div class="col-md-3"><small class="text-muted d-block text-uppercase">Birth Length</small><span class="fw-bold">{{ $ir->birth_length ?: 'N/A' }}</span></div>
                        <div class="col-md-3"><small class="text-muted d-block text-uppercase">Hospital</small><span class="fw-bold">{{ $ir->birth_hospital ?: 'N/A' }}</span></div>
                        <div class="col-md-3"><small class="text-muted d-block text-uppercase">Eye Color</small><span class="fw-bold">{{ $ir->eye_color ?: 'N/A' }}</span></div>
                        <div class="col-md-3"><small class="text-muted d-block text-uppercase">Hair Color</small><span class="fw-bold">{{ $ir->hair_color ?: 'N/A' }}</span></div>
                        <div class="col-md-3"><small class="text-muted d-block text-uppercase">Mommy's Name</small><span class="fw-bold">{{ $ir->mother_name ?: 'N/A' }}</span></div>
                        <div class="col-md-3"><small class="text-muted d-block text-uppercase">Daddy's Name</small><span class="fw-bold">{{ $ir->father_name ?: 'N/A' }}</span></div>
                    </div>
                </div>

                {{-- Visit History --}}
                <h6 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="fas fa-history me-2"></i>Visit History & Immunization Logs</h6>
                <div class="bg-white p-3 rounded shadow-sm border mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle small mb-0">
                            <thead class="bg-primary bg-opacity-10 text-primary">
                                <tr>
                                    <th style="width: 15%;">Date</th>
                                    <th style="width: 20%;">Vitals & Age</th>
                                    <th style="width: 20%;">Measurements</th>
                                    <th style="width: 45%;">Vaccine & Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td class="fw-bold text-dark">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                                    <td>
                                        <strong>Age:</strong> {{ $log->age ?: '-' }}<br>
                                        <strong>Temp:</strong> {{ $log->temp ?: '-' }}<br>
                                        <strong>Wt/Len:</strong> {{ $log->weight ?: '-' }} / {{ $log->length ?: '-' }}
                                    </td>
                                    <td>
                                        <strong>HC:</strong> {{ $log->hc ?: '-' }}<br>
                                        <strong>CC:</strong> {{ $log->cc ?: '-' }}<br>
                                        <strong>AC:</strong> {{ $log->ac ?: '-' }}
                                    </td>
                                    <td>
                                        @if($log->type_of_bakuna)
                                            <span class="badge bg-success mb-1 fs-6"><i class="fas fa-syringe me-1"></i>{{ $log->type_of_bakuna }}</span><br>
                                        @endif
                                        <div class="text-dark bg-light p-2 rounded border border-secondary border-opacity-10 mb-1" style="white-space: pre-wrap;">{{ $log->doctor_instructions ?: 'No specific instructions.' }}</div>
                                        @if($log->next_visit)
                                            <small class="text-danger fw-bold"><i class="fas fa-calendar-alt me-1"></i>Next Visit: {{ \Carbon\Carbon::parse($log->next_visit)->format('F d, Y') }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i><br>
                                        No previous visits recorded yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-0 bg-white">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif