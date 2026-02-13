@extends('layouts.app')

@section('content')
<style>
    /* --- CALENDAR GRID STYLES --- */
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
    .calendar-header { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; text-align: center; font-weight: bold; color: #6c757d; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 10px; }

    /* Day Cell - General */
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

    /* --- MOBILE RESPONSIVENESS --- */
    @media (max-width: 767.98px) {
        .calendar-header { display: none; }
        .calendar-grid { display: flex; flex-direction: column; gap: 10px; }
        
        .day-cell { 
            min-height: auto; 
            height: auto; 
            padding: 15px;
            flex-direction: row; /* Side-by-side layout */
            align-items: center;
        }
        
        /* Hide empty slots on mobile */
        .day-cell.empty-slot { display: none; }
    }

    /* --- COLORS --- */
    .bg-area { background-color: #dcfce7; color: #166534; }       
    .bg-pregnancy { background-color: #fce7f3; color: #9d174d; }  
    .bg-immunization { background-color: #e0f2fe; color: #075985; } 
    .bg-special { background-color: #fef9c3; color: #854d0e; }    
    .bg-normal { background-color: #ffffff; color: #333; }        
</style>

<div class="container py-4">
    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <h2 class="fw-bold text-primary mb-0 w-100 text-center text-md-start">Clinic Calendar</h2>

        <div class="d-flex flex-column flex-md-row align-items-center gap-3 w-100 w-md-auto">
            <div class="btn-group shadow-sm w-100 w-md-auto">
                <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm flex-fill flex-md-grow-0">&larr; Prev</a>
                <span class="btn btn-outline-secondary disabled fw-bold text-dark px-3 bg-white flex-fill flex-md-grow-0">{{ $date->format('F Y') }}</span>
                <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm flex-fill flex-md-grow-0">Next &rarr;</a>
            </div>
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary d-flex align-items-center justify-content-center shadow-sm w-100 w-md-auto">
                <i class="fas fa-plus-circle me-2"></i> Book Patient
            </a>
        </div>
    </div>

    {{-- LEGEND --}}
    <div class="d-flex gap-2 small mb-4 justify-content-center justify-content-md-end flex-wrap">
        <span class="badge bg-white text-dark border">Normal Check-up</span>
        <span class="badge bg-area">Area (Sun/Sat)</span>
        <span class="badge bg-pregnancy">Pregnancy (Tue/Thu)</span>
        <span class="badge bg-immunization">Immunization (Wed)</span>
        <span class="badge bg-special">Special/Custom</span>
    </div>

    {{-- CALENDAR GRID --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            {{-- Desktop Header --}}
            <div class="calendar-header d-none d-md-grid">
                <div class="text-danger">SUN</div><div class="text-secondary">MON</div><div class="text-secondary">TUE</div><div class="text-secondary">WED</div><div class="text-secondary">THU</div><div class="text-secondary">FRI</div><div class="text-danger">SAT</div>
            </div>

            <div class="calendar-grid">
                @php
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth = $date->copy()->endOfMonth();
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                @endphp

                {{-- Empty Slots (Desktop Only) --}}
                @for ($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="p-4 bg-light rounded border border-light d-none d-md-block"></div>
                @endfor

                {{-- Days Loop --}}
                @for ($day = 1; $day <= $endOfMonth->day; $day++)
                    @php
                        $currentDate = $startOfMonth->copy()->setDay($day);
                        $dayString = $currentDate->format('Y-m-d');
                        $dayOfWeek = $currentDate->dayOfWeek;
                        $weekdayName = $currentDate->format('l');

                        $dayAppointments = $appointmentsByDate->get($dayString, collect());
                        $count = $dayAppointments->count();
                        $isToday = $dayString == now()->format('Y-m-d');
                        
                        $setting = $settings->get($dayString);
                        $limit = $setting ? $setting->max_appointments : 30;
                        $customLabel = $setting ? $setting->label : null;
                        
                        $bgClass = 'bg-normal';
                        $labelText = 'Check-up';

                        if ($customLabel) { $bgClass = 'bg-special'; $labelText = $customLabel; } 
                        elseif ($dayOfWeek == 0 || $dayOfWeek == 6) { $bgClass = 'bg-area'; $labelText = 'Area'; } 
                        elseif ($dayOfWeek == 2 || $dayOfWeek == 4) { $bgClass = 'bg-pregnancy'; $labelText = 'Pregnancy'; } 
                        elseif ($dayOfWeek == 3) { $bgClass = 'bg-immunization'; $labelText = 'Immunization'; }
                    @endphp

                    <div onclick="openDayModal('{{ $dayString }}')" class="day-cell {{ $bgClass }}">
                        
                        {{-- === MOBILE LAYOUT (Flex Row) === --}}
                        <div class="d-flex d-md-none justify-content-between align-items-center w-100">
                            {{-- LEFT: Day & Weekday --}}
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

                            {{-- RIGHT: Settings & Label --}}
                            <div class="d-flex flex-column align-items-end gap-2">
                                <button class="btn btn-light btn-sm border rounded-circle shadow-sm" 
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;"
                                        onclick="event.stopPropagation(); editDaySettings('{{ $dayString }}', {{ $limit }}, '{{ $customLabel ?? '' }}')">
                                    <i class="fas fa-cog text-secondary"></i>
                                </button>
                                <span class="badge bg-white text-dark border shadow-sm">{{ $labelText }}</span>
                            </div>
                        </div>

                        {{-- === DESKTOP LAYOUT (Flex Column) === --}}
                        <div class="d-none d-md-flex flex-column justify-content-between h-100 w-100">
                            <div class="d-flex justify-content-between align-items-start w-100">
                                <span class="fw-bold fs-5">{{ $day }} @if($isToday) <span class="badge bg-danger ms-1" style="font-size: 0.5rem;">TODAY</span> @endif</span>
                                <button class="btn btn-link p-0 text-secondary opacity-50 hover-opacity-100" 
                                        onclick="event.stopPropagation(); editDaySettings('{{ $dayString }}', {{ $limit }}, '{{ $customLabel ?? '' }}')">
                                    <i class="fas fa-cog"></i>
                                </button>
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

{{-- MODALS & SCRIPTS --}}
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

    function editDaySettings(date, currentLimit, currentLabel) {
        document.getElementById('settingDateInput').value = date;
        document.getElementById('settingDateDisplay').innerText = date;
        document.getElementById('settingLimitInput').value = currentLimit;
        document.getElementById('settingLabelInput').value = currentLabel;
        new bootstrap.Modal(document.getElementById('settingsModal')).show();
    }

    function openDayModal(dateString) {
        const dayAppointments = allAppointments.filter(app => app.calendar_date === dateString);
        dayAppointments.sort((a, b) => a.queue_number - b.queue_number);
        const dateObj = new Date(dateString);
        document.getElementById('modalDateTitle').innerText = dateObj.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
        const tbody = document.getElementById('modalTableBody');
        tbody.innerHTML = '';
        
        if (dayAppointments.length === 0) {
            document.getElementById('emptyState').classList.remove('d-none');
        } else {
            document.getElementById('emptyState').classList.add('d-none');
            dayAppointments.forEach(app => {
                let statusBadge = app.status === 'approved' ? '<span class="badge bg-primary">Approved</span>' : (app.status === 'completed' ? '<span class="badge bg-success">Completed</span>' : '<span class="badge bg-warning text-dark">Pending</span>');
                let actions = `<a href="/admin/appointments/${app.id}/diagnose" class="btn btn-sm btn-outline-primary rounded-pill px-3">Diagnose</a>`;
                tbody.innerHTML += `<tr><td class="ps-4 fw-bold">#${app.queue_number}</td><td>${app.patient_name} <div class="small text-muted">${app.reason}</div></td><td>${statusBadge}</td><td class="text-end pe-4">${actions}</td></tr>`;
            });
        }
        new bootstrap.Modal(document.getElementById('dayDetailsModal')).show();
    }
</script>
@endsection