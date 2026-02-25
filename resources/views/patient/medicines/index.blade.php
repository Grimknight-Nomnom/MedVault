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
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $med->name }}</div>
                            
                            {{-- Description Section with Read More Dropdown --}}
                            @if($med->description)
                                @if(strlen($med->description) > 60)
                                    <div class="small text-muted mt-1" style="max-width: 300px; line-height: 1.4;">
                                        <i class="fas fa-info-circle me-1 text-primary"></i>
                                        
                                        {{-- Short Description --}}
                                        <span id="shortDesc{{ $med->id }}">{{ Str::limit($med->description, 60) }}</span>
                                        
                                        {{-- Full Collapsible Description --}}
                                        <div class="collapse mt-2 bg-light p-2 rounded border border-secondary border-opacity-10" id="collapseDesc{{ $med->id }}">
                                            {{ $med->description }}
                                        </div>
                                        
                                        {{-- Toggle Button --}}
                                        <a href="#collapseDesc{{ $med->id }}" data-bs-toggle="collapse" 
                                           class="text-success text-decoration-none ms-1 fw-bold d-inline-block mt-1" 
                                           onclick="document.getElementById('shortDesc{{ $med->id }}').classList.toggle('d-none'); this.innerText = this.innerText === 'Read more ▼' ? 'Show less ▴' : 'Read more ▼';">Read more ▼</a>
                                    </div>
                                @else
                                    <div class="small text-muted mt-1" style="max-width: 300px; line-height: 1.4;">
                                        <i class="fas fa-info-circle me-1 text-primary"></i> {{ $med->description }}
                                    </div>
                                @endif
                            @else
                                <div class="small text-muted fst-italic mt-1">No description available.</div>
                            @endif
                        </td>
                        
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3">
                                {{ $med->category }}
                            </span>
                        </td>
                        
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
    
    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $medicines->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection