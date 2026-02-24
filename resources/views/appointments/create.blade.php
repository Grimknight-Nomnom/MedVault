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
        .calendar-header { display: none; }
        .calendar-grid { display: block; }
        .day-cell { display: none; }
        .day-cell.is-today, 
        .calendar-grid.show-all .day-cell:not(.disabled) { 
            display: flex !important; 
            height: auto; 
            min-height: 80px;
            margin-bottom: 1rem;
            width: 100%;
            border-width: 1px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            flex-direction: column; 
        }
        .day-cell.is-today { 
            border-width: 2px;
            border-color: #198754; 
        }
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

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <ul class="mb-0 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                
                {{-- Navigation Controls --}}
                <div class="d-flex align-items-center mb-2 mb-md-0 gap-3">
                    @if($date->copy()->startOfMonth()->isSameMonth(now()->startOfMonth()))
                        <button class="btn btn-sm btn-outline-secondary rounded-circle" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ route('appointments.create', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}" class="btn btn-sm btn-outline-success rounded-circle shadow-sm">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    <h2 class="fw-bold text-success mb-0 text-center" style="min-width: 180px;">
                        <span class="d-none d-md-inline">{{ $date->format('F Y') }}</span>
                        <span class="d-md-none">{{ $date->format('F Y') }}</span>
                    </h2>

                    <a href="{{ route('appointments.create', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}" class="btn btn-sm btn-outline-success rounded-circle shadow-sm">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                
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

                        <div onclick="openModal('{{ $day['date'] }}', {{ $day['is_disabled'] ? 'true' : 'false' }}, {{ $day['is_full'] ? 'true' : 'false' }})"
                             class="day-cell {{ $day['is_disabled'] ? 'disabled' : $bgClass }} {{ $isToday ? 'is-today' : '' }}"
                             data-date="{{ $day['date'] }}">
                            
                            <div class="d-flex justify-content-between align-items-start w-100">
                                
                                <div class="d-flex flex-column align-items-start">
                                    <span class="fw-bold fs-5">
                                        {{ $day['day'] }}
                                        @if($isToday) <span class="badge bg-danger ms-2 d-md-none">TODAY</span> @endif
                                    </span>
                                    
                                    <span class="small text-muted d-md-none text-uppercase fw-bold" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($day['date'])->format('l') }}
                                    </span>
                                </div>
                                
                                <span class="day-label opacity-75" title="{{ $labelText }}">{{ $labelText }}</span>
                            </div>
                            
                            <div class="mt-auto text-end w-100">
                                @if(!$day['is_disabled'])
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
            
            <div class="mt-4 text-center">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i> You can only book appointments up to 7 days in advance.</small>
            </div>
        </div>
    </div>
</div>

{{-- Modal Code --}}
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
                        <i class="fas fa-lock me-2"></i> Maximum appointments reached.<br><small class="text-muted">You and your dependents already have active appointments scheduled.</small>
                    </div>
                @else
                    <form id="bookingForm" action="{{ route('appointments.store') }}" method="POST" class="d-none mt-3 border-top pt-3">
                        @csrf
                        <input type="hidden" name="appointment_date" id="inputDate">
                        
                        {{-- --- NEW: Dependent / Target Patient Dropdown --- --}}
                        @if(Auth::user()->children && Auth::user()->children->count() > 0)
                            <div class="mb-3 p-3 bg-success bg-opacity-10 border border-success rounded-3 shadow-sm">
                                <label class="form-label fw-bold text-success mb-2"><i class="fas fa-users me-2"></i>Who is this appointment for?</label>
                                <select name="dependent_id" class="form-select border-success shadow-none cursor-pointer">
                                    <option value="">Myself ({{ Auth::user()->first_name }} {{ Auth::user()->last_name }})</option>
                                    @foreach(Auth::user()->children as $child)
                                        <option value="{{ $child->id }}">{{ $child->first_name }} {{ $child->last_name }} (Child)</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

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
    function openModal(date, isDisabled, isFull) {
        if (isDisabled) return; 
        
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

                if (data.is_full) {
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