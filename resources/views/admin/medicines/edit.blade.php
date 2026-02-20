@extends('layouts.app')

@section('content')
{{-- Flatpickr + MonthSelect Plugin CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<style>
    .flatpickr-day.selected, .flatpickr-monthSelect-month.selected { background: #0d6efd !important; border-color: #0d6efd !important; }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-warning text-dark fw-bold">Edit Medicine</div>
            <div class="card-body">
                <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Medicine Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $medicine->name }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" class="form-select" required>
                                @foreach(['Tablet', 'Pills', 'Syrup', 'Capsule', 'Drops'] as $cat)
                                    <option value="{{ $cat }}" {{ $medicine->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description / Usage Instructions</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $medicine->description) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="form-control" value="{{ $medicine->stock_quantity }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Expiry Date (Month/Year)</label>
                        <input type="text" 
                               name="expiry_date" 
                               id="expiry_date" 
                               class="form-control bg-white cursor-pointer" 
                               placeholder="Select Month & Year..."
                               value="{{ $medicine->expiry_date->format('Y-m') }}" 
                               required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning px-4">Update Medicine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Flatpickr + MonthSelect Plugin JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script>
    flatpickr("#expiry_date", {
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "Y-m",
                altFormat: "F Y"
            })
        ],
        minDate: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 1),
        allowInput: true
    });
</script>
@endsection