@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Manage Appointments</h2>
        <div class="btn-group shadow-sm">
            <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary">&larr; Prev</a>
            <button type="button" class="btn btn-outline-secondary disabled fw-bold px-4 text-dark">
                {{ $date->format('F Y') }}
            </button>
            <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary">Next &rarr;</a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 text-center" style="table-layout: fixed;">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 text-secondary small text-uppercase">Sun</th>
                            <th class="py-3 text-secondary small text-uppercase">Mon</th>
                            <th class="py-3 text-secondary small text-uppercase">Tue</th>
                            <th class="py-3 text-secondary small text-uppercase">Wed</th>
                            <th class="py-3 text-secondary small text-uppercase">Thu</th>
                            <th class="py-3 text-secondary small text-uppercase">Fri</th>
                            <th class="py-3 text-secondary small text-uppercase">Sat</th>
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
                                    @endphp
                                    
                                    <td class="align-top p-2 {{ $isCurrentMonth ? 'bg-white' : 'bg-light text-muted opacity-50' }}" style="height: 120px;">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold {{ $isToday ? 'bg-primary text-white rounded-circle d-flex align-items-center justify-content-center' : '' }}" style="width: 30px; height: 30px;">
                                                {{ $currentDay->day }}
                                            </span>
                                        </div>

                                        @if ($count > 0 && $isCurrentMonth)
                                            <button onclick="openDayModal('{{ $dayString }}')" class="btn btn-sm btn-info w-100 text-white rounded-pill shadow-sm py-1" style="font-size: 0.75rem;">
                                                <i class="fas fa-calendar-check me-1"></i> {{ $count }} Appt{{ $count > 1 ? 's' : '' }}
                                            </button>
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

<div class="modal fade" id="dayDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalDateTitle">Appointments</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Patient</th>
                                <th class="py-3">Reason</th>
                                <th class="py-3">Status</th>
                                <th class="text-end pe-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="modalTableBody">
                            </tbody>
                    </table>
                </div>
                <div id="emptyState" class="text-center py-5 d-none">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No appointments found for this day.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const allAppointments = @json($appointments);

    function openDayModal(dateString) {
        const dayAppointments = allAppointments.filter(app => app.appointment_date.startsWith(dateString));
        const dateObj = new Date(dateString);
        document.getElementById('modalDateTitle').innerText = dateObj.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });

        const tbody = document.getElementById('modalTableBody');
        tbody.innerHTML = '';
        
        if (dayAppointments.length === 0) {
            document.getElementById('emptyState').classList.remove('d-none');
        } else {
            document.getElementById('emptyState').classList.add('d-none');
            
            dayAppointments.forEach(app => {
                // Determine Status Badge
                let statusBadge = '';
                if (app.status === 'completed') {
                    statusBadge = '<span class="badge bg-success rounded-pill px-3"><i class="fas fa-check-circle me-1"></i> Completed</span>';
                } else if (app.status === 'approved') {
                    statusBadge = '<span class="badge bg-info text-white rounded-pill px-3">Approved</span>';
                } else if (app.status === 'cancelled') {
                    statusBadge = '<span class="badge bg-danger rounded-pill px-3">Cancelled</span>';
                } else {
                    statusBadge = '<span class="badge bg-warning text-dark rounded-pill px-3">Pending</span>';
                }

                // Determine Actions (Logic from your snippet)
                let actionButtons = '';
                if (app.status !== 'completed') {
                    actionButtons = `
                        <div class="btn-group">
                            <a href="/admin/appointments/${app.id}/diagnose" class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="fas fa-stethoscope me-1"></i> Diagnose
                            </a>
                            ${app.status === 'pending' ? `
                                <form action="/admin/appointments/${app.id}" method="POST" class="d-inline ms-1">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-outline-success rounded-circle" title="Approve"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="/admin/appointments/${app.id}" method="POST" class="d-inline ms-1">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-sm btn-outline-danger rounded-circle" title="Cancel"><i class="fas fa-times"></i></button>
                                </form>
                            ` : ''}
                        </div>
                    `;
                } else {
                    actionButtons = '<span class="text-muted small italic">Record Saved</span>';
                }

                const row = `
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">${app.patient_name}</div>
                            <div class="small text-muted">${app.email}</div>
                        </td>
                        <td class="small">${app.reason}</td>
                        <td>${statusBadge}</td>
                        <td class="text-end pe-4">${actionButtons}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        new bootstrap.Modal(document.getElementById('dayDetailsModal')).show();
    }
</script>
@endsection