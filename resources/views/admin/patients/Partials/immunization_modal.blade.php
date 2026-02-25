<div class="modal fade" id="viewImmunizationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-book-medical me-2"></i>Baby & Immunization Record - {{ $patient->first_name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                
                @php
                    $ir = $patient->immunizationRecord;
                    $logs = \App\Models\ImmunizationLog::where('user_id', $patient->id)->orderBy('date', 'desc')->get();
                @endphp

                <form action="{{ route('admin.patients.update_immunization', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- 1. BIRTH DETAILS --}}
                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-2"><i class="fas fa-baby me-2"></i>Birth Details</h6>
                    <div class="bg-white p-3 rounded shadow-sm border mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Time of Birth</label>
                                <input type="time" name="birth_time" class="form-control form-control-sm" value="{{ old('birth_time', $ir->birth_time ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Birth Weight</label>
                                <input type="text" name="birth_weight" class="form-control form-control-sm" value="{{ old('birth_weight', $ir->birth_weight ?? '') }}" placeholder="e.g. 3.2 kg">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Birth Length</label>
                                <input type="text" name="birth_length" class="form-control form-control-sm" value="{{ old('birth_length', $ir->birth_length ?? '') }}" placeholder="e.g. 50 cm">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Hospital Born</label>
                                <input type="text" name="birth_hospital" class="form-control form-control-sm" value="{{ old('birth_hospital', $ir->birth_hospital ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Eye Color</label>
                                <input type="text" name="eye_color" class="form-control form-control-sm" value="{{ old('eye_color', $ir->eye_color ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Hair Color</label>
                                <input type="text" name="hair_color" class="form-control form-control-sm" value="{{ old('hair_color', $ir->hair_color ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Mommy's Name</label>
                                <input type="text" name="mother_name" class="form-control form-control-sm" value="{{ old('mother_name', $ir->mother_name ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Daddy's Name</label>
                                <input type="text" name="father_name" class="form-control form-control-sm" value="{{ old('father_name', $ir->father_name ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- 2. LOG A NEW VISIT --}}
                    <h6 class="text-success fw-bold border-bottom pb-2 mb-3"><i class="fas fa-plus-circle me-2"></i>Log a New Visit</h6>
                    <div class="bg-success bg-opacity-10 p-3 rounded shadow-sm border border-success mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="small fw-bold text-dark">Date</label>
                                <input type="date" name="log_date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-dark">Age</label>
                                <input type="text" name="log_age" class="form-control form-control-sm" placeholder="e.g. 2 months">
                            </div>
                            <div class="col-md-2">
                                <label class="small fw-bold text-dark">Temp</label>
                                <input type="text" name="log_temp" class="form-control form-control-sm" placeholder="36.5 °C">
                            </div>
                            <div class="col-md-2">
                                <label class="small fw-bold text-dark">Weight</label>
                                <input type="text" name="log_weight" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label class="small fw-bold text-dark">Length</label>
                                <input type="text" name="log_length" class="form-control form-control-sm">
                            </div>
                            
                            <div class="col-md-2">
                                <label class="small fw-bold text-dark">HC (Head)</label>
                                <input type="text" name="log_hc" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label class="small fw-bold text-dark">CC (Chest)</label>
                                <input type="text" name="log_cc" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label class="small fw-bold text-dark">AC (Abdominal)</label>
                                <input type="text" name="log_ac" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-dark">Type of Bakuna</label>
                                <input type="text" name="log_bakuna" class="form-control form-control-sm" placeholder="e.g. BCG, Penta">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-danger">Next Visit Schedule</label>
                                <input type="date" name="log_next_visit" class="form-control form-control-sm border-danger">
                            </div>

                            <div class="col-md-12">
                                <label class="small fw-bold text-dark">Doctor's Instructions</label>
                                <textarea name="log_instructions" class="form-control" rows="2" placeholder="Instructions..."></textarea>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-save me-2"></i>Save Birth Details & New Visit</button>
                        </div>
                    </div>
                </form>

                {{-- 3. HISTORY TABLE --}}
                <h6 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="fas fa-history me-2"></i>Previous Visit History</h6>
                <div class="bg-white p-3 rounded shadow-sm border">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle small mb-0">
                            <thead class="bg-light">
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
                                            <span class="badge bg-primary mb-1 fs-6">{{ $log->type_of_bakuna }}</span><br>
                                        @endif
                                        <div class="text-muted fst-italic mb-1" style="white-space: pre-wrap;">{{ $log->doctor_instructions ?: 'No instructions.' }}</div>
                                        @if($log->next_visit)
                                            <small class="text-danger fw-bold"><i class="fas fa-calendar-alt me-1"></i>Next Visit: {{ \Carbon\Carbon::parse($log->next_visit)->format('M d, Y') }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i><br>
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
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close Menu</button>
            </div>
        </div>
    </div>
</div>