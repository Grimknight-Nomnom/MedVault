@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h3>User Dashboard</h3>
        <p>Welcome, {{ Auth::user()->name }}! You are logged in as a Patient.</p>
    </div>
</div>
@endsection