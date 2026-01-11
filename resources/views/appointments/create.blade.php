@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Book Appointment</h2>
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">My History</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            @php
                $date = now();
                $daysInMonth = $date->daysInMonth;
                $startDayOfWeek = $date->copy()->startOfMonth()->dayOfWeek; // 0=Sun, 6=Sat
            @endphp

            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">{{ $date->format('F Y') }}</h3>
                <span class="badge bg-light text-dark border">Today: {{ now()->format('M d') }}</span>
            </div>

            <div class="row row-cols-7 g-2 text-center">
                <div class="col fw-bold text-secondary">Sun</div>
                <div class="col fw-bold text-secondary">Mon</div>
                <div class="col fw-bold text-secondary">Tue</div>
                <div class="col fw-bold text-secondary">Wed</div>
                <div class="col fw-bold text-secondary">Thu</div>
                <div class="col fw-bold text-secondary">Fri</div>
                <div class="col fw-bold text-secondary">Sat</div>

                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="col p-4 bg-light rounded"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDate = $date->copy()->setDay($day)->format('Y-m-d');
                        $slotsTaken = $counts[$currentDate] ?? 0;
                        $isFull = $slotsTaken >= 30;
                        $isPast = $currentDate < now()->format('Y-m-d');
                        
                        // Color Logic
                        if($isFull) $bgClass = 'bg-danger text-white';
                        elseif($slotsTaken >= 20) $bgClass = 'bg-warning text-dark';
                        else $bgClass = 'bg-success text-white';

                        if($isPast) $bgClass = 'bg-secondary text-white opacity-50';
                    @endphp

                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm {{ $isPast ? '' : 'btn-date' }}" 
                             @if(!$isPast) onclick="openDateModal('{{ $currentDate }}')" @endif 
                             style="cursor: {{ $isPast ? 'not-allowed' : 'pointer' }}">
                            
                            <div class="card-body p-2 {{ $bgClass }} rounded">
                                <h5 class="fw-bold">{{ $day }}</h5>
                                <small class="d-block" style="font-size: 0.75rem;">
                                    {{ $isFull ? 'FULL' : "$slotsTaken/30 Taken" }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="slotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Loading...</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="statusAlert" class="alert d-none"></div>

                <h6 class="fw-bold text-muted">Current Queue:</h6>
                <div class="border rounded p-2 mb-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                    <ul class="list-unstyled mb-0" id="queueList">
                        </ul>
                    <div id="emptyQueueMsg" class="text-center text-muted small py-2 d-none">
                        No appointments yet. Be the first!
                    </div>
                </div>

                <div id="bookingFormSection" class="d-none">
                    <hr>
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="appointment_date" id="formDateInput">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reason for Visit</label>
                            <textarea name="reason" class="form-control" rows="2" required placeholder="Describe symptoms..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold py-2">
                                Book Queue #<span id="nextQueueSpan"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openDateModal(date) {
        const modal = new bootstrap.Modal(document.getElementById('slotModal'));
        const list = document.getElementById('queueList');
        const emptyMsg = document.getElementById('emptyQueueMsg');
        const alertBox = document.getElementById('statusAlert');
        const formSection = document.getElementById('bookingFormSection');
        const title = document.getElementById('modalTitle');

        // Reset UI
        title.innerText = 'Checking availability...';
        list.innerHTML = '';
        emptyMsg.classList.add('d-none');
        alertBox.className = 'alert d-none';
        formSection.classList.add('d-none');
        
        modal.show();

        // Fetch Data
        fetch("{{ route('api.appointments.slots') }}?date=" + date)
            .then(res => res.json())
            .then(data => {
                title.innerText = `Appointments for ${data.date_formatted}`;
                document.getElementById('formDateInput').value = date;

                // 1. Populate List
                if (data.slots.length === 0) {
                    emptyMsg.classList.remove('d-none');
                } else {
                    data.slots.forEach(slot => {
                        const isMe = slot.is_me ? 'bg-info bg-opacity-10 fw-bold border-info' : '';
                        const li = `
                            <li class="d-flex justify-content-between align-items-center p-2 border-bottom ${isMe}">
                                <span>
                                    <span class="badge bg-secondary me-2">#${slot.queue_number}</span>
                                    ${slot.name}
                                </span>
                                <span class="badge bg-light text-dark border">${slot.status}</span>
                            </li>
                        `;
                        list.innerHTML += li;
                    });
                }

                // 2. Determine Action
                if (data.user_has_booking) {
                    alertBox.innerText = "You already have a booking on this date.";
                    alertBox.className = "alert alert-warning text-center";
                    alertBox.classList.remove('d-none');
                } else if (data.is_full) {
                    alertBox.innerText = "Fully Booked.";
                    alertBox.className = "alert alert-danger text-center";
                    alertBox.classList.remove('d-none');
                } else {
                    // Available to Book
                    alertBox.innerText = `${30 - data.slots_taken} slots available.`;
                    alertBox.className = "alert alert-success text-center py-1 small";
                    alertBox.classList.remove('d-none');
                    
                    document.getElementById('nextQueueSpan').innerText = data.next_queue;
                    formSection.classList.remove('d-none');
                }
            })
            .catch(err => {
                console.error(err);
                alert("Failed to load data.");
            });
    }
</script>

<style>
    .btn-date:hover { transform: scale(1.02); transition: 0.2s; }
</style>
@endsection