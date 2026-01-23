@extends('layouts.app')

@section('content')
<style>
    /* Specific Day Colors */
    .bg-area { background-color: #dcfce7; color: #166534; } /* Light Green */
    .bg-pregnancy { background-color: #fce7f3; color: #9d174d; } /* Light Pink */
    .bg-immunization { background-color: #e0f2fe; color: #075985; } /* Light Blue */
    .bg-special { background-color: #fef9c3; color: #854d0e; } /* Light Yellow */
    .bg-normal { background-color: #ffffff; color: #333; } /* White */

    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
    .calendar-header { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; text-align: center; font-weight: bold; color: #6c757d; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 10px; }
    .day-cell { height: 110px; border: 1px solid #dee2e6; border-radius: 8px; padding: 8px; position: relative; transition: all 0.2s ease; display: flex; flex-direction: column; justify-content: space-between; }
    .day-cell:not(.disabled):hover { transform: translateY(-3px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-color: var(--bs-primary) !important; cursor: pointer; z-index: 2; }
    .day-cell.disabled { opacity: 0.6; cursor: not-allowed; background-color: #f8f9fa !important; }
    .day-label { font-size: 0.65rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 4px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* --- MOBILE RESPONSIVENESS START --- */
    @media (max-width: 767.98px) {
        /* Hide the Grid Header (Sun, Mon...) */
        .calendar-header { display: none; }
        
        /* Change Grid to Block (List View) */
        .calendar-grid { display: block; }

        /* Hide ALL days by default on mobile */
        .day-cell { display: none; }

        /* Style for visible days (Today OR Expanded List) */
        .day-cell.is-today, 
        .calendar-grid.show-all .day-cell:not(.disabled) { 
            display: flex !important; 
            height: auto; /* Allow height to grow with content */
            min-height: 80px;
            margin-bottom: 1rem;
            width: 100%;
            border-width: 1px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            flex-direction: column; /* Stack content vertically */
        }

        /* Highlight 'Today' slightly more */
        .day-cell.is-today { 
            border-width: 2px;
            border-color: #198754; /* Green border for today */
        }
        
        /* Center text in cards */
        .day-cell .fs-5 { font-size: 1.25rem !important; margin-bottom: 0.25rem; }
    }
    /* --- MOBILE RESPONSIVENESS END --- */
</style>

<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-success small fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                
                {{-- Dynamic Title --}}
                <h2 class="fw-bold text-success mb-2 mb-md-0 text-center text-md-start">
                    <span class="d-none d-md-inline">{{ $date->format('F Y') }}</span>
                    {{-- Mobile Title: January 2026 (Friday) --}}
                    <span class="d-md-none">{{ now()->format('F Y (l)') }}</span>
                </h2>
                
                <div class="d-none d-md-flex gap-3 small">
                    <span class="d-flex align-items-center"><span class="d-inline-block rounded-circle bg-area border me-1" style="width:10px;height:10px;"></span> Area</span>
                    <span class="d-flex align-items-center"><span class="d-inline-block rounded-circle bg-pregnancy border me-1" style="width:10px;height:10px;"></span> Pregnancy</span>
                    <span class="d-flex align-items-center"><span class="d-inline-block rounded-circle bg-immunization border me-1" style="width:10px;height:10px;"></span> Immunization</span>
                    <span class="d-flex align-items-center"><span class="d-inline-block rounded-circle bg-special border me-1" style="width:10px;height:10px;"></span> Special</span>
                </div>
            </div>

            {{-- Mobile Toggle Button --}}
            <div class="d-grid d-md-none mb-3">
                <button class="btn btn-outline-success btn-sm rounded-pill" onclick="document.querySelector('.calendar-grid').classList.toggle('show-all'); this.innerText = this.innerText === 'Show Full Month' ? 'Show Today Only' : 'Show Full Month'">
                    Show Full Month
                </button>
            </div>

            <div class="calendar-header">
                <div class="text-danger">Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div class="text-danger">Sat</div>
            </div>

            <div class="calendar-grid">
                @foreach($calendar as $day)
                    @if(is_null($day))
                        <div class="p-4 bg-light rounded border border-light d-none d-md-block"></div>
                    @else
                        @php
                            $dayOfWeek = \Carbon\Carbon::parse($day['date'])->dayOfWeek;
                            $bgClass = 'bg-normal';
                            $labelText = 'Check-up'; 

                            // Check if this date is TODAY
                            $isToday = $day['date'] === now()->format('Y-m-d');

                            if (!empty($day['label'])) {
                                $bgClass = 'bg-special';
                                $labelText = $day['label'];
                                if(Illuminate\Support\Str::contains(Illuminate\Support\Str::lower($day['label']), 'pregnancy')) {
                                    $bgClass = 'bg-pregnancy';
                                }
                            } elseif ($dayOfWeek == 0 || $dayOfWeek == 6) { 
                                $bgClass = 'bg-area';
                                $labelText = 'Area';
                            } elseif ($dayOfWeek == 2 || $dayOfWeek == 4) { 
                                $bgClass = 'bg-pregnancy';
                                $labelText = 'Pregnancy';
                            } elseif ($dayOfWeek == 3) { 
                                $bgClass = 'bg-immunization';
                                $labelText = 'Immunization';
                            }
                        @endphp

                        <div onclick="openModal('{{ $day['date'] }}', {{ $day['is_past'] ? 'true' : 'false' }}, {{ $day['is_full'] ? 'true' : 'false' }})"
                             class="day-cell {{ $day['is_past'] ? 'disabled' : $bgClass }} {{ $isToday ? 'is-today' : '' }}"
                             data-date="{{ $day['date'] }}">
                            
                            {{-- Content Container --}}
                            <div class="d-flex justify-content-between align-items-start w-100">
                                
                                {{-- Date & Day Name Container --}}
                                <div class="d-flex flex-column align-items-start">
                                    <span class="fw-bold fs-5">
                                        {{ $day['day'] }}
                                        @if($isToday) <span class="badge bg-danger ms-2 d-md-none">TODAY</span> @endif
                                    </span>
                                    
                                    {{-- Day Name (Visible on Mobile for ALL days) --}}
                                    <span class="small text-muted d-md-none text-uppercase fw-bold" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($day['date'])->format('l') }}
                                    </span>
                                </div>
                                
                                {{-- Label (Visible on Mobile for ALL days) --}}
                                <span class="day-label opacity-75" title="{{ $labelText }}">{{ $labelText }}</span>
                            </div>
                            
                            {{-- Slots (Visible on Mobile for ALL days) --}}
                            <div class="mt-auto text-end w-100">
                                @if(!$day['is_past'])
                                    @if($day['is_full'])
                                        <span class="badge bg-danger">FULL</span>
                                    @else
                                        <span class="badge {{ $day['count'] > ($day['max'] * 0.7) ? 'bg-warning text-dark' : 'bg-success' }} rounded-pill">
                                            {{ $day['max'] - $day['count'] }} Slots
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Modal Code (Unchanged) --}}
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalTitle">Booking Details</h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeModal()"></button>
            </div>
            
            <div class="modal-body">
                <div id="modalStatus" class="mb-3"></div>
                <h6 class="text-uppercase text-muted fw-bold small">Current Queue</h6>
                <div class="list-group mb-3 overflow-auto border rounded bg-light" style="max-height: 200px;" id="queueList"></div>
                <div id="emptyQueueMsg" class="text-center text-muted small py-3 d-none">No bookings yet. Be the first!</div>
                @if(isset($hasActiveAppointment) && $hasActiveAppointment)
                    <div class="alert alert-secondary text-center border-0 bg-light">
                        <i class="fas fa-lock me-2"></i> You have an active appointment.<br><small class="text-muted">You cannot book another until it is completed.</small>
                    </div>
                @else
                    <form id="bookingForm" action="{{ route('appointments.store') }}" method="POST" class="d-none mt-3 border-top pt-3">
                        @csrf
                        <input type="hidden" name="appointment_date" id="inputDate">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Reason for Visit</label>
                            <textarea name="reason" class="form-control bg-light" rows="2" required placeholder="Briefly describe your purpose..."></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">Confirm Booking #<span id="nextQueueNum"></span></button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    let bootstrapModal;
    document.addEventListener('DOMContentLoaded', function() {
        bootstrapModal = new bootstrap.Modal(document.getElementById('bookingModal'));
    });
    function openModal(date, isPast, isFull) {
        if (isPast) return;
        const title = document.getElementById('modalTitle');
        const list = document.getElementById('queueList');
        const form = document.getElementById('bookingForm');
        const statusDiv = document.getElementById('modalStatus');
        const emptyMsg = document.getElementById('emptyQueueMsg');
        const inputDate = document.getElementById('inputDate');
        
        title.innerText = 'Checking availability...';
        list.innerHTML = '<div class="text-center py-3 text-muted">Loading...</div>';
        statusDiv.innerHTML = '';
        if(form) form.classList.add('d-none');
        emptyMsg.classList.add('d-none');
        if(inputDate) inputDate.value = date;
        
        bootstrapModal.show();

        fetch(`{{ route('api.appointments.slots') }}?date=${date}`)
            .then(res => res.json())
            .then(data => {
                title.innerText = data.date_formatted;
                list.innerHTML = '';
                if (data.appointments.length === 0) {
                    emptyMsg.classList.remove('d-none');
                } else {
                    data.appointments.forEach(appt => {
                        const activeClass = appt.is_me ? 'list-group-item-success fw-bold' : '';
                        const badgeColor = appt.status === 'Approved' ? 'bg-success' : 'bg-warning text-dark';
                        const item = `<div class="list-group-item d-flex justify-content-between align-items-center ${activeClass}"><span><span class="badge bg-secondary rounded-pill me-2">#${appt.queue}</span> ${appt.name}</span><span class="badge ${badgeColor}">${appt.status}</span></div>`;
                        list.innerHTML += item;
                    });
                }
                if (data.is_restricted) {
                    statusDiv.innerHTML = `<div class="alert alert-danger text-center small fw-bold border-danger border-2 bg-danger-subtle"><i class="fas fa-ban me-1"></i> ${data.restriction_message}</div>`;
                } else if (data.user_has_booking) {
                    statusDiv.innerHTML = `<div class="alert alert-warning text-center small fw-bold">You already have a booking on this day.</div>`;
                } else if (data.is_full) {
                    statusDiv.innerHTML = `<div class="alert alert-danger text-center small fw-bold">Fully Booked.</div>`;
                } else {
                    const slotsLeft = data.max_limit - data.slots_taken;
                    statusDiv.innerHTML = `<div class="alert alert-success text-center small py-2 fw-bold">${slotsLeft} slots available</div>`;
                    if(form) {
                        form.classList.remove('d-none');
                        document.getElementById('nextQueueNum').innerText = data.next_queue;
                    }
                }
            })
            .catch(err => { console.error(err); list.innerHTML = '<div class="text-danger text-center py-2">Failed to load data.</div>'; });
    }
    function closeModal() { bootstrapModal.hide(); }
</script>
@endsection