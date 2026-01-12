@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Registered Patients</h2>
            <p class="text-muted small">Manage and view patient records.</p>
        </div>
        
        <form action="{{ route('admin.patients.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control border-success shadow-none" 
                   placeholder="Search Name or ID..." value="{{ request('search') }}" style="width: 250px;">
            <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary" title="Clear Search"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Patient List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase small text-muted">
                            <th class="py-3 ps-4">User ID</th>
                            <th class="py-3">Patient Name</th>
                            <th class="py-3">Age / Gender</th>
                            <th class="py-3">Contact</th>
                            <th class="py-3 text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td class="ps-4 fw-bold text-success">
                                #{{ $patient->usernumber }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                        <div class="small text-muted">Joined {{ $patient->created_at->format('M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $patient->age ?? 'N/A' }} y/o
                                </span>
                                <span class="badge {{ $patient->gender == 'Male' ? 'bg-info bg-opacity-10 text-info' : 'bg-danger bg-opacity-10 text-danger' }} border ms-1">
                                    {{ $patient->gender ?? '-' }}
                                </span>
                            </td>
                            <td class="small text-muted">
                                <i class="fas fa-phone me-1"></i> {{ $patient->phone ?? 'N/A' }}
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                    View Full Profile
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-user-slash fa-3x mb-3 opacity-50"></i>
                                <p>No patients found matching your search.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($patients->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $patients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection