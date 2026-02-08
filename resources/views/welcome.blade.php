<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedVault - Barangay Looc Clinic</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Instrument Sans', sans-serif; }
        
        html { scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Fade In Animation */
        .animate-fade-in { animation: fadeIn 1s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
        
        /* Delay classes */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>
<body class="antialiased bg-white text-slate-600 selection:bg-emerald-500 selection:text-white">

    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-emerald-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <div class="flex items-center gap-3 cursor-pointer group" onclick="window.scrollTo(0,0)">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-600 rounded-lg sm:rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200 transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-heart-pulse text-base sm:text-lg"></i>
                    </div>
                    <div>
                        <span class="block font-bold text-lg sm:text-xl text-slate-900 leading-none">MedVault</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Barangay Looc</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#home" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Home</a>
                    <a href="#announcements" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Updates</a>
                    <a href="#about" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">About</a>
                    <a href="#services" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Services</a>
                    <a href="#staff" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Team</a>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-full hover:bg-slate-800 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="sm:hidden px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-full hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                                Login
                            </a>

                            <div class="hidden sm:flex items-center gap-4">
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">
                                    Login
                                </a>
                                
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-full hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                                        Register
                                    </a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="relative min-h-screen flex items-center overflow-hidden pt-16 sm:pt-0">
        
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('Image/clinic.png') }}" alt="Clinic Background" class="w-full h-full object-cover">
        </div>

        <div class="absolute inset-0 z-10" 
             style="background: linear-gradient(to right, rgba(236, 253, 245, 0.98) 0%, rgba(209, 250, 229, 0.85) 50%, rgba(255, 255, 255, 0) 100%);">
        </div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-10 sm:pt-20">
            <div class="max-w-2xl text-left">
                
                <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-4 sm:py-1.5 rounded-full bg-white border border-emerald-200 shadow-sm text-emerald-700 text-xs font-bold uppercase tracking-wide mb-6 sm:mb-8 animate-fade-in">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Trusted Community Healthcare
                </div>
                
                <h1 class="text-3xl sm:text-5xl md:text-7xl font-bold text-slate-900 tracking-tight mb-4 sm:mb-6 leading-tight sm:leading-[1.1] animate-fade-in delay-100">
                    Your Health Journey, <br>
                    <span class="text-emerald-700">Barangay Looc Clinic</span>
                </h1>
                
                <p class="text-sm sm:text-lg md:text-xl text-slate-600 mb-8 sm:mb-10 leading-relaxed max-w-xl animate-fade-in delay-200 font-medium">
                    At Barangay Looc Clinic, we are committed to providing compassionate, reliable, and high-quality healthcare to our community. Led by Dr. Adelinno Labro and supported by our dedicated team of skilled nurses and staff, we prioritize patient safety, confidentiality, and personalized care.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-5 animate-fade-in delay-300">
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="group relative px-6 py-3 sm:px-8 sm:py-4 bg-emerald-600 text-white font-bold text-sm sm:text-lg rounded-full overflow-hidden shadow-lg shadow-emerald-200/50 transition-all hover:scale-105 hover:shadow-emerald-300/50 hover:-translate-y-1 text-center">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Book Appointment <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </a>
                    @endif
                    <a href="#about" class="px-6 py-3 sm:px-8 sm:py-4 bg-white border border-slate-300 text-slate-700 font-bold text-sm sm:text-lg rounded-full hover:bg-slate-50 hover:border-emerald-300 hover:text-emerald-700 transition-all flex items-center justify-center gap-2 group shadow-sm">
                        Learn More 
                    </a>
                </div>

                <div class="mt-10 sm:mt-16 pt-6 sm:pt-8 border-t border-slate-300/50 flex gap-8 sm:gap-12 animate-fade-in delay-300">
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">40+</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Years Service</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">10k+</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Patients Served</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">100%</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Dedication</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($announcements) && $announcements->count() > 0)
    <section id="announcements" class="py-12 sm:py-24 bg-green-50 border-b border-green-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16" id="updates-header">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">Latest Updates</h2>
                <div class="w-12 h-1 sm:w-16 sm:h-1.5 bg-green-500 mx-auto rounded-full mt-2"></div>
                <p class="mt-3 sm:mt-4 text-sm sm:text-lg text-gray-600">Important news and events from Barangay Looc Clinic.</p>
            </div>

            <div class="grid gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($announcements as $key => $announcement)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 {{ $key >= 3 ? 'hidden extra-announcement' : '' }}">
                    @if($announcement->image_path)
                        <div class="h-48 sm:h-56 w-full bg-gray-100 relative group">
                            <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                    @else
                        <div class="h-48 sm:h-56 w-full bg-green-50 flex items-center justify-center border-b border-green-100">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        </div>
                    @endif
                    
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-green-100 text-green-800">
                                {{ $announcement->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <h3 class="text-base sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3 leading-tight">{{ $announcement->title }}</h3>
                        <p class="text-gray-600 text-xs sm:text-sm line-clamp-3 leading-relaxed">
                            {{ $announcement->description }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            @if($announcements->count() > 3)
            <div class="text-center" style="margin-top: 6rem;">
                <button id="viewMoreBtn" class="inline-flex items-center px-6 py-2.5 sm:px-8 sm:py-3 bg-white border border-green-300 rounded-full font-semibold text-sm sm:text-base text-green-700 tracking-wide hover:bg-green-50 transition-all duration-300 shadow-sm hover:shadow-md">
                    View More Updates
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <button id="showLessBtn" style="display: none;" class="hidden inline-flex items-center px-6 py-2.5 sm:px-8 sm:py-3 bg-white border border-gray-300 rounded-full font-semibold text-sm sm:text-base text-gray-700 tracking-wide hover:bg-gray-50 transition-all duration-300 shadow-sm hover:shadow-md">
                    Show Less
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const viewMoreBtn = document.getElementById('viewMoreBtn');
                    const showLessBtn = document.getElementById('showLessBtn');
                    const header = document.getElementById('updates-header');

                    if (viewMoreBtn && showLessBtn) {
                        viewMoreBtn.addEventListener('click', function() {
                            const hiddenCards = document.querySelectorAll('.extra-announcement');
                            hiddenCards.forEach(card => {
                                card.classList.remove('hidden');
                                card.classList.add('animate-fade-in');
                            });
                            this.style.display = 'none';
                            showLessBtn.style.display = 'inline-flex';
                            showLessBtn.classList.remove('hidden');
                        });

                        showLessBtn.addEventListener('click', function() {
                            const hiddenCards = document.querySelectorAll('.extra-announcement');
                            hiddenCards.forEach(card => {
                                card.classList.add('hidden');
                                card.classList.remove('animate-fade-in');
                            });
                            this.style.display = 'none';
                            viewMoreBtn.style.display = 'inline-flex';
                            header.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        });
                    }
                });
            </script>
            @endif
        </div>
    </section>
    @endif

    <section id="about" class="py-12 sm:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4">About Us</h2>
                <div class="w-16 sm:w-20 h-1.5 bg-green-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div class="space-y-6 text-sm sm:text-lg text-gray-700 leading-relaxed">
                    <p>
                        In the 1980s, the City Health Office of Calamba was established by the barangay captain of Looc, aiming to enhance community health services. While it has become a vital resource for local residents, one of its biggest challenges is fostering effective communication with patients. Many individuals struggle to fully understand the information provided, often due to varying levels of health literacy.
                    </p>
                    <p>
                        The Barangay Looc Clinic, situated in Barangay Looc, Calamba, Laguna, serves as a vital healthcare resource for the local community, providing essential services such as free medical checkups and, when available, complementary medications. This community clinic is focused on delivering accessible healthcare, particularly to residents who may not have the means to visit larger facilities.
                    </p>
                </div>

                <div class="relative group cursor-pointer">
                    <div class="absolute inset-0 bg-green-600 rounded-2xl rotate-3 opacity-20 transition-transform duration-500 group-hover:rotate-6"></div>
                    <div class="relative bg-white p-2 rounded-2xl shadow-xl transition-shadow duration-500 group-hover:shadow-2xl">
                        <div class="aspect-[4/3] rounded-xl bg-gray-200 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('Image/clinic.png') }}" alt="About Us Image" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="details" class="py-12 sm:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
                <p class="text-gray-600 text-sm sm:text-lg">Guiding principles that drive our service to the community.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-white p-5 sm:p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 border-t-4 border-t-green-500">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Mission</h3>
                    <p class="text-gray-600 text-sm sm:text-lg">Provide efficient, effective, and quality public health Care.</p>
                </div>

                <div class="bg-white p-5 sm:p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 border-t-4 border-t-green-500">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Vision</h3>
                    <p class="text-gray-600 text-sm sm:text-lg">A healthy City, A Healthy Community With A Health population.</p>
                </div>

                <div class="bg-white p-5 sm:p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 border-t-4 border-t-green-500">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Goal</h3>
                    <p class="text-gray-600 text-sm sm:text-lg">To Improve Health Status of all Calambunos.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="staff" class="py-12 sm:py-24 bg-green-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-10 sm:mb-16">Our Dedicated Staff</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 max-w-5xl mx-auto">
                
                @php
                    $staffMembers = [
                        ['name' => 'Dr. Adelinno Labro', 'role' => 'Doctor'],
                        ['name' => 'John Paul Dela Cruz', 'role' => 'Nurse'],
                        ['name' => 'Krystal Mae Anarna', 'role' => 'Nurse'],
                        ['name' => 'Elena Divina', 'role' => 'Nutrition Scholar'],
                        ['name' => 'Nena Alcaraz', 'role' => 'Nutrition Scholar'],
                        ['name' => 'Lolita Mane', 'role' => 'Nutrition Scholar'],
                        ['name' => 'Christine Manalac', 'role' => 'Health Worker'],
                        ['name' => 'Roberta Manlapaz', 'role' => 'Health Worker'],
                        ['name' => 'Fia Delima', 'role' => 'Health Worker'],
                        ['name' => 'Corazon Alcala', 'role' => 'Health Worker'],
                        ['name' => 'Roberta Alintanahin', 'role' => 'Health Worker'],
                        ['name' => 'Precila Magpantay', 'role' => 'Health Worker'],
                        ['name' => 'Charmaine Dazo', 'role' => 'Health Worker'],
                        ['name' => 'Evangeline Ignacio', 'role' => 'Health Worker'],
                        ['name' => 'Marites Ilanes', 'role' => 'Health Worker'],
                    ];
                @endphp

                @foreach($staffMembers as $staff)
                <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 sm:w-24 sm:h-24 bg-green-100 rounded-full flex items-center justify-center mb-3 sm:mb-4 text-green-600">
                         <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-green-700">{{ $staff['name'] }}</h3>
                    <p class="text-sm sm:text-base text-gray-500 font-medium">{{ $staff['role'] }}</p>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12h2l2-3 2 6 2-3h2"></path>
                    </svg>
                </div>
                <span class="font-bold text-xl">MedVault</span>
            </div>
            
            <p class="text-gray-400 text-sm text-center md:text-right">
                &copy; {{ date('Y') }} Barangay Looc Clinic. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>