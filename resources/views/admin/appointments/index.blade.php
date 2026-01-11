@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Manage Appointments</h2>
        <div class="btn-group">
            <a href="{{ route('admin.appointments.index', ['date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="btn btn-outline-secondary">&larr; Prev</a>
            <button type="button" class="btn btn-outline-secondary disabled fw-bold px-4">
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
                            <th class="py-3 text-secondary">Sun</th>
                            <th class="py-3 text-secondary">Mon</th>
                            <th class="py-3 text-secondary">Tue</th>
                            <th class="py-3 text-secondary">Wed</th>
                            <th class="py-3 text-secondary">Thu</th>
                            <th class="py-3 text-secondary">Fri</th>
                            <th class="py-3 text-secondary">Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $startOfMonth = $date->copy()->startOfMonth();
                            $endOfMonth = $date->copy()->endOfMonth();
                            $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) - 6 (Sat)
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
                                    
                                    <td class="align-top p-2 {{ $isCurrentMonth ? 'bg-white' : 'bg-light text-muted' }}" style="height: 120px;">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold {{ $isToday ? 'bg-primary text-white rounded-circle d-flex align-items-center justify-content-center' : '' }}" style="width: 30px; height: 30px;">
                                                {{ $currentDay->day }}
                                            </span>
                                        </div>

                                        @if ($count > 0 && $isCurrentMonth)
                                            <button onclick="openDayModal('{{ $dayString }}')" class="btn btn-sm btn-info w-100 text-white rounded-pill shadow-sm" style="font-size: 0.8rem;">
                                                {{ $count }} Appt{{ $count > 1 ? 's' : '' }}
                                            </button>
                                        @endif
                                    </td>
                                    @php $currentDay->addDay(); @endphp
                                @endfor
                            </tr>
                            @if ($currentDay->month != $date->month && $i == 7) 
                                @break 
                            @endif
                        @endwhile
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dayDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalDateTitle">Appointments</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Patient</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="modalTableBody">
                            </tbody>
                    </table>
                </div>
                <div id="emptyState" class="text-center py-5 d-none">
                    <p class="text-muted mb-0">No appointments found for this day.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Pass PHP data to JS
    const allAppointments = @json($appointments); // Uses the flat collection we passed

    function openDayModal(dateString) {
        // 1. Filter appointments for the selected date
        const dayAppointments = allAppointments.filter(app => {
            return app.appointment_date.startsWith(dateString); // Matches YYYY-MM-DD
        });

        // 2. Update Modal Title
        const dateObj = new Date(dateString);
        document.getElementById('modalDateTitle').innerText = dateObj.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });

        // 3. Build Table Rows
        const tbody = document.getElementById('modalTableBody');
        tbody.innerHTML = '';
        
        if (dayAppointments.length === 0) {
            document.getElementById('emptyState').classList.remove('d-none');
        } else {
            document.getElementById('emptyState').classList.add('d-none');
            
            dayAppointments.forEach(app => {
                const statusBadge = app.status === 'approved' ? '<span class="badge bg-success">Approved</span>' : 
                                    (app.status === 'cancelled' ? '<span class="badge bg-danger">Cancelled</span>' : '<span class="badge bg-warning text-dark">Pending</span>');

                const row = `
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">${app.patient_name}</div>
                            <div class="small text-muted">${app.email}</div>
                        </td>
                        <td>${app.reason}</td>
                        <td>${statusBadge}</td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="/admin/appointments/${app.id}/diagnose" class="btn btn-sm btn-outline-primary">Diagnose</a>
                                
                                ${app.status === 'pending' ? `
                                    <form action="/admin/appointments/${app.id}" method="POST" class="d-inline">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="hidden" name="status" value="approved">
                                        <button class="btn btn-sm btn-success ms-1">Approve</button>
                                    </form>
                                    <form action="/admin/appointments/${app.id}" method="POST" class="d-inline">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="hidden" name="status" value="cancelled">
                                        <button class="btn btn-sm btn-danger ms-1">Cancel</button>
                                    </form>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // 4. Show Modal
        new bootstrap.Modal(document.getElementById('dayDetailsModal')).show();
    }
</script>
@endsection