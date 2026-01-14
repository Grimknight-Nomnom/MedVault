@extends('layouts.app')

@section('content')
<style>
    /* Specific Day Colors */
    .bg-area { background-color: #dcfce7; color: #166534; } /* Light Green (Sun/Sat) */
    .bg-pregnancy { background-color: #fce7f3; color: #9d174d; } /* Light Pink (Tue) */
    .bg-immunization { background-color: #e0f2fe; color: #075985; } /* Light Blue (Wed) */
    .bg-special { background-color: #fef9c3; color: #854d0e; } /* Light Yellow (Custom) */
    .bg-normal { background-color: #ffffff; color: #333; } /* White (Normal) */

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }
    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        text-align: center;
        font-weight: bold;
        color: #6c757d;
        text-transform: uppercase;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }
    .day-cell {
        height: 110px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 8px;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .day-cell:not(.disabled):hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-color: var(--bs-primary) !important;
        cursor: pointer;
        z-index: 2;
    }
    .day-cell.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f8f9fa !important;
    }
    .day-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-success small fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-success mb-0">
                    {{ $date->format('F Y') }}
                </h2>
                <div class="d-none d-md-flex gap-3 small">
                    <span class="d-flex align-items-center"><span class="d-inline-block rounded-circle bg-area border me-1" style="width:10px;height:10px;"></span> Area</span>
                    <span class="d-flex align-items-center"><span class="d-inline-block rounded-circle bg-pregnancy border me-1" style="width:10px;height:10px;"></span> Pregnancy</span>
                    <span class="d-flex align-items-center"><span class="d-inline-block rounded-circle bg-immunization border me-1" style="width:10px;height:10px;"></span> Immunization</span>
                    <span class="d-flex align-items-center"><span class="d-inline-block rounded-circle bg-special border me-1" style="width:10px;height:10px;"></span> Special</span>
                </div>
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
                        <div class="p-4 bg-light rounded border border-light"></div>
                    @else
                        @php
                            $dayOfWeek = \Carbon\Carbon::parse($day['date'])->dayOfWeek;
                            $bgClass = 'bg-normal';
                            $labelText = 'Check-up'; 

                            // Logic: Custom Label overrides everything
                            if (!empty($day['label'])) {
                                $bgClass = 'bg-special';
                                $labelText = $day['label'];
                                // Special default for pregnancy text
                                if(Illuminate\Support\Str::contains(Illuminate\Support\Str::lower($day['label']), 'pregnancy')) {
                                    $bgClass = 'bg-pregnancy';
                                }
                            } elseif ($dayOfWeek == 0 || $dayOfWeek == 6) { // Sun/Sat
                                $bgClass = 'bg-area';
                                $labelText = 'Area';
                            } elseif ($dayOfWeek == 2) { // Tue
                                $bgClass = 'bg-pregnancy';
                                $labelText = 'Pregnancy';
                            } elseif ($dayOfWeek == 3) { // Wed
                                $bgClass = 'bg-immunization';
                                $labelText = 'Immunization';
                            }
                        @endphp

                        <div onclick="openModal('{{ $day['date'] }}', {{ $day['is_past'] ? 'true' : 'false' }}, {{ $day['is_full'] ? 'true' : 'false' }})"
                             class="day-cell {{ $day['is_past'] ? 'disabled' : $bgClass }}">
                            
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="fw-bold fs-5">{{ $day['day'] }}</span>
                                <span class="day-label opacity-75" title="{{ $labelText }}">{{ $labelText }}</span>
                            </div>
                            
                            <div class="mt-auto text-end">
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
                <div id="emptyQueueMsg" class="text-center text-muted small py-3 d-none">
                    No bookings yet. Be the first!
                </div>

                @if(isset($hasActiveAppointment) && $hasActiveAppointment)
                    <div class="alert alert-secondary text-center border-0 bg-light">
                        <i class="fas fa-lock me-2"></i> You have an active appointment.
                        <br><small class="text-muted">You cannot book another until it is completed.</small>
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
                            <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">
                                Confirm Booking #<span id="nextQueueNum"></span>
                            </button>
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
        
        // Reset UI
        title.innerText = 'Checking availability...';
        list.innerHTML = '<div class="text-center py-3 text-muted">Loading...</div>';
        statusDiv.innerHTML = '';
        if(form) form.classList.add('d-none');
        emptyMsg.classList.add('d-none');
        
        // Safe assignment
        if (inputDate) {
            inputDate.value = date;
        }
        
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
                        
                        const item = `
                            <div class="list-group-item d-flex justify-content-between align-items-center ${activeClass}">
                                <span><span class="badge bg-secondary rounded-pill me-2">#${appt.queue}</span> ${appt.name}</span>
                                <span class="badge ${badgeColor}">${appt.status}</span>
                            </div>
                        `;
                        list.innerHTML += item;
                    });
                }

                // --- RESTRICTION HANDLING ---
                if (data.is_restricted) {
                    // Show restriction message, do NOT show form
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
            .catch(err => {
                console.error(err);
                list.innerHTML = '<div class="text-danger text-center py-2">Failed to load data.</div>';
            });
    }

    function closeModal() {
        bootstrapModal.hide();
    }
</script>
@endsection