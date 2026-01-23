@extends('layouts.app')

@section('content')
<style>
    .bg-primary-opacity { background-color: rgba(13, 110, 253, 0.1); }
    .bg-danger-opacity { background-color: rgba(220, 53, 69, 0.1); }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Admin Dashboard</h2>
            <p class="text-muted">Overview of clinic operations.</p>
        </div>
        <span class="text-muted"><i class="far fa-clock me-1"></i> {{ date('F d, Y') }}</span>
    </div>

<div class="row g-4 mb-5">
        <div class="col-md-3"> {{-- Changed from col-md-4 --}}
            <div class="card h-100 border-0 border-start border-4 border-primary shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold text-uppercase small">Total Medicines</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Medicine::count() }}</h3>
                        <a href="{{ route('admin.medicines.index') }}" class="text-primary text-decoration-none small fw-bold">Manage Inventory</a>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-pills fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

<div class="col-md-3">
    <div class="card h-100 border-0 border-start border-4 border-info shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <p class="text-muted mb-1 fw-bold text-uppercase small">Today's Appointments</p>
                
               <h3 class="fw-bold mb-0">{{ $todayAppointmentsCount }}</h3>
                
                <a href="{{ route('admin.appointments.index') }}" class="text-info text-decoration-none small fw-bold">View Schedule</a>
            </div>
            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                <i class="fas fa-calendar-check fa-2x text-info"></i>
            </div>
        </div>
    </div>
</div>

        <div class="col-md-3"> {{-- Changed from col-md-4 --}}
            <div class="card h-100 border-0 border-start border-4 border-success shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold text-uppercase small">Registered Patients</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\User::where('role', 'user')->count() }}</h3>
                        <a href="{{ route('admin.patients.index') }}" class="text-success text-decoration-none small fw-bold stretched-link">
                            View Accounts
                        </a>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-users fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 border-start border-4 border-warning shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-bold text-uppercase small">Announcements</p>
                        {{-- Assuming you created the Announcement model in the previous steps --}}
                        <h3 class="fw-bold mb-0">{{ \App\Models\Announcement::where('is_active', true)->count() }}</h3>
                        <a href="{{ route('admin.announcements.index') }}" class="text-warning text-decoration-none small fw-bold">
                            Manage Posts
                        </a>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-bullhorn fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-line me-2 text-primary"></i>Inventory Trends</h5>
                    <select id="trendsFilter" class="form-select form-select-sm border-secondary" style="width: auto;">
                        <option value="month" selected>Monthly (Last 6 Months)</option>
                        <option value="week">Weekly (Last 12 Weeks)</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="trendsChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 border-top border-4 border-dark">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2"></i>Historical Peek</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="peekMode" id="modeMonth" value="month" checked autocomplete="off">
                            <label class="btn btn-outline-secondary" for="modeMonth">Month</label>
                            
                            <input type="radio" class="btn-check" name="peekMode" id="modeWeek" value="week" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="modeWeek">Week</label>
                        </div>
                    </div>
                    
                    <div id="monthSelectors" class="d-flex gap-1 justify-content-end">
                        <select id="reportMonth" class="form-select form-select-sm border-0 bg-light" style="width: 100px;">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                        <select id="reportYear" class="form-select form-select-sm border-0 bg-light" style="width: 80px;">
                            @foreach(range(date('Y')-1, date('Y')+1) as $y)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="weekSelectors" class="d-flex justify-content-end" style="display: none !important;">
                        <input type="week" id="reportWeek" class="form-control form-control-sm bg-light border-0" value="{{ date('Y-\WW') }}">
                    </div>
                </div>

                <div class="card-body text-center position-relative">
                    <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                        <button class="btn btn-sm btn-light rounded-circle" type="button" id="historyDetailsDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul id="activityLogContainer" class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                            </ul>
                    </div>

                    <p id="formattedDate" class="fw-bold text-muted mb-4">-</p>
                    <div class="row mb-4">
                        <div class="col-6 border-end">
                            <h2 id="releaseCount" class="fw-bold text-primary mb-0">0</h2>
                            <small class="text-uppercase text-muted fw-bold">Released</small>
                        </div>
                        <div class="col-6">
                            <h2 id="expiryCount" class="fw-bold text-danger mb-0">0</h2>
                            <small class="text-uppercase text-muted fw-bold">Expired</small>
                        </div>
                    </div>
                    <canvas id="peekChart" style="max-height: 150px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert (Under 10)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light small">
                            <tr><th>Medicine</th><th class="text-center">Current Qty</th></tr>
                        </thead>
                        <tbody>
                            @forelse($lowStock as $med)
                            <tr><td class="ps-3 small fw-bold">{{ $med->name }}</td><td class="text-center text-danger fw-bold">{{ $med->stock_quantity }}</td></tr>
                            @empty
                            <tr><td colspan="2" class="text-center text-muted py-3 small">No low stock items.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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
                            @forelse($expiringSoon as $med)
                            <tr>
                                <td class="ps-3 small fw-bold">{{ $med->name }}</td>
                                <td class="small">{{ \Carbon\Carbon::parse($med->expiry_date)->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $med->expiry_date < now() ? 'bg-danger' : 'bg-warning text-dark' }}">
                                        {{ $med->expiry_date < now() ? 'EXPIRED' : 'EXPIRING' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3 small">No immediate expirations.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let peekChart;
    let trendsChart;

    // --- 1. TRENDS CHART LOGIC ---
    function fetchTrendsData() {
        const filter = document.getElementById('trendsFilter').value;
        
        fetch(`{{ route('admin.trends.api') }}?filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                if (trendsChart) trendsChart.destroy();

                const ctx = document.getElementById('trendsChart').getContext('2d');
                trendsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Releases',
                                data: data.releases,
                                borderColor: '#0d6efd',
                                backgroundColor: '#0d6efd',
                                tension: 0.3,
                                fill: false
                            },
                            {
                                label: 'Expirations',
                                data: data.expirations,
                                borderColor: '#dc3545',
                                backgroundColor: '#dc3545',
                                tension: 0.3,
                                fill: false
                            }
                        ]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            });
    }

    // --- 2. HISTORICAL PEEK LOGIC ---
    function updatePeekReport() {
        const mode = document.querySelector('input[name="peekMode"]:checked').value;
        let queryParams = `mode=${mode}`;

        if (mode === 'month') {
            document.getElementById('monthSelectors').style.setProperty('display', 'flex', 'important');
            document.getElementById('weekSelectors').style.setProperty('display', 'none', 'important');
            queryParams += `&month=${document.getElementById('reportMonth').value}&year=${document.getElementById('reportYear').value}`;
        } else {
            document.getElementById('monthSelectors').style.setProperty('display', 'none', 'important');
            document.getElementById('weekSelectors').style.setProperty('display', 'flex', 'important');
            queryParams += `&week=${document.getElementById('reportWeek').value}`;
        }

        fetch(`{{ route('admin.report.api') }}?${queryParams}`)
            .then(res => res.json())
            .then(data => {
                // Update Counts & Date
                document.getElementById('releaseCount').innerText = data.releases;
                document.getElementById('expiryCount').innerText = data.expirations;
                document.getElementById('formattedDate').innerText = data.formatted_date;

                // Update Log List
                const logContainer = document.getElementById('activityLogContainer');
                let logHtml = `<li class="dropdown-header fw-bold text-uppercase border-bottom pb-2 mb-2">Activity Log</li>`;
                
                if (data.details && data.details.length > 0) {
                    data.details.forEach(log => {
                        const isReleased = log.action === 'Released';
                        logHtml += `
                            <li class="px-3 py-2 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-0 fw-bold small text-dark">${log.name}</h6>
                                        <p class="mb-0 text-muted" style="font-size: 0.75rem;">
                                            <span class="badge ${isReleased ? 'bg-primary-opacity text-primary border border-primary' : 'bg-danger-opacity text-danger border border-danger'} small">${log.action}</span>
                                            <br>${log.date}
                                        </p>
                                    </div>
                                    <span class="badge ${isReleased ? 'bg-primary' : 'bg-danger'} rounded-pill">
                                        ${log.qty}
                                    </span>
                                </div>
                            </li>`;
                    });
                } else {
                    logHtml += `<li class="px-3 py-3 text-center text-muted small">No activity found.</li>`;
                }
                logContainer.innerHTML = logHtml;

                // Update Chart
                if (peekChart) peekChart.destroy();
                const ctx = document.getElementById('peekChart').getContext('2d');
                peekChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Released', 'Expired'],
                        datasets: [{
                            data: [data.releases, data.expirations],
                            backgroundColor: ['#0d6efd', '#dc3545'],
                            borderWidth: 0
                        }]
                    },
                    options: { 
                        plugins: { legend: { display: false } }, 
                        cutout: '70%', 
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            })
            .catch(error => console.error('Error fetching report:', error));
    }

    // --- 3. INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', function () {
        // Init Trends
        fetchTrendsData();
        document.getElementById('trendsFilter').addEventListener('change', fetchTrendsData);

        // Init Peek
        updatePeekReport();
        
        // Listeners for Peek Controls
        const peekInputs = ['reportMonth', 'reportYear', 'reportWeek'];
        peekInputs.forEach(id => document.getElementById(id).addEventListener('change', updatePeekReport));
        
        document.querySelectorAll('input[name="peekMode"]').forEach(radio => {
            radio.addEventListener('change', updatePeekReport);
        });
    });
</script>
@endsection