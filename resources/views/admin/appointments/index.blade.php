@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">Clinic Calendar</h2>

        <div class="d-flex align-items-center gap-3">
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm">&larr; Prev</a>
                <span class="btn btn-outline-secondary disabled fw-bold text-dark px-3 bg-white">
                    {{ $date->format('F Y') }}
                </span>
                <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm">Next &rarr;</a>
            </div>
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Book Patient
            </a>
        </div>
    </div>

    <div class="d-flex gap-3 small mb-3 justify-content-end align-items-center flex-wrap">
        <span class="badge bg-white text-dark border">Normal Check-up</span>
        <span class="badge" style="background-color: #dcfce7; color: #166534;">Area (Sun/Sat)</span>
        <span class="badge" style="background-color: #fce7f3; color: #9d174d;">Pregnancy (Tue/Thu)</span>
        <span class="badge" style="background-color: #e0f2fe; color: #075985;">Immunization (Wed)</span>
        <span class="badge" style="background-color: #fef9c3; color: #854d0e;">Special/Custom</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" style="table-layout: fixed;">
                    <thead class="bg-light text-center">
                        <tr>
                            <th class="py-3 text-danger w-14">SUN</th>
                            <th class="py-3 text-secondary w-14">MON</th>
                            <th class="py-3 text-secondary w-14">TUE</th>
                            <th class="py-3 text-secondary w-14">WED</th>
                            <th class="py-3 text-secondary w-14">THU</th>
                            <th class="py-3 text-secondary w-14">FRI</th>
                            <th class="py-3 text-danger w-14">SAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $startOfMonth = $date->copy()->startOfMonth();
                            $endOfMonth = $date->copy()->endOfMonth();
                            $startDayOfWeek = $startOfMonth->dayOfWeek; 
                            $currentDay = $startOfMonth->copy()->subDays($startDayOfWeek);
                        @endphp

                        @while ($currentDay <= $endOfMonth)
                            <tr>
                                @for ($i = 0; $i < 7; $i++)
                                    @php
                                        $isCurrentMonth = $currentDay->month == $date->month;
                                        $dayString = $currentDay->format('Y-m-d');
                                        
                                        $dayAppointments = $appointmentsByDate->get($dayString, collect());
                                        $count = $dayAppointments->count();
                                        $isToday = $dayString == now()->format('Y-m-d');
                                        
                                        // Retrieve Settings for this specific day
                                        $setting = $settings->get($dayString);
                                        $limit = $setting ? $setting->max_appointments : 30;
                                        $customLabel = $setting ? $setting->label : null;
                                        
                                        // Color & Label Logic
                                        $dayOfWeek = $currentDay->dayOfWeek;
                                        $bgColor = '#ffffff'; 
                                        $label = 'Check-up';

                                        if ($isCurrentMonth) {
                                            if ($customLabel) {
                                                // Priority: Custom Label = Light Yellow
                                                $bgColor = '#fef9c3'; 
                                                $label = $customLabel;
                                            } elseif ($dayOfWeek == 0 || $dayOfWeek == 6) { 
                                                $bgColor = '#dcfce7'; $label = 'Area';
                                            } elseif ($dayOfWeek == 2 || $dayOfWeek == 4) { 
                                                // CHANGED: Added || $dayOfWeek == 4 (Thursday)
                                                $bgColor = '#fce7f3'; $label = 'Pregnancy';
                                            } elseif ($dayOfWeek == 3) { 
                                                $bgColor = '#e0f2fe'; $label = 'Immunization';
                                            }
                                        } else {
                                            $bgColor = '#f8f9fa'; 
                                        }
                                    @endphp
                                    
                                    <td class="align-top p-2 position-relative" style="height: 120px; background-color: {{ $bgColor }};">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <span class="fw-bold {{ $isToday ? 'bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm' : ($isCurrentMonth ? 'text-dark' : 'text-muted') }}" 
                                                  style="width: 28px; height: 28px; font-size: 0.9rem;">
                                                {{ $currentDay->day }}
                                            </span>
                                            
                                            @if($isCurrentMonth)
                                                <button onclick="editDaySettings('{{ $dayString }}', {{ $limit }}, '{{ $customLabel ?? '' }}')" 
                                                        class="btn btn-link p-0 text-secondary opacity-50 hover-opacity-100" 
                                                        title="Edit Settings">
                                                    <i class="fas fa-cog fa-xs"></i>
                                                </button>
                                            @endif
                                        </div>

                                        @if($isCurrentMonth)
                                            <div class="text-uppercase fw-bold small text-muted mb-2 text-truncate" style="font-size: 0.65rem; letter-spacing: 0.5px;" title="{{ $label }}">
                                                {{ $label }}
                                            </div>

                                            @if ($count > 0)
                                                <button onclick="openDayModal('{{ $dayString }}')" class="btn btn-sm btn-white border shadow-sm w-100 text-start py-1 px-2 mb-1" style="font-size: 0.75rem;">
                                                    <span class="badge bg-primary rounded-pill me-1">{{ $count }}</span> Patient{{ $count > 1 ? 's' : '' }}
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                    @php $currentDay->addDay(); @endphp
                                @endfor
                            </tr>
                            @if ($currentDay->month != $date->month && $i == 7) @break @endif
                        @endwhile
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
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
                        <label class="form-label small fw-bold">Day Label (Optional)</label>
                        <input type="text" name="label" id="settingLabelInput" class="form-control form-control-sm" placeholder="e.g. Special Checkup">
                        <div class="form-text" style="font-size: 0.7rem;">Leave blank to use default (Area, Pregnancy, etc.)</div>
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

<div class="modal fade" id="dayDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalDateTitle">Appointments</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Queue</th>
                                <th>Patient</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="modalTableBody"></tbody>
                    </table>
                </div>
                <div id="emptyState" class="text-center py-5 d-none">
                    <p class="text-muted mb-0">No appointments found.</p>
                </div>
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
        const dayAppointments = allAppointments.filter(app => app.appointment_date.startsWith(dateString));
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
                let statusBadge = app.status === 'approved' ? '<span class="badge bg-primary">Approved</span>' : 
                                  (app.status === 'completed' ? '<span class="badge bg-success">Completed</span>' : 
                                  '<span class="badge bg-warning text-dark">Pending</span>');
                
                let actions = `<a href="/admin/appointments/${app.id}/diagnose" class="btn btn-sm btn-outline-primary rounded-pill px-3">Diagnose</a>`;

                const row = `<tr>
                    <td class="ps-4 fw-bold">#${app.queue_number}</td>
                    <td>${app.patient_name} <div class="small text-muted">${app.reason}</div></td>
                    <td>${statusBadge}</td>
                    <td class="text-end pe-4">${actions}</td>
                </tr>`;
                tbody.innerHTML += row;
            });
        }
        new bootstrap.Modal(document.getElementById('dayDetailsModal')).show();
    }
</script>

<style>
    .hover-opacity-100:hover { opacity: 1 !important; color: var(--bs-primary) !important; }
    .w-14 { width: 14.28%; }
</style>
@endsection