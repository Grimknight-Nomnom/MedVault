<div class="modal fade" id="viewImmunizationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            {{-- HEADER --}}
            <div class="modal-header bg-primary text-white py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                        <i class="fas fa-baby fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">EPI / Target Client List for Infant</h5>
                        <p class="mb-0 small text-white-50">Immunization Record & Baby Book Details</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-light p-0">
                <form action="{{ route('admin.patients.update_immunization', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- COMPLETION STATUS BANNER --}}
                    <div class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                        <div>
                            @if($patient->immunizationRecord && $patient->immunizationRecord->is_completed)
                                <span class="badge bg-success py-2 px-3 fs-6 shadow-sm"><i class="fas fa-check-circle me-1"></i> Immunization Program Completed</span>
                                <p class="text-muted small mb-0 mt-1">This patient has completed all required vaccines.</p>
                            @else
                                <span class="badge bg-warning text-dark py-2 px-3 fs-6 shadow-sm"><i class="fas fa-spinner fa-spin me-1"></i> Immunization In Progress</span>
                                <p class="text-muted small mb-0 mt-1">Record visits below until the program is finished.</p>
                            @endif
                        </div>

                        {{-- Mark as Complete Button --}}
                        @if($patient->immunizationRecord && !$patient->immunizationRecord->is_completed)
                            <button type="submit" formaction="{{ route('admin.patients.complete_immunization', $patient->id) }}" class="btn btn-success fw-bold shadow-sm" onclick="return confirm('Are you sure you want to mark this immunization program as fully completed?');">
                                <i class="fas fa-check-double me-1"></i> Mark as Complete
                            </button>
                        @endif
                    </div>

                    <div class="row g-0">
                        {{-- LEFT COLUMN: BABY BOOK DETAILS --}}
                        <div class="col-lg-4 border-end bg-white">
                            <div class="p-4 h-100">
                                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Baby / Birth Details
                                </h6>
                                
                                {{-- FIELDSET TO LOCK THE LEFT COLUMN IF COMPLETED --}}
                                <fieldset {{ ($patient->immunizationRecord && $patient->immunizationRecord->is_completed) ? 'disabled' : '' }}>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted mb-1">Time of Birth</label>
                                        <input type="time" name="birth_time" class="form-control form-control-sm" value="{{ $patient->immunizationRecord->birth_time ?? '' }}">
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted mb-1">Birth Weight</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="birth_weight" class="form-control" value="{{ $patient->immunizationRecord->birth_weight ?? '' }}" placeholder="e.g. 3.2">
                                                <span class="input-group-text bg-light">kg</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted mb-1">Birth Length</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="birth_length" class="form-control" value="{{ $patient->immunizationRecord->birth_length ?? '' }}" placeholder="e.g. 50">
                                                <span class="input-group-text bg-light">cm</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted mb-1">Eye Color</label>
                                            <input type="text" name="eye_color" class="form-control form-control-sm" value="{{ $patient->immunizationRecord->eye_color ?? '' }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted mb-1">Hair Color</label>
                                            <input type="text" name="hair_color" class="form-control form-control-sm" value="{{ $patient->immunizationRecord->hair_color ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted mb-1">Hospital / Clinic of Birth</label>
                                        <textarea name="birth_hospital" class="form-control form-control-sm" rows="2">{{ $patient->immunizationRecord->birth_hospital ?? '' }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted mb-1">Mother's Name</label>
                                        <input type="text" name="mother_name" class="form-control form-control-sm" value="{{ $patient->immunizationRecord->mother_name ?? '' }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted mb-1">Father's Name</label>
                                        <input type="text" name="father_name" class="form-control form-control-sm" value="{{ $patient->immunizationRecord->father_name ?? '' }}">
                                    </div>
                                </fieldset>

                                {{-- DYNAMIC SAVE BUTTON --}}
                                @if(!$patient->immunizationRecord || !$patient->immunizationRecord->is_completed)
                                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold mt-2 shadow-sm">
                                        <i class="fas fa-save me-1"></i> Update Birth Details
                                    </button>
                                @else
                                    <div class="alert alert-success small py-2 mt-3 mb-0 text-center border-0 shadow-sm">
                                        <i class="fas fa-lock me-1"></i>Details Locked
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: IMMUNIZATION LOGS --}}
                        <div class="col-lg-8 bg-light">
                            <div class="p-4">
                                
                                {{-- NEW VISIT ENTRY FORM (Hides when complete) --}}
                                @if(!$patient->immunizationRecord || !$patient->immunizationRecord->is_completed)
                                <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-4 border-success">
                                    <div class="card-header bg-white border-0 py-3">
                                        <h6 class="fw-bold text-success mb-0"><i class="fas fa-plus-circle me-2"></i>Log New Visit & Vitals</h6>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-4">
    <label class="form-label small fw-bold text-muted mb-1">Date of Visit <span class="text-danger">*</span></label>
    <input type="date" name="log_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
</div>
                                            <div class="col-md-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Age (Mo/Wk)</label>
                                                <input type="text" name="log_age" class="form-control form-control-sm" placeholder="e.g. 2 mo">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Temp (°C)</label>
                                                <input type="text" name="log_temp" class="form-control form-control-sm" placeholder="36.5">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Weight (kg)</label>
                                                <input type="text" name="log_weight" class="form-control form-control-sm" placeholder="4.5">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Length (cm)</label>
                                                <input type="text" name="log_length" class="form-control form-control-sm" placeholder="55">
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted mb-1">Head Circ. (cm)</label>
                                                <input type="text" name="log_hc" class="form-control form-control-sm" placeholder="HC">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted mb-1">Chest Circ. (cm)</label>
                                                <input type="text" name="log_cc" class="form-control form-control-sm" placeholder="CC">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted mb-1">Abd. Circ. (cm)</label>
                                                <input type="text" name="log_ac" class="form-control form-control-sm" placeholder="AC">
                                            </div>
                                        </div>

<div class="row g-2 mb-3">
    <div class="col-md-6">
        <label class="form-label small fw-bold text-muted mb-1">Type of Vaccine / Bakuna <span class="text-danger">*</span></label>
        <input type="text" name="log_bakuna" class="form-control form-control-sm" placeholder="e.g. BCG, OPV, Penta 1..." required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-bold text-muted mb-1">Next Visit Date <span class="text-danger">*</span></label>
        <input type="date" name="log_next_visit" class="form-control form-control-sm" required>
    </div>
</div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted mb-1">Doctor's Instructions / Notes</label>
                                            <textarea name="log_instructions" class="form-control form-control-sm" rows="2" placeholder="Side effects to watch out for, paracetamol dosage..."></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-sm fw-bold w-100 shadow-sm">
                                            <i class="fas fa-syringe me-1"></i> Save Visit Record & Complete Appointment
                                        </button>
                                    </div>
                                </div>
                                @endif

                                {{-- HISTORY TABLE --}}
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-history me-2"></i>Visitation History</h6>
                                <div class="table-responsive bg-white rounded-3 shadow-sm border">
                                    <table class="table table-sm table-hover align-middle mb-0 text-center" style="font-size: 0.8rem;">
                                        <thead class="table-light text-muted">
                                            <tr>
                                                <th class="py-2 text-start ps-3">Date / Age</th>
                                                <th class="py-2">Vitals</th>
                                                <th class="py-2">Measurements</th>
                                                <th class="py-2 text-start">Vaccine & Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $logs = \App\Models\ImmunizationLog::where('user_id', $patient->id)->orderBy('date', 'desc')->get();
                                            @endphp

                                            @forelse($logs as $log)
                                            <tr>
                                                <td class="text-start ps-3">
                                                    <div class="fw-bold text-primary">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</div>
                                                    <small class="text-muted">{{ $log->age ?? '-' }}</small>
                                                </td>
                                                <td>
                                                    <div>T: {{ $log->temp ? $log->temp.'°C' : '-' }}</div>
                                                    <div>W: {{ $log->weight ? $log->weight.'kg' : '-' }}</div>
                                                    <div>L: {{ $log->length ? $log->length.'cm' : '-' }}</div>
                                                </td>
                                                <td>
                                                    <div>HC: {{ $log->hc ?? '-' }}</div>
                                                    <div>CC: {{ $log->cc ?? '-' }}</div>
                                                    <div>AC: {{ $log->ac ?? '-' }}</div>
                                                </td>
                                                <td class="text-start">
                                                    <div class="fw-bold text-dark">{{ $log->type_of_bakuna ?? 'Check-up Only' }}</div>
                                                    @if($log->doctor_instructions)
                                                        <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($log->doctor_instructions, 40) }}</div>
                                                    @endif
                                                    @if($log->next_visit)
                                                        <div class="text-danger mt-1" style="font-size: 0.75rem;"><i class="fas fa-calendar-alt me-1"></i>Next: {{ \Carbon\Carbon::parse($log->next_visit)->format('M d, Y') }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted fst-italic">
                                                    No visits recorded yet. Fill out the form above to log the first visit.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>