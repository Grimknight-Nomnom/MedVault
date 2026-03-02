<div class="modal fade" id="viewPregnancyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-baby me-2"></i>Pregnancy Record - {{ $patient->first_name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-0 bg-white">
                @php
                    $pr = $patient->pregnancyRecord;
                @endphp

                <form action="{{ route('admin.patients.update_pregnancy', $patient->id) }}" method="POST" id="pregnancyForm">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="redirect_to_calendar" id="redirect_to_calendar" value="0">
                    
                    <div class="bg-light border-bottom px-4 pt-3">
                        <ul class="nav nav-tabs nav-tabs-custom border-0" id="pregnancyTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold text-secondary" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab"><i class="fas fa-info-circle me-1"></i> General</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="prenatal-tab" data-bs-toggle="tab" data-bs-target="#prenatal" type="button" role="tab"><i class="fas fa-calendar-check me-1"></i> Prenatal (BANC)</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="supplements-tab" data-bs-toggle="tab" data-bs-target="#supplements" type="button" role="tab"><i class="fas fa-pills me-1"></i> Meds & Vaccines</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="labs-tab" data-bs-toggle="tab" data-bs-target="#labs" type="button" role="tab"><i class="fas fa-microscope me-1"></i> Laboratories</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button" role="tab"><i class="fas fa-procedures me-1"></i> Delivery & Postpartum</button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content p-4" id="pregnancyTabsContent">
                        
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">General Information</h6>
                            <div class="row g-4 mb-4">
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
                                        @endphp
                                        <option value="">Select...</option>
                                        <option value="A" {{ ($savedAgeGroup == 'A' || (empty($savedAgeGroup) && $ageInYears >= 10 && $ageInYears <= 14)) ? 'selected' : '' }}>A - 10-14 y.o</option>
                                        <option value="B" {{ ($savedAgeGroup == 'B' || (empty($savedAgeGroup) && $ageInYears >= 15 && $ageInYears <= 19)) ? 'selected' : '' }}>B - 15-19 y.o</option>
                                        <option value="C" {{ ($savedAgeGroup == 'C' || (empty($savedAgeGroup) && $ageInYears >= 20 && $ageInYears <= 49)) ? 'selected' : '' }}>C - 20-49 y.o</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Gravida Parity (G-P)</label>
                                    <input type="text" name="gravida_parity" class="form-control" placeholder="e.g. G2 P1" value="{{ old('gravida_parity', $pr->gravida_parity ?? '') }}">
                                </div>

                                <div class="col-md-6 border-top pt-3">
                                    <label class="form-label small fw-bold text-muted">Last Menstrual Period (LMP)</label>
                                    <input type="date" name="lmp" class="form-control" value="{{ old('lmp', $pr->lmp ?? '') }}">
                                </div>
                                <div class="col-md-6 border-top pt-3">
                                    <label class="form-label small fw-bold text-muted">Expected Date of Delivery (EDD)</label>
                                    <input type="date" name="edd" class="form-control text-primary fw-bold" value="{{ old('edd', $pr->edd ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="prenatal" role="tabpanel">
                            <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">Prenatal Check-ups (BANC)</h6>
                            
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <div class="p-3 bg-light rounded border border-secondary border-opacity-10 mb-3">
                                        <span class="d-block fw-bold text-dark mb-2 border-bottom pb-1">1st Trimester <small class="text-muted fw-normal fst-italic">(8 - 13 wks)</small></span>
                                        <div class="row g-2">
                                            <div class="col-md-6"><label class="small text-muted mb-1">Visit 1</label><input type="date" name="visit_1" class="form-control form-control-sm" value="{{ old('visit_1', $pr->visit_1 ?? '') }}"></div>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-light rounded border border-secondary border-opacity-10 mb-3">
                                        <span class="d-block fw-bold text-dark mb-2 border-bottom pb-1">2nd Trimester <small class="text-muted fw-normal fst-italic">(14 - 27 wks)</small></span>
                                        <div class="row g-2">
                                            <div class="col-md-6"><label class="small text-muted mb-1">Visit 2 (14-20 wks)</label><input type="date" name="visit_2" class="form-control form-control-sm" value="{{ old('visit_2', $pr->visit_2 ?? '') }}"></div>
                                            <div class="col-md-6"><label class="small text-muted mb-1">Visit 3 (21-27 wks)</label><input type="date" name="visit_3" class="form-control form-control-sm" value="{{ old('visit_3', $pr->visit_3 ?? '') }}"></div>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-light rounded border border-secondary border-opacity-10 mb-3">
                                        <span class="d-block fw-bold text-dark mb-2 border-bottom pb-1">3rd Trimester <small class="text-muted fw-normal fst-italic">(28 - 40 wks)</small></span>
                                        <div class="row g-2">
                                            <div class="col-md-4 mb-2"><label class="small text-muted mb-1">Visit 4 (28-30 wks)</label><input type="date" name="visit_4" class="form-control form-control-sm" value="{{ old('visit_4', $pr->visit_4 ?? '') }}"></div>
                                            <div class="col-md-4 mb-2"><label class="small text-muted mb-1">Visit 5 (31-34 wks)</label><input type="date" name="visit_5" class="form-control form-control-sm" value="{{ old('visit_5', $pr->visit_5 ?? '') }}"></div>
                                            <div class="col-md-4 mb-2"><label class="small text-muted mb-1">Visit 6 (35 wks)</label><input type="date" name="visit_6" class="form-control form-control-sm" value="{{ old('visit_6', $pr->visit_6 ?? '') }}"></div>
                                            <div class="col-md-6"><label class="small text-muted mb-1">Visit 7 (36 wks)</label><input type="date" name="visit_7" class="form-control form-control-sm" value="{{ old('visit_7', $pr->visit_7 ?? '') }}"></div>
                                            <div class="col-md-6"><label class="small text-muted mb-1">Visit 8 (37-40 wks)</label><input type="date" name="visit_8" class="form-control form-control-sm" value="{{ old('visit_8', $pr->visit_8 ?? '') }}"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 bg-success bg-opacity-10 border border-success border-opacity-25 rounded d-flex align-items-center justify-content-between">
                                        <label class="form-label fw-bold mb-0 text-success"><i class="fas fa-check-circle me-2"></i>Completed 8ANC?</label>
                                        <div>
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="completed_8anc" id="anc_yes" value="1" {{ old('completed_8anc', $pr->completed_8anc ?? '0') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label text-success fw-bold" for="anc_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="completed_8anc" id="anc_no" value="0" {{ old('completed_8anc', $pr->completed_8anc ?? '0') == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label text-danger" for="anc_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold text-dark border-bottom pb-2"><i class="fas fa-weight me-2"></i>Nutritional Assessment</h6>
                                            <label class="small text-muted mb-1 mt-2">1st Trimester BMI</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="number" step="0.1" name="bmi" class="form-control" placeholder="BMI" value="{{ old('bmi', $pr->bmi ?? '') }}">
                                                <span class="input-group-text">kg/m²</span>
                                            </div>
                                            <label class="small text-muted mb-1">Category</label>
                                            <select name="bmi_category" class="form-select form-select-sm mb-3">
                                                <option value="">Select Category...</option>
                                                <option value="Low" {{ old('bmi_category', $pr->bmi_category ?? '') == 'Low' ? 'selected' : '' }}>Low: < 18.5</option>
                                                <option value="Normal" {{ old('bmi_category', $pr->bmi_category ?? '') == 'Normal' ? 'selected' : '' }}>Normal: 18.5 - 22.9</option>
                                                <option value="High" {{ old('bmi_category', $pr->bmi_category ?? '') == 'High' ? 'selected' : '' }}>High: ≥ 23.0</option>
                                            </select>
                                            <label class="small text-muted mb-1">Remarks</label>
                                            <select name="nutritional_remarks" class="form-select form-select-sm">
                                                <option value="">None</option>
                                                <option value="A" {{ old('nutritional_remarks', $pr->nutritional_remarks ?? '') == 'A' ? 'selected' : '' }}>A - Trans in</option>
                                                <option value="B" {{ old('nutritional_remarks', $pr->nutritional_remarks ?? '') == 'B' ? 'selected' : '' }}>B - Trans Out</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="supplements" role="tabpanel">
                            <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">Immunization & Supplementation</h6>
                            
                            <div class="p-3 bg-light rounded border border-secondary border-opacity-10 mb-4">
                                <span class="d-block fw-bold text-dark mb-2"><i class="fas fa-syringe text-primary me-2"></i>Tetanus Diphtheria (Td) / TT Vaccines</span>
                                <div class="row g-2">
                                    @for($i=1; $i<=5; $i++)
                                    <div class="col-md"><label class="small text-muted mb-1">Td{{$i}} / TT{{$i}}</label><input type="date" name="td{{$i}}" class="form-control form-control-sm" value="{{ old("td{$i}", $pr->{"td{$i}"} ?? '') }}"></div>
                                    @endfor
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded border border-secondary border-opacity-10 mb-4">
                                <span class="d-block fw-bold text-dark mb-2"><i class="fas fa-pills text-danger me-2"></i>Iron with Folic Acid (IFA)</span>
                                <div class="row g-2 mb-3">
                                    @foreach(['1st visit' => 'v1', '2nd visit' => 'v2', '3rd visit' => 'v3', '4th visit' => 'v4', '5th visit' => 'v5', '6th visit' => 'v6'] as $label => $key)
                                    <div class="col-md-4 col-sm-6">
                                        <label class="small text-muted mb-1">{{ $label }}</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white">#</span>
                                            <input type="number" name="ifa_{{ $key }}_tablets" class="form-control" placeholder="Qty" value="{{ old('ifa_'.$key.'_tablets', $pr->{'ifa_'.$key.'_tablets'} ?? '') }}">
                                            <input type="date" name="ifa_{{ $key }}_date" class="form-control" value="{{ old('ifa_'.$key.'_date', $pr->{'ifa_'.$key.'_date'} ?? '') }}">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-flex align-items-center bg-white p-2 rounded border shadow-sm">
                                    <label class="small fw-bold mb-0 me-3">Completed IFA?</label>
                                    <input type="radio" name="ifa_completed" value="1" class="form-check-input me-1" {{ old('ifa_completed', $pr->ifa_completed ?? '0') == '1' ? 'checked' : '' }}><span class="small text-success fw-bold me-3">Yes</span>
                                    <input type="radio" name="ifa_completed" value="0" class="form-check-input me-1" {{ old('ifa_completed', $pr->ifa_completed ?? '0') == '0' ? 'checked' : '' }}><span class="small text-danger fw-bold me-3">No</span>
                                    <input type="date" name="ifa_completed_date" class="form-control form-control-sm ms-auto" style="max-width: 150px;" value="{{ old('ifa_completed_date', $pr->ifa_completed_date ?? '') }}">
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded border border-secondary border-opacity-10 mb-4">
                                <span class="d-block fw-bold text-dark mb-2"><i class="fas fa-capsules text-primary me-2"></i>Multiple Micronutrient Supplementation (MMS)</span>
                                <div class="row g-2 mb-3">
                                    @foreach(['1st visit' => 'v1', '2nd visit' => 'v2', '3rd visit' => 'v3', '4th visit' => 'v4', '5th visit' => 'v5', '6th visit' => 'v6'] as $label => $key)
                                    <div class="col-md-4 col-sm-6">
                                        <label class="small text-muted mb-1">{{ $label }}</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white">#</span>
                                            <input type="number" name="mms_{{ $key }}_tablets" class="form-control" placeholder="Qty" value="{{ old('mms_'.$key.'_tablets', $pr->{'mms_'.$key.'_tablets'} ?? '') }}">
                                            <input type="date" name="mms_{{ $key }}_date" class="form-control" value="{{ old('mms_'.$key.'_date', $pr->{'mms_'.$key.'_date'} ?? '') }}">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-flex align-items-center bg-white p-2 rounded border shadow-sm">
                                    <label class="small fw-bold mb-0 me-3">Completed MMS?</label>
                                    <input type="radio" name="mms_completed" value="1" class="form-check-input me-1" {{ old('mms_completed', $pr->mms_completed ?? '0') == '1' ? 'checked' : '' }}><span class="small text-success fw-bold me-3">Yes</span>
                                    <input type="radio" name="mms_completed" value="0" class="form-check-input me-1" {{ old('mms_completed', $pr->mms_completed ?? '0') == '0' ? 'checked' : '' }}><span class="small text-danger fw-bold me-3">No</span>
                                    <input type="date" name="mms_completed_date" class="form-control form-control-sm ms-auto" style="max-width: 150px;" value="{{ old('mms_completed_date', $pr->mms_completed_date ?? '') }}">
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-8">
                                    <div class="p-3 bg-light rounded border border-danger border-opacity-25 h-100">
                                        <span class="d-block fw-bold text-danger mb-2"><i class="fas fa-exclamation-triangle me-2"></i>HIGH RISK: Calcium Carbonate (CC)</span>
                                        <div class="row g-2 mb-3">
                                            @foreach(['2nd visit' => 'v2', '3rd visit' => 'v3', '4th visit' => 'v4'] as $label => $key)
                                            <div class="col-md-4">
                                                <label class="small text-muted mb-1">{{ $label }}</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-white">#</span>
                                                    <input type="number" name="cc_{{ $key }}_tablets" class="form-control" placeholder="Qty" value="{{ old('cc_'.$key.'_tablets', $pr->{'cc_'.$key.'_tablets'} ?? '') }}">
                                                    <input type="date" name="cc_{{ $key }}_date" class="form-control" value="{{ old('cc_'.$key.'_date', $pr->{'cc_'.$key.'_date'} ?? '') }}">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex align-items-center bg-white p-2 rounded border shadow-sm">
                                            <label class="small fw-bold mb-0 me-3">Completed CC?</label>
                                            <input type="radio" name="cc_completed" value="1" class="form-check-input me-1" {{ old('cc_completed', $pr->cc_completed ?? '0') == '1' ? 'checked' : '' }}><span class="small text-success fw-bold me-2">Yes</span>
                                            <input type="radio" name="cc_completed" value="0" class="form-check-input me-1" {{ old('cc_completed', $pr->cc_completed ?? '0') == '0' ? 'checked' : '' }}><span class="small text-danger fw-bold me-2">No</span>
                                            <input type="date" name="cc_completed_date" class="form-control form-control-sm ms-auto" style="max-width: 130px;" value="{{ old('cc_completed_date', $pr->cc_completed_date ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded border border-secondary border-opacity-10 h-100 d-flex flex-column justify-content-center align-items-center text-center">
                                        <label class="form-label fw-bold mb-3 text-dark">Received 1 dose Deworming?</label>
                                        <div>
                                            <input type="radio" name="deworming" id="deworm_yes" value="1" class="btn-check" {{ old('deworming', $pr->deworming ?? '0') == '1' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success btn-sm px-4 fw-bold me-2" for="deworm_yes">Yes (1)</label>
                                            
                                            <input type="radio" name="deworming" id="deworm_no" value="0" class="btn-check" {{ old('deworming', $pr->deworming ?? '0') == '0' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger btn-sm px-4 fw-bold" for="deworm_no">No (0)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="labs" role="tabpanel">
                            <h6 class="text-danger fw-bold border-bottom pb-2 mb-4">Laboratory Screenings</h6>
                            
                            <div class="row g-4">
                                @foreach([
                                    ['name' => 'Syphilis', 'field' => 'lab_syphilis', 'icon' => 'fa-vial'],
                                    ['name' => 'HIV', 'field' => 'lab_hiv', 'icon' => 'fa-ribbon'],
                                    ['name' => 'Hepatitis B', 'field' => 'lab_hepb', 'icon' => 'fa-vials']
                                ] as $lab)
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold text-dark mb-3"><i class="fas {{ $lab['icon'] }} text-primary me-2"></i>{{ $lab['name'] }}</h6>
                                            <label class="small text-muted mb-1">Date Tested</label>
                                            <input type="date" name="{{ $lab['field'] }}_date" class="form-control form-control-sm mb-2" value="{{ old($lab['field'].'_date', $pr->{$lab['field'].'_date'} ?? '') }}">
                                            <label class="small text-muted mb-1">Result</label>
                                            <select name="{{ $lab['field'] }}_result" class="form-select form-select-sm">
                                                <option value="">Select Result...</option>
                                                <option value="1" class="text-danger fw-bold" {{ old($lab['field'].'_result', $pr->{$lab['field'].'_result'} ?? '') == '1' ? 'selected' : '' }}>Positive</option>
                                                <option value="0" class="text-success fw-bold" {{ old($lab['field'].'_result', $pr->{$lab['field'].'_result'} ?? '') == '0' ? 'selected' : '' }}>Negative</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-tint text-danger me-2"></i>CBC / Hgb & Hct Count</h6>
                                            <div class="row">
                                                <div class="col-7">
                                                    <label class="small text-muted mb-1">Date Tested</label>
                                                    <input type="date" name="lab_cbc_date" class="form-control form-control-sm" value="{{ old('lab_cbc_date', $pr->lab_cbc_date ?? '') }}">
                                                </div>
                                                <div class="col-5">
                                                    <label class="small text-muted mb-1">Result</label>
                                                    <select name="lab_cbc_result" class="form-select form-select-sm">
                                                        <option value="">Select...</option>
                                                        <option value="1" class="text-danger" {{ old('lab_cbc_result', $pr->lab_cbc_result ?? '') == '1' ? 'selected' : '' }}>Anemic</option>
                                                        <option value="0" class="text-success" {{ old('lab_cbc_result', $pr->lab_cbc_result ?? '') == '0' ? 'selected' : '' }}>Normal</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-cube text-warning me-2"></i>Gestational Diabetes</h6>
                                            <div class="row">
                                                <div class="col-7">
                                                    <label class="small text-muted mb-1">Date Tested</label>
                                                    <input type="date" name="lab_gdm_date" class="form-control form-control-sm" value="{{ old('lab_gdm_date', $pr->lab_gdm_date ?? '') }}">
                                                </div>
                                                <div class="col-5">
                                                    <label class="small text-muted mb-1">Result</label>
                                                    <select name="lab_gdm_result" class="form-select form-select-sm">
                                                        <option value="">Select...</option>
                                                        <option value="1" class="text-danger" {{ old('lab_gdm_result', $pr->lab_gdm_result ?? '') == '1' ? 'selected' : '' }}>Positive</option>
                                                        <option value="0" class="text-success" {{ old('lab_gdm_result', $pr->lab_gdm_result ?? '') == '0' ? 'selected' : '' }}>Negative</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="delivery" role="tabpanel">
                            
                            <div class="row g-4">
                                <div class="col-lg-7">
                                    
                                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">Pregnancy Outcome</h6>
                                    <div class="p-3 bg-light rounded border shadow-sm mb-4">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">Date Terminated</label>
                                                <input type="date" name="outcome_date" class="form-control form-control-sm" value="{{ old('outcome_date', $pr->outcome_date ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">Outcome</label>
                                                <select name="outcome_type" class="form-select form-select-sm">
                                                    <option value="">Select...</option>
                                                    <option value="FT" {{ old('outcome_type', $pr->outcome_type ?? '') == 'FT' ? 'selected' : '' }}>Full term (FT)</option>
                                                    <option value="PT" {{ old('outcome_type', $pr->outcome_type ?? '') == 'PT' ? 'selected' : '' }}>Preterm (PT)</option>
                                                    <option value="FD" {{ old('outcome_type', $pr->outcome_type ?? '') == 'FD' ? 'selected' : '' }}>Fetal Death (FD)</option>
                                                    <option value="AB" {{ old('outcome_type', $pr->outcome_type ?? '') == 'AB' ? 'selected' : '' }}>Abortion/Miscarriage (AB)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">Delivery Type</label>
                                                <select name="delivery_type" class="form-select form-select-sm">
                                                    <option value="">Select...</option>
                                                    <option value="VD" {{ old('delivery_type', $pr->delivery_type ?? '') == 'VD' ? 'selected' : '' }}>Vaginal (VD)</option>
                                                    <option value="CS" {{ old('delivery_type', $pr->delivery_type ?? '') == 'CS' ? 'selected' : '' }}>Cesarean (CS)</option>
                                                    <option value="CVCD" {{ old('delivery_type', $pr->delivery_type ?? '') == 'CVCD' ? 'selected' : '' }}>Combined (CVCD)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">Birth Weight</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="birth_weight" class="form-control" placeholder="Grams" value="{{ old('birth_weight', $pr->birth_weight ?? '') }}">
                                                    <select name="birth_weight_category" class="form-select" style="max-width: 90px;">
                                                        <option value="">Cat...</option>
                                                        <option value="A" {{ old('birth_weight_category', $pr->birth_weight_category ?? '') == 'A' ? 'selected' : '' }}>Normal</option>
                                                        <option value="B" {{ old('birth_weight_category', $pr->birth_weight_category ?? '') == 'B' ? 'selected' : '' }}>Low</option>
                                                        <option value="C" {{ old('birth_weight_category', $pr->birth_weight_category ?? '') == 'C' ? 'selected' : '' }}>Unknown</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">Place & Time of Delivery</h6>
                                    <div class="p-3 bg-light rounded border shadow-sm mb-4">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">Health Facility</label>
                                                <select name="delivery_health_facility" class="form-select form-select-sm">
                                                    <option value="">Select Facility...</option>
                                                    <option value="BHS" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'BHS' ? 'selected' : '' }}>BHS</option>
                                                    <option value="RHU_UHU" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'RHU_UHU' ? 'selected' : '' }}>RHU/UHU</option>
                                                    <option value="Gov_Hosp" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'Gov_Hosp' ? 'selected' : '' }}>Government Hospital</option>
                                                    <option value="Pub_Infirm" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'Pub_Infirm' ? 'selected' : '' }}>Public Infirmary</option>
                                                    <option value="DOH_Ambulance" {{ old('delivery_health_facility', $pr->delivery_health_facility ?? '') == 'DOH_Ambulance' ? 'selected' : '' }}>Ambulance</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">Facility Type</label>
                                                <select name="delivery_facility_type" class="form-select form-select-sm">
                                                    <option value="">Select...</option>
                                                    <option value="Public" {{ old('delivery_facility_type', $pr->delivery_facility_type ?? '') == 'Public' ? 'selected' : '' }}>Public</option>
                                                    <option value="Private" {{ old('delivery_facility_type', $pr->delivery_facility_type ?? '') == 'Private' ? 'selected' : '' }}>Private</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">Non-Health Facility</label>
                                                <select name="delivery_non_health_facility" class="form-select form-select-sm">
                                                    <option value="">Select...</option>
                                                    <option value="1" {{ old('delivery_non_health_facility', $pr->delivery_non_health_facility ?? '') == '1' ? 'selected' : '' }}>1 - Home</option>
                                                    <option value="2" {{ old('delivery_non_health_facility', $pr->delivery_non_health_facility ?? '') == '2' ? 'selected' : '' }}>2 - Others (inc. emergency)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center mt-4">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="bemonc_cemonc" id="bemonc_cemonc" value="1" {{ old('bemonc_cemonc', $pr->bemonc_cemonc ?? '0') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-bold mt-1 ms-2" for="bemonc_cemonc">BEmONC/CEmONC Capable</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">Birth Attendant</label>
                                                <select name="birth_attendant" class="form-select form-select-sm">
                                                    <option value="">Select...</option>
                                                    <option value="MD" {{ old('birth_attendant', $pr->birth_attendant ?? '') == 'MD' ? 'selected' : '' }}>Doctor (MD)</option>
                                                    <option value="RN" {{ old('birth_attendant', $pr->birth_attendant ?? '') == 'RN' ? 'selected' : '' }}>Nurse (RN)</option>
                                                    <option value="MW" {{ old('birth_attendant', $pr->birth_attendant ?? '') == 'MW' ? 'selected' : '' }}>Midwife (MW)</option>
                                                    <option value="O" {{ old('birth_attendant', $pr->birth_attendant ?? '') == 'O' ? 'selected' : '' }}>Others</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted">If Others, Specify:</label>
                                                <input type="text" name="birth_attendant_others" class="form-control form-control-sm" value="{{ old('birth_attendant_others', $pr->birth_attendant_others ?? '') }}">
                                            </div>

                                            <div class="col-md-6 border-top pt-2 mt-3">
                                                <label class="small fw-bold text-muted">Actual Date of Delivery</label>
                                                <input type="date" name="delivery_date_actual" class="form-control form-control-sm" value="{{ old('delivery_date_actual', $pr->delivery_date_actual ?? '') }}">
                                            </div>
                                            <div class="col-md-6 border-top pt-2 mt-3">
                                                <label class="small fw-bold text-muted">Actual Time</label>
                                                <input type="time" name="delivery_time_actual" class="form-control form-control-sm" value="{{ old('delivery_time_actual', $pr->delivery_time_actual ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">Postpartum Care (4PNC)</h6>
                                    
                                    <div class="row g-2 mb-3">
                                        @foreach([
                                            ['name' => 'Contact 1', 'desc' => 'Within 24 hrs', 'field' => 'pnc_contact_1'],
                                            ['name' => 'Contact 2', 'desc' => 'Day 3', 'field' => 'pnc_contact_2'],
                                            ['name' => 'Contact 3', 'desc' => '7-14 days', 'field' => 'pnc_contact_3'],
                                            ['name' => 'Contact 4', 'desc' => '6 weeks', 'field' => 'pnc_contact_4']
                                        ] as $pnc)
                                        <div class="col-6">
                                            <label class="small text-muted mb-1 fw-bold">{{ $pnc['name'] }} <span class="fw-normal fst-italic">({{ $pnc['desc'] }})</span></label>
                                            <input type="date" name="{{ $pnc['field'] }}" class="form-control form-control-sm" value="{{ old($pnc['field'], $pr->{$pnc['field']} ?? '') }}">
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="d-flex align-items-center bg-light p-2 rounded border shadow-sm mb-4">
                                        <label class="small fw-bold text-dark mb-0 me-3">Completed 4PNC?</label>
                                        <input type="radio" name="completed_4pnc" id="pnc_yes" value="1" class="form-check-input me-1" {{ old('completed_4pnc', $pr->completed_4pnc ?? '0') == '1' ? 'checked' : '' }}><label class="small text-success fw-bold me-3" for="pnc_yes">Yes</label>
                                        <input type="radio" name="completed_4pnc" id="pnc_no" value="0" class="form-check-input me-1" {{ old('completed_4pnc', $pr->completed_4pnc ?? '0') == '0' ? 'checked' : '' }}><label class="small text-danger fw-bold" for="pnc_no">No</label>
                                    </div>

                                    <div class="p-3 bg-light rounded border border-secondary border-opacity-10 mb-3">
                                        <span class="d-block fw-bold text-dark mb-2"><i class="fas fa-pills text-danger me-2"></i>PP IFA Supplementation</span>
                                        <div class="row g-2 mb-3">
                                            @foreach(['1st visit' => 'v1', '2nd visit' => 'v2', '3rd visit' => 'v3'] as $label => $key)
                                            <div class="col-12">
                                                <label class="small text-muted mb-1">{{ $label }}</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-white">#</span>
                                                    <input type="number" name="pp_ifa_{{ $key }}_tablets" class="form-control" placeholder="Qty" value="{{ old('pp_ifa_'.$key.'_tablets', $pr->{'pp_ifa_'.$key.'_tablets'} ?? '') }}">
                                                    <input type="date" name="pp_ifa_{{ $key }}_date" class="form-control" value="{{ old('pp_ifa_'.$key.'_date', $pr->{'pp_ifa_'.$key.'_date'} ?? '') }}">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex align-items-center bg-white p-2 rounded border shadow-sm">
                                            <label class="small fw-bold mb-0 me-3">Completed PP IFA?</label>
                                            <input type="radio" name="pp_ifa_completed" value="1" class="form-check-input me-1" {{ old('pp_ifa_completed', $pr->pp_ifa_completed ?? '0') == '1' ? 'checked' : '' }}><span class="small text-success fw-bold me-2">Yes</span>
                                            <input type="radio" name="pp_ifa_completed" value="0" class="form-check-input me-1" {{ old('pp_ifa_completed', $pr->pp_ifa_completed ?? '0') == '0' ? 'checked' : '' }}><span class="small text-danger fw-bold me-2">No</span>
                                            <input type="date" name="pp_ifa_completed_date" class="form-control form-control-sm ms-auto" style="max-width: 130px;" value="{{ old('pp_ifa_completed_date', $pr->pp_ifa_completed_date ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="p-3 bg-light rounded border border-secondary border-opacity-10 d-flex flex-column justify-content-between">
                                        <div>
                                            <span class="d-block fw-bold text-dark mb-2"><i class="fas fa-capsules text-warning me-2"></i>Vit. A Supplementation</span>
                                            <div class="d-flex align-items-center bg-white p-2 rounded border shadow-sm mb-3">
                                                <label class="small fw-bold mb-0 me-3">Completed Vit. A?</label>
                                                <input type="radio" name="pp_vita_completed" id="vita_yes" value="1" class="form-check-input me-1" {{ old('pp_vita_completed', $pr->pp_vita_completed ?? '0') == '1' ? 'checked' : '' }}><label class="small text-success fw-bold me-2" for="vita_yes">Yes</label>
                                                <input type="radio" name="pp_vita_completed" id="vita_no" value="0" class="form-check-input me-1" {{ old('pp_vita_completed', $pr->pp_vita_completed ?? '0') == '0' ? 'checked' : '' }}><label class="small text-danger fw-bold me-2" for="vita_no">No</label>
                                                <input type="date" name="pp_vita_completed_date" class="form-control form-control-sm ms-auto" style="max-width: 130px;" value="{{ old('pp_vita_completed_date', $pr->pp_vita_completed_date ?? '') }}">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="small fw-bold text-muted mb-1">Postpartum Remarks</label>
                                            <select name="postpartum_remarks" class="form-select form-select-sm">
                                                <option value="">None...</option>
                                                <option value="A" {{ old('postpartum_remarks', $pr->postpartum_remarks ?? '') == 'A' ? 'selected' : '' }}>A - Trans in</option>
                                                <option value="B" {{ old('postpartum_remarks', $pr->postpartum_remarks ?? '') == 'B' ? 'selected' : '' }}>B - Trans Out</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="modal-footer bg-light border-top p-3 d-flex justify-content-between">
                        <span class="text-muted small"><i class="fas fa-info-circle me-1"></i>Ensure all fields are accurate before saving.</span>
                        <div>
                            <button type="button" class="btn btn-secondary rounded-pill px-4 me-2 shadow-sm" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#bookNextVisitModal">
                                <i class="fas fa-save me-2"></i>Save Pregnancy Record
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bookNextVisitModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title fw-bold"><i class="fas fa-calendar-plus me-2"></i>Book Next Visit?</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-white text-center">
                <p class="text-muted small mb-4">
                    Would you like to go to the Clinic Calendar to book the patient's next visit after saving?
                </p>

                <div class="d-flex flex-column gap-2 mt-2">
                    <button type="button" class="btn btn-primary fw-bold rounded-pill shadow-sm" onclick="submitAndRedirect()">
                        <i class="fas fa-calendar-alt me-2"></i>Yes, Save & Go to Calendar
                    </button>
                    <button type="button" class="btn btn-light border fw-bold rounded-pill text-secondary" onclick="submitOnly()">
                        No, Just Save Record
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Cascading Logic for BANC, Vaccines, etc. ---
        function setupSequentialGroup(form, config) {
            const resolvedSteps = config.steps.map(stepNames => {
                return stepNames.map(name => form.querySelector('input[name="' + name + '"]')).filter(el => el !== null);
            });

            if (resolvedSteps.length === 0 || resolvedSteps[0].length === 0) return null;

            let radioYes = null;
            let radioNo = null;
            let dateCompleted = null;

            if (config.completionRadio) {
                radioYes = form.querySelector('input[name="' + config.completionRadio + '"][value="1"]');
                radioNo = form.querySelector('input[name="' + config.completionRadio + '"][value="0"]');
            }
            if (config.completionDate) {
                dateCompleted = form.querySelector('input[name="' + config.completionDate + '"]');
            }

            function updateStates() {
                let foundEmpty = false;

                for (let i = 0; i < resolvedSteps.length; i++) {
                    const stepInputs = resolvedSteps[i];
                    if (stepInputs.length === 0) continue;

                    if (foundEmpty) {
                        stepInputs.forEach(input => {
                            input.readOnly = true;
                            input.setAttribute('tabindex', '-1');
                            input.style.pointerEvents = 'none';
                            input.style.opacity = '0.5';
                            input.value = ''; 
                        });
                    } else {
                        let stepIsEmpty = false;
                        stepInputs.forEach(input => {
                            input.readOnly = false;
                            input.removeAttribute('tabindex');
                            input.style.pointerEvents = 'auto';
                            input.style.opacity = '1';
                            
                            if (input.value === '') {
                                stepIsEmpty = true;
                            }
                        });

                        if (stepIsEmpty) {
                            foundEmpty = true;
                        }
                    }
                }

                if (radioYes && radioNo) {
                    if (foundEmpty) {
                        radioYes.disabled = true;
                        radioYes.parentElement.style.opacity = '0.4'; 
                        radioNo.checked = true; 
                    } else {
                        radioYes.disabled = false;
                        radioYes.parentElement.style.opacity = '1';
                    }
                }

                if (radioYes && radioNo && dateCompleted) {
                    if (radioYes.checked && !radioYes.disabled) {
                        dateCompleted.readOnly = false;
                        dateCompleted.style.pointerEvents = 'auto';
                        dateCompleted.style.opacity = '1';
                    } else {
                        dateCompleted.readOnly = true;
                        dateCompleted.style.pointerEvents = 'none';
                        dateCompleted.style.opacity = '0.5';
                        dateCompleted.value = ''; 
                    }
                }
            }

            updateStates();

            resolvedSteps.forEach(stepInputs => {
                stepInputs.forEach(input => {
                    input.addEventListener('change', updateStates);
                    input.addEventListener('input', updateStates);
                });
            });

            if (radioYes && radioNo) {
                radioYes.addEventListener('change', updateStates);
                radioNo.addEventListener('change', updateStates);
            }

            return updateStates;
        }

        function setupAllSequences(form) {
            if (form.dataset.sequencesSetup === "true") return;
            form.dataset.sequencesSetup = "true";

            const sequences = [
                { steps: [['visit_1'], ['visit_2'], ['visit_3'], ['visit_4'], ['visit_5'], ['visit_6'], ['visit_7'], ['visit_8']], completionRadio: 'completed_8anc' },
                { steps: [['td1'], ['td2'], ['td3'], ['td4'], ['td5']] },
                { steps: [['ifa_v1_tablets', 'ifa_v1_date'], ['ifa_v2_tablets', 'ifa_v2_date'], ['ifa_v3_tablets', 'ifa_v3_date'], ['ifa_v4_tablets', 'ifa_v4_date'], ['ifa_v5_tablets', 'ifa_v5_date'], ['ifa_v6_tablets', 'ifa_v6_date']], completionRadio: 'ifa_completed', completionDate: 'ifa_completed_date' },
                { steps: [['mms_v1_tablets', 'mms_v1_date'], ['mms_v2_tablets', 'mms_v2_date'], ['mms_v3_tablets', 'mms_v3_date'], ['mms_v4_tablets', 'mms_v4_date'], ['mms_v5_tablets', 'mms_v5_date'], ['mms_v6_tablets', 'mms_v6_date']], completionRadio: 'mms_completed', completionDate: 'mms_completed_date' },
                { steps: [['cc_v2_tablets', 'cc_v2_date'], ['cc_v3_tablets', 'cc_v3_date'], ['cc_v4_tablets', 'cc_v4_date']], completionRadio: 'cc_completed', completionDate: 'cc_completed_date' },
                { steps: [['pnc_contact_1'], ['pnc_contact_2'], ['pnc_contact_3'], ['pnc_contact_4']], completionRadio: 'completed_4pnc' },
                { steps: [['pp_ifa_v1_tablets', 'pp_ifa_v1_date'], ['pp_ifa_v2_tablets', 'pp_ifa_v2_date'], ['pp_ifa_v3_tablets', 'pp_ifa_v3_date']], completionRadio: 'pp_ifa_completed', completionDate: 'pp_ifa_completed_date' }
            ];

            const updaters = [];
            sequences.forEach(config => {
                const updater = setupSequentialGroup(form, config);
                if (updater) updaters.push(updater);
            });

            const vitaYes = form.querySelector('input[name="pp_vita_completed"][value="1"]');
            const vitaNo = form.querySelector('input[name="pp_vita_completed"][value="0"]');
            const vitaDate = form.querySelector('input[name="pp_vita_completed_date"]');
            if (vitaYes && vitaNo && vitaDate) {
                const toggleVitaDate = () => {
                    if (vitaYes.checked) {
                        vitaDate.readOnly = false;
                        vitaDate.style.pointerEvents = 'auto';
                        vitaDate.style.opacity = '1';
                    } else {
                        vitaDate.readOnly = true;
                        vitaDate.style.pointerEvents = 'none';
                        vitaDate.style.opacity = '0.5';
                        vitaDate.value = '';
                    }
                };
                vitaYes.addEventListener('change', toggleVitaDate);
                vitaNo.addEventListener('change', toggleVitaDate);
                updaters.push(toggleVitaDate);
            }

            form.updateAllSequences = function() {
                updaters.forEach(updater => updater());
            };
        }

        document.querySelectorAll('input[name="visit_1"]').forEach(function(firstVisitInput) {
            const form = firstVisitInput.closest('form');
            if (form) setupAllSequences(form);
        });

        document.addEventListener('shown.bs.modal', function (event) {
            const modal = event.target;
            const firstVisitInput = modal.querySelector('input[name="visit_1"]');
            if (firstVisitInput) {
                const form = firstVisitInput.closest('form');
                if (form) {
                    setupAllSequences(form);
                    if (typeof form.updateAllSequences === 'function') {
                        form.updateAllSequences();
                    }
                }
            }
        });

    });

    // --- SUBMISSION AND REDIRECTION LOGIC ---
    function submitAndRedirect() {
        // Mark that we want to redirect to calendar after save
        document.getElementById('redirect_to_calendar').value = '1';
        document.getElementById('pregnancyForm').submit();
    }

    function submitOnly() {
        // Keep the user on the patient profile page after save
        document.getElementById('redirect_to_calendar').value = '0';
        document.getElementById('pregnancyForm').submit();
    }
</script>