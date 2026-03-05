@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Staff Dashboard</h2>
            <p class="text-muted">Overview of clinic operations.</p>
        </div>
        
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted border-start ps-3"><i class="far fa-clock me-1"></i> {{ date('F d, Y') }}</span>
        </div>
    </div>

    {{-- STATS ROW --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card h-100 border-0 border-start border-4 border-primary shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold text-uppercase small">Total Medicines</p>
                        <h3 class="fw-bold mb-0">{{ $totalMedicines }}</h3>
                        <span class="text-muted small fw-bold">Inventory Overview</span>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle"><i class="fas fa-pills fa-2x text-primary"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 border-start border-4 border-info shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold text-uppercase small">Today's Appointments</p>
                        <h3 class="fw-bold mb-0">{{ $todayAppointmentsCount ?? 0 }}</h3>
                        <span class="text-muted small fw-bold">Daily Schedule</span>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle"><i class="fas fa-calendar-check fa-2x text-info"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 border-start border-4 border-success shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold text-uppercase small">Registered Patients</p>
                        <h3 class="fw-bold mb-0">{{ $totalPatients }}</h3>
                        <span class="text-muted small fw-bold">Patient Records</span>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle"><i class="fas fa-users fa-2x text-success"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 border-start border-4 border-warning shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold text-uppercase small">Announcements</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Announcement::where('is_active', true)->count() }}</h3>
                        <span class="text-muted small fw-bold">Active Posts</span>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle"><i class="fas fa-bullhorn fa-2x text-warning"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTS ROW --}}
    <div class="row g-4">
        {{-- LOW STOCK TABLE --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert (Under 10)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light small"><tr><th>Medicine</th><th class="text-center">Current Qty</th></tr></thead>
                        <tbody>
                            @forelse(collect($lowStock ?? [])->take(5) as $med)
                            <tr><td class="ps-3 small fw-bold">{{ $med->name }}</td><td class="text-center text-danger fw-bold">{{ $med->stock_quantity }}</td></tr>
                            @empty
                            <tr><td colspan="2" class="text-center text-muted py-3 small">No low stock items.</td></tr>
                            @endforelse
                        </tbody>
                        
                        @if(collect($lowStock ?? [])->count() > 5)
                        <tbody class="collapse" id="collapseLowStock">
                            @foreach(collect($lowStock ?? [])->skip(5) as $med)
                            <tr><td class="ps-3 small fw-bold">{{ $med->name }}</td><td class="text-center text-danger fw-bold">{{ $med->stock_quantity }}</td></tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-center bg-light p-0">
                                    <button class="btn btn-link btn-sm text-decoration-none text-muted w-100 py-2 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLowStock" onclick="this.innerText = this.innerText.includes('More') ? 'Show Less ▴' : 'Show More ▼'">Show More ▼</button>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- EXPIRY ALERT TABLE --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-warning mb-0"><i class="fas fa-hourglass-half me-2"></i>Expiry Alerts (30 Days)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light small">
                            <tr><th>Medicine</th><th>Date</th><th class="text-center">Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse(collect($expiringSoon ?? [])->take(5) as $med)
                            <tr>
                                <td class="ps-3 small fw-bold">{{ $med->name }}</td>
                                <td class="small">{{ \Carbon\Carbon::parse($med->expiry_date)->format('F Y') }}</td>
                                <td class="text-center"><span class="badge {{ $med->expiry_date < now() ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $med->expiry_date < now() ? 'EXPIRED' : 'EXPIRING' }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3 small">No immediate expirations.</td></tr>
                            @endforelse
                        </tbody>
                        
                        @if(collect($expiringSoon ?? [])->count() > 5)
                        <tbody class="collapse" id="collapseExpiry">
                            @foreach(collect($expiringSoon ?? [])->skip(5) as $med)
                            <tr>
                                <td class="ps-3 small fw-bold">{{ $med->name }}</td>
                                <td class="small">{{ \Carbon\Carbon::parse($med->expiry_date)->format('F Y') }}</td>
                                <td class="text-center"><span class="badge {{ $med->expiry_date < now() ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $med->expiry_date < now() ? 'EXPIRED' : 'EXPIRING' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-center bg-light p-0">
                                    <button class="btn btn-link btn-sm text-decoration-none text-muted w-100 py-2 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExpiry" onclick="this.innerText = this.innerText.includes('More') ? 'Show Less ▴' : 'Show More ▼'">Show More ▼</button>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection