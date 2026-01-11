@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-success small fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
        <h2 class="fw-bold mt-2">Medicine Availability</h2>
        <p class="text-muted">Check current availability of free clinic medications.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('patient.medicines.index') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control rounded-pill border-success shadow-none" 
                           placeholder="Search for medicine (e.g. Paracetamol)..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100 rounded-pill">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-success text-white">
                    <tr>
                        <th class="ps-4">Medicine Name</th>
                        <th>Category</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $med)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $med->name }}</td>
                        <td class="text-muted small">{{ $med->category }}</td>
                        <td class="text-center">
                            @if($med->stock_quantity > 0)
                                <span class="badge bg-success-subtle text-success border border-success px-3 rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i> Available
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 rounded-pill">
                                    <i class="fas fa-times-circle me-1"></i> Out of Stock
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">No medicines found matching your search.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $medicines->links() }}
    </div>
</div>
@endsection