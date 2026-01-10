@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Medicine Inventory</h2>
    <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">Add New Medicine</a>
</div>

<div class="card shadow">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Expiry</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                <tr>
                    <td>{{ $medicine->id }}</td>
                    <td>{{ $medicine->name }}</td>
                    <td><span class="badge bg-info">{{ $medicine->category }}</span></td>
                    <td>
                        @if($medicine->stock_quantity < 10)
                            <span class="text-danger fw-bold">{{ $medicine->stock_quantity }} (Low)</span>
                        @else
                            {{ $medicine->stock_quantity }}
                        @endif
                    </td>
                    <td>₱{{ number_format($medicine->price, 2) }}</td>
                    <td>{{ $medicine->expiry_date->format('M d, Y') }}</td>
                    <td>
                        <form action="{{ route('admin.medicines.delete', $medicine->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection