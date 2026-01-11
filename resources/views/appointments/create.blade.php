@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary mb-0">
                    {{ $date->format('F Y') }}
                </h2>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-history me-1"></i> My History
                </a>
            </div>

            <div class="calendar-grid mb-2 text-center fw-bold text-secondary text-uppercase small">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>

            <div class="calendar-grid">
                @foreach($calendar as $day)
                    @if(is_null($day))
                        <div class="p-4 bg-light rounded"></div>
                    @else
                        <div onclick="openModal('{{ $day['date'] }}', {{ $day['is_past'] ? 'true' : 'false' }})"
                             class="day-cell position-relative p-2 border rounded {{ $day['status_class'] }}"
                             style="height: 100px; {{ $day['is_past'] ? 'opacity: 0.6; pointer-events: none;' : 'cursor: pointer;' }}">
                            
                            <span class="fw-bold fs-5 {{ $day['is_past'] ? 'text-muted' : 'text-dark' }}">{{ $day['day'] }}</span>
                            
                            <div class="position-absolute bottom-0 end-0 p-2">
                                <span class="badge rounded-pill {{ $day['badge_class'] }}">
                                    {{ $day['is_full'] ? 'FULL' : $day['count'] . '/30' }}
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Loading...</h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeModal()"></button>
            </div>
            
            <div class="modal-body">
                <div id="modalStatus" class="mb-3"></div>

                <h6 class="text-uppercase text-muted fw-bold small">Current Queue</h6>
                <div class="list-group mb-3 overflow-auto" style="max-height: 200px;" id="queueList">
                    </div>
                <div id="emptyQueueMsg" class="text-center text-muted small py-3 d-none">
                    No bookings yet for this day.
                </div>

                <form id="bookingForm" action="{{ route('appointments.store') }}" method="POST" class="d-none mt-3 border-top pt-3">
                    @csrf
                    <input type="hidden" name="appointment_date" id="inputDate">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Visit</label>
                        <textarea name="reason" class="form-control" rows="2" required placeholder="Briefly describe symptoms..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success fw-bold py-2">
                            Book Queue #<span id="nextQueueNum"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }
    .day-cell:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        transition: all 0.2s;
        border-color: var(--bs-primary) !important;
    }
</style>

<script>
    let bootstrapModal;

    document.addEventListener('DOMContentLoaded', function() {
        bootstrapModal = new bootstrap.Modal(document.getElementById('bookingModal'));
    });

    function openModal(date, isPast) {
        if (isPast) return;

        const title = document.getElementById('modalTitle');
        const list = document.getElementById('queueList');
        const form = document.getElementById('bookingForm');
        const statusDiv = document.getElementById('modalStatus');
        const emptyMsg = document.getElementById('emptyQueueMsg');
        
        // 1. Reset UI
        title.innerText = 'Checking availability...';
        list.innerHTML = '';
        statusDiv.innerHTML = '';
        form.classList.add('d-none');
        emptyMsg.classList.add('d-none');
        document.getElementById('inputDate').value = date;
        
        bootstrapModal.show();

        // 2. Fetch Data
        fetch(`{{ route('api.appointments.slots') }}?date=${date}`)
            .then(res => res.json())
            .then(data => {
                title.innerText = data.date_formatted;

                // 3. Populate Queue List
                if (data.appointments.length === 0) {
                    emptyMsg.classList.remove('d-none');
                } else {
                    data.appointments.forEach(appt => {
                        // Highlight user's own appointment
                        const activeClass = appt.is_me ? 'list-group-item-primary fw-bold' : '';
                        const badgeColor = appt.status === 'Approved' ? 'bg-success' : 'bg-warning text-dark';
                        
                        const item = `
                            <div class="list-group-item d-flex justify-content-between align-items-center ${activeClass}">
                                <div>
                                    <span class="badge bg-secondary rounded-pill me-2">#${appt.queue}</span>
                                    ${appt.name}
                                </div>
                                <span class="badge ${badgeColor}">${appt.status}</span>
                            </div>
                        `;
                        list.innerHTML += item;
                    });
                }

                // 4. Determine Status
                if (data.user_has_booking) {
                    statusDiv.innerHTML = `<div class="alert alert-warning mb-0 text-center">You already have a booking on this day.</div>`;
                } else if (data.is_full) {
                    statusDiv.innerHTML = `<div class="alert alert-danger mb-0 text-center">Fully Booked (30/30).</div>`;
                } else {
                    const slotsLeft = 30 - data.slots_taken;
                    statusDiv.innerHTML = `<div class="alert alert-success py-2 text-center small mb-0">${slotsLeft} slots available</div>`;
                    
                    // Show Booking Form
                    form.classList.remove('d-none');
                    document.getElementById('nextQueueNum').innerText = data.next_queue;
                }
            })
            .catch(err => {
                console.error(err);
                title.innerText = "Error loading data";
            });
    }

    function closeModal() {
        bootstrapModal.hide();
    }
</script>
@endsection