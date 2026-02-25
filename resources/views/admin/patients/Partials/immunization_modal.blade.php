<div class="modal fade" id="viewImmunizationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-syringe me-2"></i>Immunization Record - {{ $patient->first_name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                
                @php
                    $ir = $patient->immunizationRecord;
                @endphp

                <form action="{{ route('admin.patients.update_immunization', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="alert alert-info border-0 shadow-sm small mb-4">
                        <i class="fas fa-info-circle me-1"></i> Input the exact date the vaccine was administered. Leave blank if not yet given.
                    </div>

                    <div class="table-responsive bg-light p-3 rounded border border-secondary border-opacity-10 mb-4">
                        <table class="table table-bordered bg-white mb-0 align-middle text-center">
                            <thead class="bg-primary bg-opacity-10 text-primary">
                                <tr>
                                    <th class="small fw-bold text-start">Bakuna (Vaccine)</th>
                                    <th class="small fw-bold" style="width: 100px;">Dose</th>
                                    <th class="small fw-bold" style="width: 150px;">Recommended Age</th>
                                    <th class="small fw-bold" style="width: 180px;">Date Administered</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- BCG --}}
                                <tr>
                                    <td class="fw-bold text-dark text-start">BCG (TB)</td>
                                    <td class="small">1st Dose</td>
                                    <td class="small text-muted">Pagkapanganak</td>
                                    <td><input type="date" name="bcg_date" class="form-control form-control-sm text-center" value="{{ old('bcg_date', $ir->bcg_date ?? '') }}"></td>
                                </tr>
                                
                                {{-- Hepatitis B --}}
                                <tr>
                                    <td class="fw-bold text-dark text-start">Hepatitis B</td>
                                    <td class="small">1st Dose</td>
                                    <td class="small text-muted">Pagkapanganak</td>
                                    <td><input type="date" name="hepb_birth_date" class="form-control form-control-sm text-center" value="{{ old('hepb_birth_date', $ir->hepb_birth_date ?? '') }}"></td>
                                </tr>
                                
                                {{-- Pentavalent --}}
                                <tr>
                                    <td class="fw-bold text-dark text-start" rowspan="3">Pentavalent Vaccine <br><small class="text-muted fw-normal">Dipterya, Tetano, Hepa B, Pertussis, Pulmonya, Meningitis</small></td>
                                    <td class="small">1st Dose</td>
                                    <td class="small text-muted">1 ½ month</td>
                                    <td><input type="date" name="penta_1_date" class="form-control form-control-sm text-center" value="{{ old('penta_1_date', $ir->penta_1_date ?? '') }}"></td>
                                </tr>
                                <tr>
                                    <td class="small border-start">2nd Dose</td>
                                    <td class="small text-muted">2 ½ month</td>
                                    <td><input type="date" name="penta_2_date" class="form-control form-control-sm text-center" value="{{ old('penta_2_date', $ir->penta_2_date ?? '') }}"></td>
                                </tr>
                                <tr>
                                    <td class="small border-start">3rd Dose</td>
                                    <td class="small text-muted">3 ½ month</td>
                                    <td><input type="date" name="penta_3_date" class="form-control form-control-sm text-center" value="{{ old('penta_3_date', $ir->penta_3_date ?? '') }}"></td>
                                </tr>

                                {{-- OPV --}}
                                <tr>
                                    <td class="fw-bold text-dark text-start" rowspan="3">Oral Polio Vaccine (OPV) <br><small class="text-muted fw-normal">Polio</small></td>
                                    <td class="small">1st Dose</td>
                                    <td class="small text-muted">1 ½ month</td>
                                    <td><input type="date" name="opv_1_date" class="form-control form-control-sm text-center" value="{{ old('opv_1_date', $ir->opv_1_date ?? '') }}"></td>
                                </tr>
                                <tr>
                                    <td class="small border-start">2nd Dose</td>
                                    <td class="small text-muted">2 ½ month</td>
                                    <td><input type="date" name="opv_2_date" class="form-control form-control-sm text-center" value="{{ old('opv_2_date', $ir->opv_2_date ?? '') }}"></td>
                                </tr>
                                <tr>
                                    <td class="small border-start">3rd Dose</td>
                                    <td class="small text-muted">3 ½ month</td>
                                    <td><input type="date" name="opv_3_date" class="form-control form-control-sm text-center" value="{{ old('opv_3_date', $ir->opv_3_date ?? '') }}"></td>
                                </tr>

                                {{-- IPV --}}
                                <tr>
                                    <td class="fw-bold text-dark text-start">Inactivated Polio (IPV) <br><small class="text-muted fw-normal">Polio</small></td>
                                    <td class="small">1st Dose</td>
                                    <td class="small text-muted">3 ½ month</td>
                                    <td><input type="date" name="ipv_1_date" class="form-control form-control-sm text-center" value="{{ old('ipv_1_date', $ir->ipv_1_date ?? '') }}"></td>
                                </tr>

                                {{-- PCV --}}
                                <tr>
                                    <td class="fw-bold text-dark text-start" rowspan="3">Pneumococcal Conjugate Vaccine (PCV) <br><small class="text-muted fw-normal">Pulmonya, Meningitis</small></td>
                                    <td class="small">1st Dose</td>
                                    <td class="small text-muted">1 ½ month</td>
                                    <td><input type="date" name="pcv_1_date" class="form-control form-control-sm text-center" value="{{ old('pcv_1_date', $ir->pcv_1_date ?? '') }}"></td>
                                </tr>
                                <tr>
                                    <td class="small border-start">2nd Dose</td>
                                    <td class="small text-muted">2 ½ month</td>
                                    <td><input type="date" name="pcv_2_date" class="form-control form-control-sm text-center" value="{{ old('pcv_2_date', $ir->pcv_2_date ?? '') }}"></td>
                                </tr>
                                <tr>
                                    <td class="small border-start">3rd Dose</td>
                                    <td class="small text-muted">3 ½ month</td>
                                    <td><input type="date" name="pcv_3_date" class="form-control form-control-sm text-center" value="{{ old('pcv_3_date', $ir->pcv_3_date ?? '') }}"></td>
                                </tr>

                                {{-- MMR --}}
                                <tr>
                                    <td class="fw-bold text-dark text-start" rowspan="2">Measles, Mumps, Rubella (MMR) <br><small class="text-muted fw-normal">Tigdas, Beke, German Measles</small></td>
                                    <td class="small">1st Dose</td>
                                    <td class="small text-muted">9 months</td>
                                    <td><input type="date" name="mmr_1_date" class="form-control form-control-sm text-center" value="{{ old('mmr_1_date', $ir->mmr_1_date ?? '') }}"></td>
                                </tr>
                                <tr>
                                    <td class="small border-start">2nd Dose</td>
                                    <td class="small text-muted">1 year</td>
                                    <td><input type="date" name="mmr_2_date" class="form-control form-control-sm text-center" value="{{ old('mmr_2_date', $ir->mmr_2_date ?? '') }}"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4">Additional Vaccines / Notes</h6>
                    <div class="mb-3">
                        <textarea name="additional_notes" class="form-control" rows="3" placeholder="Enter any additional doses, vaccines, or doctor notes here...">{{ old('additional_notes', $ir->additional_notes ?? '') }}</textarea>
                    </div>

                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-secondary rounded-pill px-4 me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Save Immunization Record</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>