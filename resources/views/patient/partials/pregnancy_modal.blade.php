@if($user->has_pregnancy_record && $user->pregnancyRecord)
@php $pr = $user->pregnancyRecord; @endphp
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-baby me-2"></i>Pregnancy Record - {{ $user->first_name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                
                {{-- Key Dates --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="bg-white p-3 rounded shadow-sm border border-danger border-opacity-25 h-100 text-center">
                            <span class="d-block small text-muted text-uppercase fw-bold mb-1">Expected Delivery (EDD)</span>
                            <h5 class="fw-bold text-danger mb-0">{{ $pr->edd ? \Carbon\Carbon::parse($pr->edd)->format('M d, Y') : 'Not Set' }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-3 rounded shadow-sm border h-100 text-center">
                            <span class="d-block small text-muted text-uppercase fw-bold mb-1">Last Menstrual Period</span>
                            <h6 class="fw-bold text-dark mb-0">{{ $pr->lmp ? \Carbon\Carbon::parse($pr->lmp)->format('M d, Y') : 'Not Set' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-3 rounded shadow-sm border h-100 text-center">
                            <span class="d-block small text-muted text-uppercase fw-bold mb-1">Gravida Parity</span>
                            <h6 class="fw-bold text-dark mb-0">{{ $pr->gravida_parity ?: 'N/A' }}</h6>
                        </div>
                    </div>
                </div>

                {{-- Prenatal Checkups --}}
                <h6 class="fw-bold text-danger mb-3 border-bottom pb-2">Prenatal Check-up Schedule</h6>
                <div class="bg-white p-3 rounded shadow-sm border mb-4">
                    <div class="row text-center g-2">
                        <div class="col-3 border-end">
                            <small class="text-muted d-block">Visit 1</small>
                            <span class="fw-bold {{ $pr->visit_1 ? 'text-success' : 'text-danger' }}">
                                {!! $pr->visit_1 ? '<i class="fas fa-check-circle"></i> '.\Carbon\Carbon::parse($pr->visit_1)->format('M d') : 'Pending' !!}
                            </span>
                        </div>
                        <div class="col-3 border-end">
                            <small class="text-muted d-block">Visit 2</small>
                            <span class="fw-bold {{ $pr->visit_2 ? 'text-success' : 'text-danger' }}">
                                {!! $pr->visit_2 ? '<i class="fas fa-check-circle"></i> '.\Carbon\Carbon::parse($pr->visit_2)->format('M d') : 'Pending' !!}
                            </span>
                        </div>
                        <div class="col-3 border-end">
                            <small class="text-muted d-block">Visit 3</small>
                            <span class="fw-bold {{ $pr->visit_3 ? 'text-success' : 'text-danger' }}">
                                {!! $pr->visit_3 ? '<i class="fas fa-check-circle"></i> '.\Carbon\Carbon::parse($pr->visit_3)->format('M d') : 'Pending' !!}
                            </span>
                        </div>
                        <div class="col-3">
                            <small class="text-muted d-block">Visit 4</small>
                            <span class="fw-bold {{ $pr->visit_4 ? 'text-success' : 'text-danger' }}">
                                {!! $pr->visit_4 ? '<i class="fas fa-check-circle"></i> '.\Carbon\Carbon::parse($pr->visit_4)->format('M d') : 'Pending' !!}
                            </span>
                        </div>
                    </div>
                    <hr class="opacity-10 my-2">
                    <div class="row text-center g-2">
                        <div class="col-3 border-end">
                            <small class="text-muted d-block">Visit 5</small>
                            <span class="fw-bold {{ $pr->visit_5 ? 'text-success' : 'text-danger' }}">
                                {!! $pr->visit_5 ? '<i class="fas fa-check-circle"></i> '.\Carbon\Carbon::parse($pr->visit_5)->format('M d') : 'Pending' !!}
                            </span>
                        </div>
                        <div class="col-3 border-end">
                            <small class="text-muted d-block">Visit 6</small>
                            <span class="fw-bold {{ $pr->visit_6 ? 'text-success' : 'text-danger' }}">
                                {!! $pr->visit_6 ? '<i class="fas fa-check-circle"></i> '.\Carbon\Carbon::parse($pr->visit_6)->format('M d') : 'Pending' !!}
                            </span>
                        </div>
                        <div class="col-3 border-end">
                            <small class="text-muted d-block">Visit 7</small>
                            <span class="fw-bold {{ $pr->visit_7 ? 'text-success' : 'text-danger' }}">
                                {!! $pr->visit_7 ? '<i class="fas fa-check-circle"></i> '.\Carbon\Carbon::parse($pr->visit_7)->format('M d') : 'Pending' !!}
                            </span>
                        </div>
                        <div class="col-3">
                            <small class="text-muted d-block">Visit 8</small>
                            <span class="fw-bold {{ $pr->visit_8 ? 'text-success' : 'text-danger' }}">
                                {!! $pr->visit_8 ? '<i class="fas fa-check-circle"></i> '.\Carbon\Carbon::parse($pr->visit_8)->format('M d') : 'Pending' !!}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    {{-- Supplements --}}
                    <div class="col-md-6">
                        <h6 class="fw-bold text-danger mb-3 border-bottom pb-2">Supplementation Progress</h6>
                        <div class="bg-white p-3 rounded shadow-sm border h-100">
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="fas {{ $pr->ifa_completed ? 'fa-check-circle text-success' : 'fa-clock text-warning' }} me-2"></i> 
                                    IFA Supplementation: <strong>{{ $pr->ifa_completed ? 'Completed' : 'Ongoing' }}</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas {{ $pr->mms_completed ? 'fa-check-circle text-success' : 'fa-clock text-warning' }} me-2"></i> 
                                    MMS Supplementation: <strong>{{ $pr->mms_completed ? 'Completed' : 'Ongoing' }}</strong>
                                </li>
                                @if($pr->cc_completed)
                                <li>
                                    <i class="fas fa-check-circle text-success me-2"></i> 
                                    Calcium Supplementation: <strong>Completed</strong>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    {{-- Td Vaccines --}}
                    <div class="col-md-6">
                        <h6 class="fw-bold text-danger mb-3 border-bottom pb-2">Tetanus Diphtheria (Td)</h6>
                        <div class="bg-white p-3 rounded shadow-sm border h-100">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Td1'=>'td1', 'Td2'=>'td2', 'Td3'=>'td3', 'Td4'=>'td4', 'Td5'=>'td5'] as $label => $key)
                                    <span class="badge {{ $pr->$key ? 'bg-success' : 'bg-light text-muted border' }}">
                                        {{ $label }} {!! $pr->$key ? '<br>'.\Carbon\Carbon::parse($pr->$key)->format('M d, y') : '' !!}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delivery Info (Only show if delivered) --}}
                @if($pr->outcome_date)
                    <h6 class="fw-bold text-danger mb-3 border-bottom pb-2">Delivery & Postnatal Info</h6>
                    <div class="bg-white p-3 rounded shadow-sm border">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Delivery Date</small>
                                <span class="fw-bold">{{ \Carbon\Carbon::parse($pr->delivery_date_actual ?? $pr->outcome_date)->format('F d, Y') }}</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Birth Weight</small>
                                <span class="fw-bold">{{ $pr->birth_weight ? $pr->birth_weight.' grams' : 'N/A' }}</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Completed 4PNC</small>
                                <span class="fw-bold {{ $pr->completed_4pnc ? 'text-success' : 'text-warning' }}">
                                    {{ $pr->completed_4pnc ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif