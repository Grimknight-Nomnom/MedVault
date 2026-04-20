@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-primary mb-1"><i class="fas fa-clipboard-list me-2"></i>Audit Logs</h2>
            <p class="text-muted small mb-0">Track all system activities and user actions.</p>
        </div>
        <a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="btn btn-success shadow-sm rounded-pill px-4">
            <i class="fas fa-download me-2"></i>Export to CSV
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.audit-logs.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-bold small">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small">Module</label>
                        <select name="module" class="form-select">
                            <option value="">All Modules</option>
                            @foreach($modules as $module)
                                <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                    {{ ucfirst($module) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small">User</label>
                        <select name="user" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}" {{ request('user') == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->user_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-12">
                        <input type="text" name="search" class="form-control" placeholder="Search by user, record name, or description..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-12">
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-light border">Clear Filters</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Audit Logs Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Activity Log ({{ $auditLogs->total() }} records)</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3">Date & Time</th>
                            <th class="py-3">User</th>
                            <th class="py-3">Action</th>
                            <th class="py-3">Module</th>
                            <th class="py-3">Record</th>
                            <th class="py-3">Description</th>
                            <th class="py-3">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditLogs as $log)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $log->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $log->user_name }}</span>
                            </td>
                            <td>
                                @if($log->action === 'created')
                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3">
                                        <i class="fas fa-plus-circle me-1"></i>Created
                                    </span>
                                @elseif($log->action === 'updated')
                                    <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-3">
                                        <i class="fas fa-edit me-1"></i>Updated
                                    </span>
                                @elseif($log->action === 'deleted')
                                    <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3">
                                        <i class="fas fa-trash-alt me-1"></i>Deleted
                                    </span>
                                @elseif($log->action === 'viewed')
                                    <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3">
                                        <i class="fas fa-eye me-1"></i>Viewed
                                    </span>
                                @elseif($log->action === 'logged_in')
                                    <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill px-3">
                                        <i class="fas fa-sign-in-alt me-1"></i>Login
                                    </span>
                                @elseif($log->action === 'logged_out')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-3">
                                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-3">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border border-secondary rounded-pill px-3">
                                    {{ ucfirst($log->module) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-dark">{{ $log->record_name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small class="text-dark">{{ $log->description ?? '-' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $log->ip_address }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x opacity-25 mb-3"></i>
                                <p>No audit logs found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($auditLogs->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection