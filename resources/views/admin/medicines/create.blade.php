@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white fw-bold">Add New Medicine</div>
            <div class="card-body">
                <form action="{{ route('admin.medicines.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Medicine Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Biogesic" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Pills">Pills</option>
                                <option value="Syrup">Syrup</option>
                                <option value="Capsule">Capsule</option>
                                <option value="Drops">Drops</option>
                            </select>
                        </div>
                        <div class="mb-3">
    <label for="description" class="form-label fw-bold">Description / Usage Instructions</label>
    <textarea name="description" id="description" class="form-control" rows="3" placeholder="e.g. Take after meals. Used for headaches.">{{ old('description') }}</textarea>
</div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="form-control" required>
                        </div>
                    </div>

<div class="mb-3">
    <label for="expiry_date" class="form-label fw-bold">Expiry Date (Month & Year)</label>
    <input type="month" 
           name="expiry_date" 
           id="expiry_date" 
           class="form-control @error('expiry_date') is-invalid @enderror" 
           required>
    <div class="form-text">Pick the month and year of expiration.</div>
    @error('expiry_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success px-4">Save Medicine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection