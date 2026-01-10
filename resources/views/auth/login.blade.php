@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                    <h3 class="fw-bold text-dark">Welcome Back</h3>
                    <p class="text-muted">Secure Login to MedVault</p>
                </div>

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput">Email Address</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg rounded-3">
                            Login to Account
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">New Patient? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Create Account</a></small>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mt-4 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection