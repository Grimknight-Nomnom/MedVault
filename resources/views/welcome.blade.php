<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedVault - Barangay Looc Clinic</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Varela+Round&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Instrument Sans', sans-serif; }
        
        html { scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        .animate-fade-in { animation: fadeIn 1s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
        
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        .staff-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media (min-width: 640px) { .staff-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .staff-grid { grid-template-columns: repeat(4, 1fr); } }

        /* --- GALLERY CSS --- */
        .gallery {
            --g: 8px; /* the gap */
            display: grid;
            clip-path: inset(1px); 
            border-radius: 1.5rem; 
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            background-color: white; 
        }
        .gallery > img {
            --_p: calc(-1*var(--g));
            grid-area: 1/1;
            width: 100%; 
            aspect-ratio: 1;
            cursor: pointer;
            transition: .4s .1s;
            object-fit: cover;
        }
        .gallery > img:first-child {
            clip-path: polygon(0 0, calc(100% + var(--_p)) 0 , 0 calc(100% + var(--_p)));
        }
        .gallery > img:last-child {
            clip-path: polygon(100% 100%, 100% calc(0% - var(--_p)), calc(0% - var(--_p)) 100%);
        }
        .gallery:hover > img:last-child,
        .gallery:hover > img:first-child:hover{
            --_p: calc(50% - var(--g));
        }
        .gallery:hover > img:first-child,
        .gallery:hover > img:first-child:hover + img{
            --_p: calc(-50% - var(--g));
        }

        /* --- WAVY MENU CSS --- */
        .wavy-menu {
            display: flex;
            justify-content: center;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .wavy-menu li {
            width: 100px; /* Adjusted width for compact header */
            height: 50px;
            transition: background-position-x 0.9s linear;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wavy-menu li a {
            font-family: "Varela Round", sans-serif;
            font-size: 15px; /* Matches your previous font size */
            color: #475569; /* slate-600 */
            text-decoration: none;
            transition: all 0.45s;
            font-weight: 600;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Hover Effect */
        .wavy-menu li:hover {
            /* Converted the Red SVG to Emerald Green (#059669) */
            background: url('data:image/svg+xml;charset=utf-8,%3Csvg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="390px" height="50px" viewBox="0 0 390 50" enable-background="new 0 0 390 50" xml:space="preserve"%3E%3Cpath fill="none" stroke="%23059669" stroke-width="1.5" stroke-miterlimit="10" d="M0,47.585c0,0,97.5,0,130,0 c13.75,0,28.74-38.778,46.168-19.416C192.669,46.5,243.603,47.585,260,47.585c31.821,0,130,0,130,0"/%3E%3C/svg%3E');
            animation: line 0.50s;
            background-repeat: no-repeat;
            background-position: center bottom; 
        }

        .wavy-menu li:hover a {
            color: #059669; /* Emerald 600 */
        }

        @keyframes line {
            0% {
                background-position-x: 390px;
            }
        }
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

                <div class="hidden md:flex items-center">
                    <ul class="wavy-menu">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#announcements">Updates</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#staff">Team</a></li>
                    </ul>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-full hover:bg-slate-800 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="sm:hidden px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-full hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">Login</a>
                            <div class="hidden sm:flex items-center gap-4">
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-full hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">Register</a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="relative min-h-screen flex items-center pt-24 pb-12 lg:pt-0 lg:pb-0 overflow-hidden scroll-mt-20">
        
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('Image/clinic.png') }}" alt="Clinic Background" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 z-10" style="background: linear-gradient(to right, rgba(236, 253, 245, 0.98) 0%, rgba(209, 250, 229, 0.85) 50%, rgba(255, 255, 255, 0) 100%);"></div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full h-full">
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 lg:gap-12">
                
                <div class="w-full md:w-1/2 lg:w-5/12 text-left flex flex-col justify-center order-1">
                    
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-emerald-200 shadow-sm text-emerald-700 text-sm font-bold uppercase tracking-wide mb-5 animate-fade-in w-fit">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Trusted Community Healthcare
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-slate-900 tracking-tight mb-5 leading-tight animate-fade-in delay-100">
                        Your Health Journey, <br>
                        <span class="text-emerald-700">Barangay Looc Clinic</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg lg:text-xl text-slate-700 mb-8 font-medium leading-relaxed animate-fade-in delay-200 max-w-lg">
                        At Barangay Looc Clinic, we are committed to providing compassionate, reliable, and high-quality healthcare to our community.
                    </p>

                    <div class="flex flex-row gap-3 animate-fade-in delay-300">
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="group relative px-6 py-3 bg-emerald-600 text-white font-bold text-sm sm:text-base rounded-full shadow-lg shadow-emerald-200/50 transition-all hover:scale-105 hover:shadow-emerald-300/50 hover:-translate-y-1 text-center">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Book Appointment <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </a>
                        @endif
                        <a href="#about" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold text-sm sm:text-base rounded-full hover:bg-slate-50 hover:border-emerald-300 hover:text-emerald-700 transition-all flex items-center justify-center gap-2 group shadow-sm">
                            Learn More 
                        </a>
                    </div>
                </div>

                <div class="hidden md:flex w-full md:w-1/2 lg:w-5/12 items-center justify-center animate-fade-in delay-200 order-2">
                    <div class="gallery w-full max-w-[280px] sm:max-w-[320px] lg:max-w-[400px]">
                        <img src="{{ asset('Image/clinic.png') }}" alt="Clinic Exterior">
                        <img src="{{ asset('Image/background.jpg') }}" alt="Medical Equipment">
                    </div>
                </div>

            </div>
        </div>
    </section>

    @if(isset($announcements) && $announcements->count() > 0)
    <section id="announcements" class="min-h-screen flex flex-col justify-center py-12 sm:py-24 bg-green-50 border-b border-green-100 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16" id="updates-header">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">Latest Updates</h2>
                <div class="w-12 h-1 sm:w-16 sm:h-1.5 bg-green-500 mx-auto rounded-full mt-2"></div>
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

    <section id="about" class="min-h-screen flex flex-col justify-center py-12 sm:py-24 bg-white scroll-mt-20">
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

    <section id="details" class="min-h-screen flex flex-col justify-center py-12 sm:py-24 bg-gray-50 scroll-mt-20">
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

    <section id="staff" class="min-h-screen flex flex-col justify-center py-12 sm:py-24 bg-green-50/50 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center w-full">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-10 sm:mb-16">Our Dedicated Staff</h2>

            <div class="staff-grid w-full max-w-7xl mx-auto">
                
                @php
                    $staffMembers = [
                        ['name' => 'Dr. Adelinno Labro', 'role' => 'Doctor', 'image' => 'Image/staff1.png'],
                        ['name' => 'John Paul Dela Cruz', 'role' => 'Nurse', 'image' => null],
                        ['name' => 'Krystal Mae Anarna', 'role' => 'Nurse', 'image' => null],
                        ['name' => 'Elena Divina', 'role' => 'Nutrition Scholar', 'image' => null],
                        ['name' => 'Nena Alcaraz', 'role' => 'Nutrition Scholar', 'image' => null],
                        ['name' => 'Lolita Mane', 'role' => 'Nutrition Scholar', 'image' => null],
                        ['name' => 'Christine Manalac', 'role' => 'Health Worker', 'image' => null],
                        ['name' => 'Roberta Manlapaz', 'role' => 'Health Worker', 'image' => null],
                        ['name' => 'Fia Delima', 'role' => 'Health Worker', 'image' => null],
                        ['name' => 'Corazon Alcala', 'role' => 'Health Worker', 'image' => null],
                        ['name' => 'Roberta Alintanahin', 'role' => 'Health Worker', 'image' => null],
                        ['name' => 'Precila Magpantay', 'role' => 'Health Worker', 'image' => null],
                        ['name' => 'Charmaine Dazo', 'role' => 'Health Worker', 'image' => null],
                        ['name' => 'Evangeline Ignacio', 'role' => 'Health Worker', 'image' => null],
                        ['name' => 'Marites Ilanes', 'role' => 'Health Worker', 'image' => null],
                    ];
                @endphp

                @foreach($staffMembers as $staff)
                    @php
                        $imageSrc = $staff['image'] 
                            ? asset($staff['image']) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($staff['name']) . '&background=10b981&color=fff&size=512&font-size=0.33';
                    @endphp
                    
                    <div class="group relative aspect-[3/4] rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-slate-200">
                        <img src="{{ $imageSrc }}" alt="{{ $staff['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 w-full p-6 text-left transform translate-y-0 transition-transform duration-300">
                            <h3 class="text-white text-lg font-bold leading-tight drop-shadow-md">{{ $staff['name'] }}</h3>
                            <p class="text-emerald-400 text-xs font-bold tracking-widest uppercase mt-2 drop-shadow-sm">{{ $staff['role'] }}</p>
                        </div>
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