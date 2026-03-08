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
            background-color: #f4f6f9;
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
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="me-2" style="height: 48px; width: auto; object-fit: contain;">MedVault
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        {{-- CHECK IF USER IS ADMIN OR STAFF --}}
                        @if(in_array(Auth::user()->role, ['admin', 'staff']))
                            @php
                                // Dynamically grab the role to output either 'admin' or 'staff' in the route names
                                $rolePrefix = Auth::user()->role; 
                            @endphp
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route($rolePrefix . '.dashboard') }}">
                                    <i class="fas fa-chart-line me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route($rolePrefix . '.medicines.index') }}">
                                    <i class="fas fa-pills me-1"></i> Inventory
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route($rolePrefix . '.appointments.index') }}">
                                    <i class="fas fa-calendar-check me-1"></i> Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route($rolePrefix . '.patients.index') }}">
                                    <i class="fas fa-users me-1"></i> Patients
                                </a>
                            </li>

                            {{-- ========================================== --}}
                            {{-- NOTIFICATION BELL (Admin & Staff)          --}}
                            {{-- ========================================== --}}
                            @php
                                // Existing Checks
                                $notifAnnouncements = \App\Models\Announcement::where('is_active', true)->count();
                                $notifLowStock = \App\Models\Medicine::where('stock_quantity', '<', 10)->count();
                                $notifExpiring = \App\Models\Medicine::where('expiry_date', '<=', now()->addDays(30))->count();
                                
                                // NEW 1: Appointments ONLY for today that are pending
                                $notifTodayAppointments = \App\Models\Appointment::whereDate('appointment_date', \Carbon\Carbon::today())
                                    ->where('status', 'pending')
                                    ->count();

                                // NEW 2: Users who uploaded residency photo but are not yet verified
                                $notifPendingAccounts = \App\Models\User::where('role', 'user')
                                    ->whereNotNull('patient_photo_path')
                                    ->whereNull('admin_verified_at')
                                    ->count();

                                // Total Notification Count
                                $totalNotifs = $notifAnnouncements + $notifLowStock + $notifExpiring + $notifTodayAppointments + $notifPendingAccounts;
                            @endphp
                            
                            <li class="nav-item dropdown ms-2 me-2 border-start ps-3 d-flex align-items-center">
                                <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell fs-5 text-secondary"></i>
                                    @if($totalNotifs > 0)
                                        <span id="notifBadge" data-count="{{ $totalNotifs }}" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65em; margin-left: -5px; margin-top: 5px;">
                                            {{ $totalNotifs }}
                                            <span class="visually-hidden">New alerts</span>
                                        </span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="notifDropdown" style="min-width: 280px; max-height: 400px; overflow-y: auto;">
                                    <li><h6 class="dropdown-header fw-bold text-dark border-bottom pb-2 mb-1">Notifications</h6></li>
                                    
                                    @if($totalNotifs == 0)
                                        <li><span class="dropdown-item text-muted small py-3 text-center">No new notifications</span></li>
                                    @else
                                        
                                        {{-- Today's Appointments Alert --}}
                                        @if($notifTodayAppointments > 0)
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route($rolePrefix . '.appointments.index') }}">
                                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-calendar-day text-primary"></i></div>
                                                    <div>
                                                        <p class="mb-0 small fw-bold">{{ $notifTodayAppointments }} New Appointment(s) Today</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Pending Account Verification Alert --}}
                                        @if($notifPendingAccounts > 0)
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route($rolePrefix . '.patients.index') }}">
                                                    <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-user-check text-success"></i></div>
                                                    <div>
                                                        <p class="mb-0 small fw-bold">{{ $notifPendingAccounts }} Account(s) Need Verification</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Low Stock Alert --}}
                                        @if($notifLowStock > 0)
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route($rolePrefix . '.medicines.index') }}">
                                                    <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-exclamation-triangle text-danger"></i></div>
                                                    <div>
                                                        <p class="mb-0 small fw-bold">{{ $notifLowStock }} Low Stock Item(s)</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Expiry Alert --}}
                                        @if($notifExpiring > 0)
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route($rolePrefix . '.medicines.index') }}">
                                                    <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-hourglass-half text-info"></i></div>
                                                    <div>
                                                        <p class="mb-0 small fw-bold">{{ $notifExpiring }} Expiring Medicine(s)</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Announcements Alert --}}
                                        @if($notifAnnouncements > 0)
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route($rolePrefix . '.announcements.index') }}">
                                                    <div class="bg-warning bg-opacity-10 p-2 rounded-circle me-3"><i class="fas fa-bullhorn text-warning"></i></div>
                                                    <div>
                                                        <p class="mb-0 small fw-bold">{{ $notifAnnouncements }} Active Announcement(s)</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endif

                                    @endif
                                </ul>
                            </li>
                            {{-- ========================================== --}}

                        @else
                            {{-- PATIENT LINKS --}}
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-home me-1"></i> Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}"><i class="fas fa-user-circle me-1"></i> Profile</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('patient.records') }}"><i class="fas fa-file-medical me-1"></i> Medical History</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('patient.medicines.index') }}"><i class="fas fa-pills me-1"></i> Medicines</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('appointments.index') }}"><i class="fas fa-calendar-alt me-1"></i> Appointments</a></li>
                        @endif
                        
                        {{-- LOGOUT BUTTON --}}
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
        {{-- GLOBAL SUCCESS/ERROR ALERTS (Fixes double messages) --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4 mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4 mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4 mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bellIcon = document.getElementById('notifDropdown');
            const badge = document.getElementById('notifBadge');
            
            if (bellIcon && badge) {
                const currentCount = badge.getAttribute('data-count');
                const lastSeenCount = localStorage.getItem('lastSeenNotifCount');
                
                // If the user has already clicked the bell for this exact number of notifications, hide the badge
                if (lastSeenCount === currentCount) {
                    badge.style.display = 'none';
                }
                
                // When the bell is clicked, save the current count as "read" and hide the badge immediately
                bellIcon.addEventListener('click', function() {
                    localStorage.setItem('lastSeenNotifCount', currentCount);
                    badge.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>