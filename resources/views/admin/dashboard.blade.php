@extends('layouts.app')

@section('content')
<div class="card border-primary">
    <div class="card-body">
        <h3 class="text-primary">Admin Dashboard</h3>
        <p>Welcome, {{ Auth::user()->name }}! You have full access to the system.</p>
        <div class="mt-3">
            <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-primary">Manage Inventory</a>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-dark">View Appointments</a>
        </div>
    </div>
</div>
@endsection