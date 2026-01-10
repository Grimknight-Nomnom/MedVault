@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">Add New Medicine</div>
            <div class="card-body">
                <form action="{{ route('admin.medicines.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label>Medicine Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Category</label>
                            <select name="category" class="form-select">
                                <option value="Antibiotic">Antibiotic</option>
                                <option value="Painkiller">Painkiller</option>
                                <option value="Supplement">Supplement</option>
                                <option value="Syrup">Syrup</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock_quantity" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Price (PHP)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Save Medicine</button>
                    <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection