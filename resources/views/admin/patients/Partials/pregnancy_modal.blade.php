<div class="modal fade" id="viewPregnancyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-baby me-2"></i>Pregnancy Record - {{ $patient->first_name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                
                @php
                    $pr = $patient->pregnancyRecord;
                @endphp

                <form action="{{ route('admin.patients.update_pregnancy', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">General Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Date of Registration</label>
                            <input type="date" name="date_of_registration" class="form-control" value="{{ old('date_of_registration', $pr->date_of_registration ?? date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">Complete Address</label>
                            <input type="text" class="form-control bg-light" value="{{ $patient->address }}" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Age</label>
                            <input type="text" class="form-control bg-light" value="{{ $patient->age }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Age Group</label>
                            <select name="age_group" class="form-select">
                                @php
                                    $ageInYears = $patient->date_of_birth ? $patient->date_of_birth->diffInYears(\Carbon\Carbon::now()) : 0;
                                    $savedAgeGroup = old('age_group', $pr->age_group ?? '');
                                    $groupA = ($savedAgeGroup == 'A' || (empty($savedAgeGroup) && $ageInYears >= 10 && $ageInYears <= 14)) ? 'selected' : '';
                                    $groupB = ($savedAgeGroup == 'B' || (empty($savedAgeGroup) && $ageInYears >= 15 && $ageInYears <= 19)) ? 'selected' : '';
                                    $groupC = ($savedAgeGroup == 'C' || (empty($savedAgeGroup) && $ageInYears >= 20 && $ageInYears <= 49)) ? 'selected' : '';
                                @endphp
                                <option value="">Select...</option>
                                <option value="A" {{ $groupA }}>A - 10-14 y.o</option>
                                <option value="B" {{ $groupB }}>B - 15-19 y.o</option>
                                <option value="C" {{ $groupC }}>C - 20-49 y.o</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Gravida Parity (G-P)</label>
                            <input type="text" name="gravida_parity" class="form-control" placeholder="e.g. G2 P1" value="{{ old('gravida_parity', $pr->gravida_parity ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Last Menstrual Period (LMP)</label>
                            <input type="date" name="lmp" class="form-control" value="{{ old('lmp', $pr->lmp ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Expected Date of Delivery (EDD)</label>
                            <input type="date" name="edd" class="form-control" value="{{ old('edd', $pr->edd ?? '') }}">
                        </div>
                    </div>

                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Date of Prenatal Check-ups (BANC)</h6>
                    
                    {{-- 1st Trimester --}}
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <span class="d-block fw-bold text-dark mb-2">1st Trimester <small class="text-muted fw-normal fst-italic">(Recommended Timing: 8 - 13 weeks)</small></span>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Visit 1</label>
                                <input type="date" name="visit_1" class="form-control form-control-sm" value="{{ old('visit_1', $pr->visit_1 ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- 2nd Trimester --}}
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <span class="d-block fw-bold text-dark mb-2">2nd Trimester <small class="text-muted fw-normal fst-italic">(Recommended Timing: 14 - 20 weeks, 21 - 27 weeks)</small></span>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Visit 2</label>
                                <input type="date" name="visit_2" class="form-control form-control-sm" value="{{ old('visit_2', $pr->visit_2 ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Visit 3</label>
                                <input type="date" name="visit_3" class="form-control form-control-sm" value="{{ old('visit_3', $pr->visit_3 ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- 3rd Trimester --}}
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <span class="d-block fw-bold text-dark mb-2">3rd Trimester <small class="text-muted fw-normal fst-italic">(Recommended Timing: 28-30, 31-34, 35, 36, 37-40 weeks)</small></span>
                        <div class="row g-2">
                            <div class="col-md-4 mb-2">
                                <label class="form-label small text-muted mb-1">Visit 4 (28-30 wks)</label>
                                <input type="date" name="visit_4" class="form-control form-control-sm" value="{{ old('visit_4', $pr->visit_4 ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small text-muted mb-1">Visit 5 (31-34 wks)</label>
                                <input type="date" name="visit_5" class="form-control form-control-sm" value="{{ old('visit_5', $pr->visit_5 ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small text-muted mb-1">Visit 6 (35 wks)</label>
                                <input type="date" name="visit_6" class="form-control form-control-sm" value="{{ old('visit_6', $pr->visit_6 ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Visit 7 (36 wks)</label>
                                <input type="date" name="visit_7" class="form-control form-control-sm" value="{{ old('visit_7', $pr->visit_7 ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Visit 8 (37-40 wks)</label>
                                <input type="date" name="visit_8" class="form-control form-control-sm" value="{{ old('visit_8', $pr->visit_8 ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Nutritional Assessment --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Nutritional Assessment</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small text-muted mb-1">BMI for 1st trimester (1st visit)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.1" name="bmi" class="form-control" placeholder="BMI" value="{{ old('bmi', $pr->bmi ?? '') }}">
                                    <span class="input-group-text">kg/m²</span>
                                    <select name="bmi_category" class="form-select">
                                        <option value="">Select Category...</option>
                                        <option value="Low" {{ old('bmi_category', $pr->bmi_category ?? '') == 'Low' ? 'selected' : '' }}>Low: < 18.5</option>
                                        <option value="Normal" {{ old('bmi_category', $pr->bmi_category ?? '') == 'Normal' ? 'selected' : '' }}>Normal: 18.5 - 22.9</option>
                                        <option value="High" {{ old('bmi_category', $pr->bmi_category ?? '') == 'High' ? 'selected' : '' }}>High: ≥ 23.0</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Remarks</label>
                                <select name="nutritional_remarks" class="form-select form-select-sm">
                                    <option value="">None</option>
                                    <option value="A" {{ old('nutritional_remarks', $pr->nutritional_remarks ?? '') == 'A' ? 'selected' : '' }}>A - Trans in</option>
                                    <option value="B" {{ old('nutritional_remarks', $pr->nutritional_remarks ?? '') == 'B' ? 'selected' : '' }}>B - Trans Out before receiving 8ANC</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Immunization Status (Td/TT Vaccines) --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Immunization Status</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <span class="d-block fw-bold text-dark mb-2">Date Tetanus Diphtheria (Td)-containing vaccine given <small class="text-muted fw-normal fst-italic">(mm/dd/yy)</small></span>
                        <div class="row g-2">
                            <div class="col-md-4 mb-2">
                                <label class="form-label small text-muted mb-1">Td1 / TT1</label>
                                <input type="date" name="td1" class="form-control form-control-sm" value="{{ old('td1', $pr->td1 ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small text-muted mb-1">Td2 / TT2</label>
                                <input type="date" name="td2" class="form-control form-control-sm" value="{{ old('td2', $pr->td2 ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small text-muted mb-1">Td3 / TT3</label>
                                <input type="date" name="td3" class="form-control form-control-sm" value="{{ old('td3', $pr->td3 ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Td4 / TT4</label>
                                <input type="date" name="td4" class="form-control form-control-sm" value="{{ old('td4', $pr->td4 ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Td5 / TT5</label>
                                <input type="date" name="td5" class="form-control form-control-sm" value="{{ old('td5', $pr->td5 ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Prenatal Supplementation --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Prenatal Supplementation</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">

                        {{-- Deworming --}}
                        <div class="mb-4 bg-white p-3 rounded shadow-sm border border-secondary border-opacity-10 d-flex flex-column flex-md-row align-items-md-center">
                            <label class="form-label fw-bold mb-2 mb-md-0 me-4 text-dark">Received one dose of deworming?</label>
                            <div>
                                <div class="form-check form-check-inline mb-0">
                                    <input class="form-check-input" type="radio" name="deworming" id="deworm_yes" value="1" {{ old('deworming', $pr->deworming ?? '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label text-success fw-bold" for="deworm_yes">Yes (1)</label>
                                </div>
                                <div class="form-check form-check-inline mb-0">
                                    <input class="form-check-input" type="radio" name="deworming" id="deworm_no" value="0" {{ old('deworming', $pr->deworming ?? '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label text-danger" for="deworm_no">No (0)</label>
                                </div>
                            </div>
                        </div>

                        {{-- IFA Supplementation --}}
                        <div class="mb-4">
                            <span class="d-block fw-bold text-dark mb-1"><i class="fas fa-pills text-danger me-2"></i>Iron with Folic Acid (IFA) Supplementation</span>
                            <p class="small text-muted mb-3 fst-italic">#: Number of tablets Given &nbsp;|&nbsp; d: Date (mm/dd/yy)</p>
                            <div class="row g-2 mb-3">
                                @foreach(['1st visit(1st tri)' => 'v1', '2nd visit(2nd tri)' => 'v2', '3rd visit(2nd tri)' => 'v3', '4th visit(3rd tri)' => 'v4', '5th visit(3rd tri)' => 'v5', '6th visit(3rd tri)' => 'v6'] as $label => $key)
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small text-muted mb-1">{{ $label }}</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white fw-bold">#</span>
                                        <input type="number" name="ifa_{{ $key }}_tablets" class="form-control" placeholder="Qty" value="{{ old('ifa_'.$key.'_tablets', $pr->{'ifa_'.$key.'_tablets'} ?? '') }}">
                                        <span class="input-group-text bg-white fw-bold text-muted">d</span>
                                        <input type="date" name="ifa_{{ $key }}_date" class="form-control" value="{{ old('ifa_'.$key.'_date', $pr->{'ifa_'.$key.'_date'} ?? '') }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="row align-items-center bg-white p-2 rounded border border-secondary border-opacity-10 mx-0 shadow-sm">
                                <div class="col-md-6 d-flex align-items-center mb-2 mb-md-0">
                                    <label class="form-label small fw-bold mb-0 me-3 text-dark">Completed IFA Supplementation?</label>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="ifa_completed" id="ifa_comp_yes" value="1" {{ old('ifa_completed', $pr->ifa_completed ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success small fw-bold" for="ifa_comp_yes">Yes (1)</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="ifa_completed" id="ifa_comp_no" value="0" {{ old('ifa_completed', $pr->ifa_completed ?? '0') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger small" for="ifa_comp_no">No (0)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">If Yes, Date Completed:</span>
                                        <input type="date" name="ifa_completed_date" class="form-control" value="{{ old('ifa_completed_date', $pr->ifa_completed_date ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="opacity-10 my-4 border-secondary">

                        {{-- MMS Supplementation --}}
                        <div class="mb-4">
                            <span class="d-block fw-bold text-dark mb-1"><i class="fas fa-capsules text-primary me-2"></i>Multiple Micronutrient Supplementation (MMS)</span>
                            <p class="small text-muted mb-3 fst-italic">#: Number of tablets Given &nbsp;|&nbsp; d: Date (mm/dd/yy)</p>
                            <div class="row g-2 mb-3">
                                @foreach(['1st visit(1st tri)' => 'v1', '2nd visit(2nd tri)' => 'v2', '3rd visit(2nd tri)' => 'v3', '4th visit(3rd tri)' => 'v4', '5th visit(3rd tri)' => 'v5', '6th visit(3rd tri)' => 'v6'] as $label => $key)
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small text-muted mb-1">{{ $label }}</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white fw-bold">#</span>
                                        <input type="number" name="mms_{{ $key }}_tablets" class="form-control" placeholder="Qty" value="{{ old('mms_'.$key.'_tablets', $pr->{'mms_'.$key.'_tablets'} ?? '') }}">
                                        <span class="input-group-text bg-white fw-bold text-muted">d</span>
                                        <input type="date" name="mms_{{ $key }}_date" class="form-control" value="{{ old('mms_'.$key.'_date', $pr->{'mms_'.$key.'_date'} ?? '') }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="row align-items-center bg-white p-2 rounded border border-secondary border-opacity-10 mx-0 shadow-sm">
                                <div class="col-md-6 d-flex align-items-center mb-2 mb-md-0">
                                    <label class="form-label small fw-bold mb-0 me-3 text-dark">Completed MM Supplementation?</label>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="mms_completed" id="mms_comp_yes" value="1" {{ old('mms_completed', $pr->mms_completed ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success small fw-bold" for="mms_comp_yes">Yes (1)</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="mms_completed" id="mms_comp_no" value="0" {{ old('mms_completed', $pr->mms_completed ?? '0') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger small" for="mms_comp_no">No (0)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">If Yes, Date Completed:</span>
                                        <input type="date" name="mms_completed_date" class="form-control" value="{{ old('mms_completed_date', $pr->mms_completed_date ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="opacity-10 my-4 border-secondary">

                        {{-- CC Supplementation (High Risk) --}}
                        <div class="mb-2">
                            <span class="d-block fw-bold text-danger mb-1"><i class="fas fa-exclamation-triangle me-2"></i>FOR HIGH RISK PREGNANT: Calcium Carbonate (CC) Supplementation</span>
                            <p class="small text-muted mb-3 fst-italic">#: Number of tablets Given &nbsp;|&nbsp; d: Date (mm/dd/yy)</p>
                            <div class="row g-2 mb-3">
                                @foreach(['2nd visit(2nd tri)' => 'v2', '3rd visit(3rd tri)' => 'v3', '4th visit(3rd tri)' => 'v4'] as $label => $key)
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small text-muted mb-1">{{ $label }}</label>
                                    <div class="input-group input-group-sm shadow-sm border-danger border-opacity-50 border rounded">
                                        <span class="input-group-text bg-white fw-bold text-danger border-0">#</span>
                                        <input type="number" name="cc_{{ $key }}_tablets" class="form-control border-0" placeholder="Qty" value="{{ old('cc_'.$key.'_tablets', $pr->{'cc_'.$key.'_tablets'} ?? '') }}">
                                        <span class="input-group-text bg-white fw-bold text-muted border-0 border-start">d</span>
                                        <input type="date" name="cc_{{ $key }}_date" class="form-control border-0" value="{{ old('cc_'.$key.'_date', $pr->{'cc_'.$key.'_date'} ?? '') }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="row align-items-center bg-white p-2 rounded border border-danger border-opacity-25 mx-0 shadow-sm">
                                <div class="col-md-6 d-flex align-items-center mb-2 mb-md-0">
                                    <label class="form-label small fw-bold mb-0 me-3 text-dark">Completed CC Supplementation?</label>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="cc_completed" id="cc_comp_yes" value="1" {{ old('cc_completed', $pr->cc_completed ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success small fw-bold" for="cc_comp_yes">Yes (1)</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="cc_completed" id="cc_comp_no" value="0" {{ old('cc_completed', $pr->cc_completed ?? '0') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger small" for="cc_comp_no">No (0)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light text-danger fw-bold border-danger border-opacity-50">If Yes, Date Completed:</span>
                                        <input type="date" name="cc_completed_date" class="form-control border-danger border-opacity-50" value="{{ old('cc_completed_date', $pr->cc_completed_date ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Laboratory Screenings --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Laboratory Screenings</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <div class="row g-3">
                            {{-- Syphilis --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-dark mb-1">Syphilis</label>
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text bg-white">Date</span>
                                    <input type="date" name="lab_syphilis_date" class="form-control" value="{{ old('lab_syphilis_date', $pr->lab_syphilis_date ?? '') }}">
                                </div>
                                <select name="lab_syphilis_result" class="form-select form-select-sm">
                                    <option value="">Select Result...</option>
                                    <option value="1" {{ old('lab_syphilis_result', $pr->lab_syphilis_result ?? '') == '1' ? 'selected' : '' }}>1 - Positive</option>
                                    <option value="0" {{ old('lab_syphilis_result', $pr->lab_syphilis_result ?? '') == '0' ? 'selected' : '' }}>0 - Negative</option>
                                </select>
                            </div>
                            
                            {{-- HIV --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-dark mb-1">HIV</label>
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text bg-white">Date</span>
                                    <input type="date" name="lab_hiv_date" class="form-control" value="{{ old('lab_hiv_date', $pr->lab_hiv_date ?? '') }}">
                                </div>
                                <select name="lab_hiv_result" class="form-select form-select-sm">
                                    <option value="">Select Result...</option>
                                    <option value="1" {{ old('lab_hiv_result', $pr->lab_hiv_result ?? '') == '1' ? 'selected' : '' }}>1 - Positive</option>
                                    <option value="0" {{ old('lab_hiv_result', $pr->lab_hiv_result ?? '') == '0' ? 'selected' : '' }}>0 - Negative</option>
                                </select>
                            </div>

                            {{-- Hepatitis B --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-dark mb-1">Hepatitis B</label>
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text bg-white">Date</span>
                                    <input type="date" name="lab_hepb_date" class="form-control" value="{{ old('lab_hepb_date', $pr->lab_hepb_date ?? '') }}">
                                </div>
                                <select name="lab_hepb_result" class="form-select form-select-sm">
                                    <option value="">Select Result...</option>
                                    <option value="1" {{ old('lab_hepb_result', $pr->lab_hepb_result ?? '') == '1' ? 'selected' : '' }}>1 - Positive</option>
                                    <option value="0" {{ old('lab_hepb_result', $pr->lab_hepb_result ?? '') == '0' ? 'selected' : '' }}>0 - Negative</option>
                                </select>
                            </div>

                            {{-- CBC / Hgb & Hct Count --}}
                            <div class="col-md-6 mt-3">
                                <label class="form-label fw-bold small text-dark mb-1">CBC / Hgb & Hct Count</label>
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text bg-white">Date</span>
                                    <input type="date" name="lab_cbc_date" class="form-control" value="{{ old('lab_cbc_date', $pr->lab_cbc_date ?? '') }}">
                                </div>
                                <select name="lab_cbc_result" class="form-select form-select-sm">
                                    <option value="">Select Result...</option>
                                    <option value="1" {{ old('lab_cbc_result', $pr->lab_cbc_result ?? '') == '1' ? 'selected' : '' }}>1 - With Anemia</option>
                                    <option value="0" {{ old('lab_cbc_result', $pr->lab_cbc_result ?? '') == '0' ? 'selected' : '' }}>0 - W/o Anemia</option>
                                </select>
                            </div>

                            {{-- Gestational Diabetes Mellitus --}}
                            <div class="col-md-6 mt-3">
                                <label class="form-label fw-bold small text-dark mb-1">Gestational Diabetes Mellitus</label>
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text bg-white">Date</span>
                                    <input type="date" name="lab_gdm_date" class="form-control" value="{{ old('lab_gdm_date', $pr->lab_gdm_date ?? '') }}">
                                </div>
                                <select name="lab_gdm_result" class="form-select form-select-sm">
                                    <option value="">Select Result...</option>
                                    <option value="1" {{ old('lab_gdm_result', $pr->lab_gdm_result ?? '') == '1' ? 'selected' : '' }}>1 - Positive</option>
                                    <option value="0" {{ old('lab_gdm_result', $pr->lab_gdm_result ?? '') == '0' ? 'selected' : '' }}>0 - Negative</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Pregnancy Outcome --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Pregnancy Outcome</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Date Terminated</label>
                                <input type="date" name="outcome_date" class="form-control" value="{{ old('outcome_date', $pr->outcome_date ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Outcome</label>
                                <select name="outcome_type" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="FT" {{ old('outcome_type', $pr->outcome_type ?? '') == 'FT' ? 'selected' : '' }}>FT - Full term</option>
                                    <option value="PT" {{ old('outcome_type', $pr->outcome_type ?? '') == 'PT' ? 'selected' : '' }}>PT - Preterm</option>
                                    <option value="FD" {{ old('outcome_type', $pr->outcome_type ?? '') == 'FD' ? 'selected' : '' }}>FD - Fetal Death</option>
                                    <option value="AB" {{ old('outcome_type', $pr->outcome_type ?? '') == 'AB' ? 'selected' : '' }}>AB - Abortion / Miscarriage</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Delivery Type</label>
                                <select name="delivery_type" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="CS" {{ old('delivery_type', $pr->delivery_type ?? '') == 'CS' ? 'selected' : '' }}>CS - Cesarean section</option>
                                    <option value="VD" {{ old('delivery_type', $pr->delivery_type ?? '') == 'VD' ? 'selected' : '' }}>VD - Vaginal Delivery</option>
                                    <option value="CVCD" {{ old('delivery_type', $pr->delivery_type ?? '') == 'CVCD' ? 'selected' : '' }}>CVCD - Combined Vaginal Cesarean Delivery</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Birth Weight</label>
                                <div class="input-group">
                                    <input type="number" name="birth_weight" class="form-control" placeholder="Weight" value="{{ old('birth_weight', $pr->birth_weight ?? '') }}">
                                    <span class="input-group-text bg-white">grams</span>
                                    <select name="birth_weight_category" class="form-select" style="max-width: 140px;">
                                        <option value="">Select...</option>
                                        <option value="A" {{ old('birth_weight_category', $pr->birth_weight_category ?? '') == 'A' ? 'selected' : '' }}>A - Normal</option>
                                        <option value="B" {{ old('birth_weight_category', $pr->birth_weight_category ?? '') == 'B' ? 'selected' : '' }}>B - Low</option>
                                        <option value="C" {{ old('birth_weight_category', $pr->birth_weight_category ?? '') == 'C' ? 'selected' : '' }}>C - Unknown</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Place of Delivery & Birth Attendant --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Place of Delivery</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Health Facility</label>
                                <select name="delivery_health_facility" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="BHS" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'BHS' ? 'selected' : '' }}>BHS</option>
                                    <option value="RHU_UHU" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'RHU_UHU' ? 'selected' : '' }}>RHU/UHU</option>
                                    <option value="Gov_Hosp" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'Gov_Hosp' ? 'selected' : '' }}>Government Hospitals</option>
                                    <option value="Pub_Infirm" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'Pub_Infirm' ? 'selected' : '' }}>Public Infirmaries</option>
                                    <option value="DOH_Ambulance" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'DOH_Ambulance' ? 'selected' : '' }}>DOH-licensed Ambulance</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Facility Type</label>
                                <select name="delivery_facility_type" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="Public" {{ old('delivery_facility_type', $pr->delivery_facility_type ?? '') == 'Public' ? 'selected' : '' }}>Public</option>
                                    <option value="Private" {{ old('delivery_facility_type', $pr->delivery_facility_type ?? '') == 'Private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 d-flex align-items-end pb-2">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="bemonc_cemonc" id="bemonc_cemonc" value="1" {{ old('bemonc_cemonc', $pr->bemonc_cemonc ?? '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-bold text-dark" for="bemonc_cemonc">
                                        BEmONC/CEmONC capable?
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Non-Health Facility</label>
                                <select name="delivery_non_health_facility" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="1" {{ old('delivery_non_health_facility', $pr->delivery_non_health_facility ?? '') == '1' ? 'selected' : '' }}>1 - Home</option>
                                    <option value="2" {{ old('delivery_non_health_facility', $pr->delivery_non_health_facility ?? '') == '2' ? 'selected' : '' }}>2 - Others (including emergency transport)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Birth Attendant</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Attendant</label>
                                <select name="birth_attendant" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="MD" {{ old('birth_attendant', $pr->birth_attendant ?? '') == 'MD' ? 'selected' : '' }}>MD - Doctor</option>
                                    <option value="RN" {{ old('birth_attendant', $pr->birth_attendant ?? '') == 'RN' ? 'selected' : '' }}>RN - Nurse</option>
                                    <option value="MW" {{ old('birth_attendant', $pr->birth_attendant ?? '') == 'MW' ? 'selected' : '' }}>MW - Midwife</option>
                                    <option value="O" {{ old('birth_attendant', $pr->birth_attendant ?? '') == 'O' ? 'selected' : '' }}>O - Others</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">If Others, Please Specify</label>
                                <input type="text" name="birth_attendant_others" class="form-control" placeholder="Specify here..." value="{{ old('birth_attendant_others', $pr->birth_attendant_others ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Date & Time of Delivery --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Date & Time of Delivery</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Date (mm/dd/yy)</label>
                                <input type="date" name="delivery_date_actual" class="form-control" value="{{ old('delivery_date_actual', $pr->delivery_date_actual ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Time</label>
                                <input type="time" name="delivery_time_actual" class="form-control" value="{{ old('delivery_time_actual', $pr->delivery_time_actual ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Date of Postnatal Care (4PNC) --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Date of Postnatal Care (4PNC)</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <div class="row g-2 mb-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-muted mb-1">Contact 1 <small>(Within 24 hours)</small></label>
                                <input type="date" name="pnc_contact_1" class="form-control form-control-sm" value="{{ old('pnc_contact_1', $pr->pnc_contact_1 ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-muted mb-1">Contact 2 <small>(On day 3)</small></label>
                                <input type="date" name="pnc_contact_2" class="form-control form-control-sm" value="{{ old('pnc_contact_2', $pr->pnc_contact_2 ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-muted mb-1">Contact 3 <small>(Between 7 - 14 days)</small></label>
                                <input type="date" name="pnc_contact_3" class="form-control form-control-sm" value="{{ old('pnc_contact_3', $pr->pnc_contact_3 ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-muted mb-1">Contact 4 <small>(6 weeks after birth)</small></label>
                                <input type="date" name="pnc_contact_4" class="form-control form-control-sm" value="{{ old('pnc_contact_4', $pr->pnc_contact_4 ?? '') }}">
                            </div>
                        </div>
                        <div class="row align-items-center bg-white p-2 rounded border border-secondary border-opacity-10 mx-0 shadow-sm">
                            <div class="col-md-12 d-flex flex-column flex-md-row align-items-md-center">
                                <label class="form-label small fw-bold mb-2 mb-md-0 me-4 text-dark">Completed 4PNC?</label>
                                <div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="completed_4pnc" id="pnc_comp_yes" value="1" {{ old('completed_4pnc', $pr->completed_4pnc ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success small fw-bold" for="pnc_comp_yes">Yes (1)</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="completed_4pnc" id="pnc_comp_no" value="0" {{ old('completed_4pnc', $pr->completed_4pnc ?? '0') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger small" for="pnc_comp_no">No (0)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Postpartum Supplementation --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Postpartum Supplementation</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        
                        {{-- IFA Supplementation --}}
                        <div class="mb-4">
                            <span class="d-block fw-bold text-dark mb-1"><i class="fas fa-pills text-danger me-2"></i>Iron with Folic Acid (IFA) Supplementation</span>
                            <p class="small text-muted mb-3 fst-italic">#: Number of tablets Given &nbsp;|&nbsp; d: Date (mm/dd/yy)</p>
                            <div class="row g-2 mb-3">
                                @foreach(['1st visit' => 'v1', '2nd visit' => 'v2', '3rd visit' => 'v3'] as $label => $key)
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small text-muted mb-1">{{ $label }}</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white fw-bold">#</span>
                                        <input type="number" name="pp_ifa_{{ $key }}_tablets" class="form-control" placeholder="Qty" value="{{ old('pp_ifa_'.$key.'_tablets', $pr->{'pp_ifa_'.$key.'_tablets'} ?? '') }}">
                                        <span class="input-group-text bg-white fw-bold text-muted">d</span>
                                        <input type="date" name="pp_ifa_{{ $key }}_date" class="form-control" value="{{ old('pp_ifa_'.$key.'_date', $pr->{'pp_ifa_'.$key.'_date'} ?? '') }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="row align-items-center bg-white p-2 rounded border border-secondary border-opacity-10 mx-0 shadow-sm">
                                <div class="col-md-6 d-flex align-items-center mb-2 mb-md-0">
                                    <label class="form-label small fw-bold mb-0 me-3 text-dark">Completed IFA Supplementation?</label>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="pp_ifa_completed" id="pp_ifa_comp_yes" value="1" {{ old('pp_ifa_completed', $pr->pp_ifa_completed ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success small fw-bold" for="pp_ifa_comp_yes">Yes (1)</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="pp_ifa_completed" id="pp_ifa_comp_no" value="0" {{ old('pp_ifa_completed', $pr->pp_ifa_completed ?? '0') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger small" for="pp_ifa_comp_no">No (0)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">If Yes, Date Completed:</span>
                                        <input type="date" name="pp_ifa_completed_date" class="form-control" value="{{ old('pp_ifa_completed_date', $pr->pp_ifa_completed_date ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="opacity-10 my-4 border-secondary">

                        {{-- Vitamin A Supplementation --}}
                        <div class="mb-2">
                            <span class="d-block fw-bold text-dark mb-2"><i class="fas fa-capsules text-warning me-2"></i>Vitamin A Supplementation</span>
                            <div class="row align-items-center bg-white p-2 rounded border border-secondary border-opacity-10 mx-0 shadow-sm">
                                <div class="col-md-6 d-flex align-items-center mb-2 mb-md-0">
                                    <label class="form-label small fw-bold mb-0 me-3 text-dark">Completed Vit. A Supplementation?</label>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="pp_vita_completed" id="pp_vita_comp_yes" value="1" {{ old('pp_vita_completed', $pr->pp_vita_completed ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success small fw-bold" for="pp_vita_comp_yes">Yes (1)</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="pp_vita_completed" id="pp_vita_comp_no" value="0" {{ old('pp_vita_completed', $pr->pp_vita_completed ?? '0') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger small" for="pp_vita_comp_no">No (0)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">If Yes, Date Completed:</span>
                                        <input type="date" name="pp_vita_completed_date" class="form-control" value="{{ old('pp_vita_completed_date', $pr->pp_vita_completed_date ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Postpartum Remarks --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Postpartum Remarks</h6>
                    <div class="mb-3 p-3 bg-light rounded border border-secondary border-opacity-10">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted mb-2">Remarks</label>
                                <select name="postpartum_remarks" class="form-select">
                                    <option value="">Select Remarks...</option>
                                    <option value="A" {{ old('postpartum_remarks', $pr->postpartum_remarks ?? '') == 'A' ? 'selected' : '' }}>A - Trans in</option>
                                    <option value="B" {{ old('postpartum_remarks', $pr->postpartum_remarks ?? '') == 'B' ? 'selected' : '' }}>B - Trans Out before completing</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- General Record Completion --}}
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3 mt-4">Record Completion</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="p-3 bg-light rounded border border-secondary border-opacity-10 d-flex flex-column flex-md-row align-items-md-center">
                                <label class="form-label fw-bold mb-2 mb-md-0 me-4 text-dark">Completed 8ANC?</label>
                                <div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="completed_8anc" id="anc_yes" value="1" {{ old('completed_8anc', $pr->completed_8anc ?? '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success fw-bold" for="anc_yes">Yes (1)</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="completed_8anc" id="anc_no" value="0" {{ old('completed_8anc', $pr->completed_8anc ?? '0') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger" for="anc_no">No (0)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-secondary rounded-pill px-4 me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Save Pregnancy Record</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>