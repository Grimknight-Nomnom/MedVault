@extends('layouts.app')

@section('content')
<style>
    /* --- CALENDAR GRID STYLES --- */
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
    .calendar-header { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; text-align: center; font-weight: bold; color: #6c757d; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 10px; }

    .day-cell { 
        min-height: 120px; 
        border: 1px solid #dee2e6; 
        border-radius: 8px; 
        padding: 8px; 
        position: relative; 
        transition: all 0.2s ease; 
        background-color: #fff; 
        cursor: pointer; 
        display: flex; 
        flex-direction: column; 
        justify-content: space-between;
    }
    .day-cell:hover { transform: translateY(-3px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-color: var(--bs-primary) !important; z-index: 2; }

    @media (max-width: 767.98px) {
        .calendar-header { display: none; }
        .calendar-grid { display: flex; flex-direction: column; gap: 10px; }
        .day-cell { min-height: auto; height: auto; padding: 15px; flex-direction: row; align-items: center; }
        .day-cell.empty-slot { display: none; }
    }

    /* Fixed Colors */
    .bg-area { background-color: #dcfce7; color: #166534; }       
    .bg-pregnancy { background-color: #fce7f3; color: #9d174d; }  
    .bg-immunization { background-color: #e0f2fe; color: #075985; } 
    .bg-special { background-color: #fef9c3; color: #854d0e; }    
    .bg-normal { background-color: #ffffff; color: #333; }        
</style>

<div class="container py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <h2 class="fw-bold text-primary mb-0 w-100 text-center text-md-start">Clinic Calendar</h2>

        <div class="d-flex flex-column flex-md-row align-items-center gap-3 w-100 w-md-auto">
            <div class="btn-group shadow-sm w-100 w-md-auto">
                <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm flex-fill flex-md-grow-0">&larr; Prev</a>
                <span class="btn btn-outline-secondary disabled fw-bold text-dark px-3 bg-white flex-fill flex-md-grow-0">{{ $date->format('F Y') }}</span>
                <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm flex-fill flex-md-grow-0">Next &rarr;</a>
            </div>
            
            <div class="d-flex gap-2 w-100 w-md-auto">
                <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-center shadow-sm flex-fill flex-md-grow-0 text-nowrap" data-bs-toggle="modal" data-bs-target="#bulkSettingsModal">
                    <i class="fas fa-layer-group me-2"></i> Bulk Settings
                </button>
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary d-flex align-items-center justify-content-center shadow-sm flex-fill flex-md-grow-0 text-nowrap">
                    <i class="fas fa-plus-circle me-2"></i> Book Patient
                </a>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 small mb-4 justify-content-center justify-content-md-end flex-wrap">
        <span class="badge bg-white text-dark border">Normal Check-up</span>
        <span class="badge bg-area">Area</span>
        <span class="badge bg-pregnancy">Pregnancy</span>
        <span class="badge bg-immunization">Immunization</span>
        <span class="badge bg-special">Special/Custom</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="calendar-header d-none d-md-grid">
                <div class="text-danger">SUN</div><div class="text-secondary">MON</div><div class="text-secondary">TUE</div><div class="text-secondary">WED</div><div class="text-secondary">THU</div><div class="text-secondary">FRI</div><div class="text-danger">SAT</div>
            </div>

            <div class="calendar-grid">
                @php
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth = $date->copy()->endOfMonth();
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                @endphp

                @for ($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="p-4 bg-light rounded border border-light d-none d-md-block"></div>
                @endfor

                @for ($day = 1; $day <= $endOfMonth->day; $day++)
                    @php
                        $currentDate = $startOfMonth->copy()->setDay($day);
                        $dayString = $currentDate->format('Y-m-d');
                        $dayOfWeek = $currentDate->dayOfWeek;
                        $weekdayName = $currentDate->format('l');

                        $dayAppointments = $appointmentsByDate->get($dayString, collect());
                        $count = $dayAppointments->count();
                        
                        $isToday = $dayString == now()->format('Y-m-d');
                        $isPast = $dayString < now()->format('Y-m-d');
                        
                        $setting = $settings->get($dayString);
                        $limit = $setting ? $setting->max_appointments : 30;
                        $customLabel = $setting ? $setting->label : null;
                        
                        // 1. Determine Default Label
                        $labelText = 'Check-up';
                        if ($dayOfWeek == 0 || $dayOfWeek == 6) { $labelText = 'Area'; } 
                        elseif ($dayOfWeek == 2 || $dayOfWeek == 4) { $labelText = 'Pregnancy'; } 
                        elseif ($dayOfWeek == 3) { $labelText = 'Immunization'; }

                        // 2. Override with custom label if set
                        if ($customLabel) {
                            $labelText = $customLabel;
                        }

                        // 3. Smart Color Assignment
                        $lowerLabel = strtolower($labelText);
                        $bgClass = 'bg-special'; // Default to yellow for random strings

                        if (str_contains($lowerLabel, 'pregnancy')) { 
                            $bgClass = 'bg-pregnancy'; 
                        } elseif (str_contains($lowerLabel, 'immunization')) { 
                            $bgClass = 'bg-immunization'; 
                        } elseif (str_contains($lowerLabel, 'area')) { 
                            $bgClass = 'bg-area'; 
                        } elseif (str_contains($lowerLabel, 'check-up') || $lowerLabel === 'normal check-up') { 
                            $bgClass = 'bg-normal'; 
                        }
                    @endphp

                    <div onclick="openDayModal('{{ $dayString }}', '{{ $labelText }}')" class="day-cell {{ $bgClass }}">
                        <div class="d-flex d-md-none justify-content-between align-items-center w-100">
                            <div class="d-flex flex-column align-items-start">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold fs-3 text-dark lh-1">{{ $day }}</span>
                                    @if($isToday) <span class="badge bg-danger text-uppercase" style="font-size: 0.65rem;">Today</span> @endif
                                </div>
                                <span class="text-uppercase text-muted fw-bold small mt-1">{{ $weekdayName }}</span>
                                @if($count > 0)
                                    <span class="badge bg-primary rounded-pill mt-2 shadow-sm">{{ $count }} Patient{{ $count > 1 ? 's' : '' }}</span>
                                @endif
                            </div>
                            <div class="d-flex flex-column align-items-end gap-2">
                                @if(!$isPast)
                                <button class="btn btn-light btn-sm border rounded-circle shadow-sm" 
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;"
                                        onclick="event.stopPropagation(); editDaySettings('{{ $dayString }}', {{ $limit }}, '{{ $customLabel ?? '' }}')">
                                    <i class="fas fa-cog text-secondary"></i>
                                </button>
                                @endif
                                <span class="badge bg-white text-dark border shadow-sm">{{ $labelText }}</span>
                            </div>
                        </div>

                        <div class="d-none d-md-flex flex-column justify-content-between h-100 w-100">
                            <div class="d-flex justify-content-between align-items-start w-100">
                                <span class="fw-bold fs-5">{{ $day }} @if($isToday) <span class="badge bg-danger ms-1" style="font-size: 0.5rem;">TODAY</span> @endif</span>
                                
                                @if(!$isPast)
                                <button class="btn btn-link p-0 text-secondary opacity-50 hover-opacity-100" 
                                        onclick="event.stopPropagation(); editDaySettings('{{ $dayString }}', {{ $limit }}, '{{ $customLabel ?? '' }}')">
                                    <i class="fas fa-cog"></i>
                                </button>
                                @endif
                            </div>
                            <div class="mt-auto text-end w-100">
                                <span class="day-label opacity-75 mb-1" title="{{ $labelText }}">{{ $labelText }}</span>
                                @if($count > 0)
                                    <span class="badge bg-primary rounded-pill d-block">{{ $count }} Patient{{ $count > 1 ? 's' : '' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

{{-- MODALS --}}

{{-- 1. INDIVIDUAL SETTINGS MODAL --}}
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-light py-2">
                <h6 class="modal-title fw-bold">Edit Day Settings</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.appointments.limit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="date" id="settingDateInput">
                    <div class="text-center mb-3">
                        <span id="settingDateDisplay" class="fw-bold text-primary"></span>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Day Label</label>
                        <input type="text" name="label" id="settingLabelInput" class="form-control form-control-sm" placeholder="e.g. Special Checkup">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Max Appointments</label>
                        <input type="number" name="limit" id="settingLimitInput" class="form-control form-control-sm" min="0" max="200" required>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. BULK SETTINGS MODAL --}}
<div class="modal fade" id="bulkSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-layer-group me-2"></i>Bulk Update Calendar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.appointments.bulk_limit') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info small mb-4 border-0 border-start border-4 border-info">
                        <i class="fas fa-info-circle me-1"></i> This applies changes to the selected day of the week for the next <strong>12 months</strong>.
                        Days that already have <strong>existing patient appointments</strong> will be automatically skipped.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Day of the Week</label>
                        <select name="day_of_week" class="form-select border-primary shadow-none" required>
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                            <option value="0">Sunday</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Set Program / Label</label>
                        <select name="label" class="form-select border-primary shadow-none mb-2" onchange="toggleBulkCustom(this.value)">
                            <option value="Normal Check-up">Normal Check-up</option>
                            <option value="Area">Area</option>
                            <option value="Pregnancy">Pregnancy</option>
                            <option value="Immunization">Immunization</option>
                            <option value="Custom">Custom Label...</option>
                            <option value="">Reset to Default Day Program</option>
                        </select>
                        <input type="text" name="custom_label" id="bulkCustomLabel" class="form-control d-none shadow-none" placeholder="Type custom name here...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Max Appointments Limit</label>
                        <input type="number" name="limit" class="form-control border-primary shadow-none" min="0" max="200" value="30" required>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Apply Bulk Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3. DAY DETAILS / DIAGNOSE MODAL --}}
<div class="modal fade" id="dayDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalDateTitle">Appointments</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light"><tr><th class="ps-4">Queue</th><th>Patient</th><th>Status</th><th class="text-end pe-4">Actions</th></tr></thead>
                        <tbody id="modalTableBody"></tbody>
                    </table>
                </div>
                <div id="emptyState" class="text-center py-5 d-none"><p class="text-muted mb-0">No appointments found.</p></div>
            </div>
        </div>
    </div>
</div>

<script>
    const allAppointments = @json($appointments);

    function toggleBulkCustom(val) {
        const customInput = document.getElementById('bulkCustomLabel');
        if (val === 'Custom') {
            customInput.classList.remove('d-none');
            customInput.setAttribute('required', 'required');
        } else {
            customInput.classList.add('d-none');
            customInput.removeAttribute('required');
            customInput.value = '';
        }
    }

    function editDaySettings(date, currentLimit, currentLabel) {
        document.getElementById('settingDateInput').value = date;
        document.getElementById('settingDateDisplay').innerText = date;
        document.getElementById('settingLimitInput').value = currentLimit;
        document.getElementById('settingLabelInput').value = currentLabel;
        new bootstrap.Modal(document.getElementById('settingsModal')).show();
    }

    // UPDATED FUNCTION: Added explicit FontAwesome icons to all buttons!
    function openDayModal(dateString, dayLabel) {
        const dayAppointments = allAppointments.filter(app => app.calendar_date === dateString);
        dayAppointments.sort((a, b) => a.queue_number - b.queue_number);
        
        const dateObj = new Date(dateString + 'T00:00:00'); 
        document.getElementById('modalDateTitle').innerText = dateObj.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
        const tbody = document.getElementById('modalTableBody');
        tbody.innerHTML = '';
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const isPastDate = dateObj < today;
        
        // CHECK IF IT IS AN IMMUNIZATION OR PREGNANCY DAY
        const lowerLabel = (dayLabel || '').toLowerCase();
        const isImmunizationDay = lowerLabel.includes('immunization');
        const isPregnancyDay = lowerLabel.includes('pregnancy');
        const isSpecialRecordDay = isImmunizationDay || isPregnancyDay;
        
        if (dayAppointments.length === 0) {
            document.getElementById('emptyState').classList.remove('d-none');
        } else {
            document.getElementById('emptyState').classList.add('d-none');
            dayAppointments.forEach(app => {
                
                let displayStatus = app.status;
                if (isPastDate && (app.status === 'pending' || app.status === 'approved')) {
                    displayStatus = 'incomplete';
                }

                let statusBadge = displayStatus === 'approved' ? '<span class="badge bg-primary">Approved</span>' : 
                                  (displayStatus === 'completed' ? '<span class="badge bg-success">Completed</span>' : 
                                  (displayStatus === 'incomplete' ? '<span class="badge bg-secondary">Incomplete</span>' : 
                                  (displayStatus === 'cancelled' ? '<span class="badge bg-danger">Cancelled</span>' : 
                                  '<span class="badge bg-warning text-dark">Pending</span>')));
                
                let actions = '';
                
                if (!isPastDate && app.status !== 'completed' && app.status !== 'cancelled') {
                    // Show "View" button next to "Diagnose" with icons
                    if (isSpecialRecordDay) {
                        actions = `
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="/admin/appointments/${app.id}/diagnose" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm"><i class="fas fa-stethoscope me-1"></i>Diagnose</a>
                                <a href="/admin/patients/${app.user_id}" class="btn btn-sm btn-outline-info rounded-pill px-3 shadow-sm"><i class="fas fa-eye me-1"></i>View</a>
                            </div>
                        `;
                    } else {
                        actions = `<a href="/admin/appointments/${app.id}/diagnose" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm"><i class="fas fa-stethoscope me-1"></i>Diagnose</a>`;
                    }
                } else if (app.status === 'completed') {
                    actions = `<span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i> Diagnosed</span>`;
                } else if (isPastDate || displayStatus === 'incomplete') {
                    actions = `<span class="text-muted small fw-bold"><i class="fas fa-ban me-1"></i> Past Date</span>`;
                } else if (app.status === 'cancelled') {
                    actions = `<span class="text-danger small fw-bold"><i class="fas fa-times-circle me-1"></i> Cancelled</span>`;
                }

                tbody.innerHTML += `<tr><td class="ps-4 fw-bold">#${app.queue_number}</td><td>${app.patient_name} <div class="small text-muted">${app.reason}</div></td><td>${statusBadge}</td><td class="text-end pe-4">${actions}</td></tr>`;
            });
        }
        new bootstrap.Modal(document.getElementById('dayDetailsModal')).show();
    }
</script>
@endsection