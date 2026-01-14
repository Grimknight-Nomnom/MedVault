<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedVault | Clinic Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9; /* Professional light grey background */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        .main-content {
            flex: 1;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        .footer {
            background: white;
            border-top: 1px solid #e3e6f0;
            padding: 1.5rem 0;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                <i class="fas fa-heartbeat me-2"></i>MedVault
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-chart-line me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.medicines.index') }}">
                                    <i class="fas fa-pills me-1"></i> Inventory
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.appointments.index') }}">
                                    <i class="fas fa-calendar-check me-1"></i> Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.patients.index') }}">
                                    <i class="fas fa-users me-1"></i> Patients
                                </a>
                            </li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-home me-1"></i> Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}"><i class="fas fa-user-circle me-1"></i> Profile</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('patient.records') }}"><i class="fas fa-file-medical me-1"></i> Medical History</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('patient.medicines.index') }}"><i class="fas fa-pills me-1"></i> Medicines</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('appointments.index') }}"><i class="fas fa-calendar-alt me-1"></i> Appointments</a></li>
                        @endif
                        
                        <li class="nav-item ms-3">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3" type="submit">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary text-white px-4 rounded-pill ms-2" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="footer text-center text-muted">
        <div class="container">
            <small>&copy; {{ date('Y') }} MedVault Clinic Systems. All rights reserved. <br> Protected by Patient Privacy Laws.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>