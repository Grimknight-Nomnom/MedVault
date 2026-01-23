<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedVault - Barangay Looc Clinic</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    

    <style>
        html { scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Line Clamp for Announcement Text */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 selection:bg-green-100 selection:text-green-700">

    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-green-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold shadow-md group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12h2l2-3 2 6 2-3h2"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-gray-900">MedVault</span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="text-gray-600 hover:text-green-600 font-medium transition hover:underline decoration-2 underline-offset-4 decoration-green-500">Home</a>
                    <a href="#announcements" class="text-gray-600 hover:text-green-600 font-medium transition hover:underline decoration-2 underline-offset-4 decoration-green-500">Announcements</a>
                    <a href="#about" class="text-gray-600 hover:text-green-600 font-medium transition hover:underline decoration-2 underline-offset-4 decoration-green-500">About Us</a>
                    <a href="#details" class="text-gray-600 hover:text-green-600 font-medium transition hover:underline decoration-2 underline-offset-4 decoration-green-500">Details</a>
                    <a href="#staff" class="text-gray-600 hover:text-green-600 font-medium transition hover:underline decoration-2 underline-offset-4 decoration-green-500">Staff</a>
                </div>

<div class="flex items-center gap-4">
    @if (Route::has('login'))
        @auth
            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-green-600 transition">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-green-600 transition">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="hidden md:block bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-full transition shadow-lg shadow-green-200">
                    Get Started
                </a>
            @endif
        @endauth
    @endif
</div>
            </div>
        </div>
    </nav>

    @if(isset($announcements) && $announcements->count() > 0)
    <section id="announcements" class="py-12 bg-green-50 border-b border-green-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Latest Updates</h2>
                <div class="w-16 h-1.5 bg-green-500 mx-auto rounded-full mt-2"></div>
                <p class="mt-4 text-lg text-gray-600">Important news and events from Barangay Looc Clinic.</p>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($announcements as $announcement)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    @if($announcement->image_path)
                        <div class="h-48 w-full bg-gray-100 relative group">
                            <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                    @else
                        <div class="h-48 w-full bg-green-50 flex items-center justify-center border-b border-green-100">
                            <svg class="w-16 h-16 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $announcement->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight">{{ $announcement->title }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-3 leading-relaxed">
                            {{ $announcement->description }}
                        </p>
                        </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section id="home" class="relative pt-20 pb-24 lg:pt-32 lg:pb-40 overflow-hidden bg-white">
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-green-50/50 skew-x-12 opacity-70"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-green-50 text-green-700 text-sm font-semibold mb-6 border border-green-100">
                Welcome to Community Healthcare
            </span>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-gray-900 mb-6 leading-tight">
                Your Health Journey, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-green-800">Barangay Looc Clinic</span>
            </h1>
            
            <p class="mt-6 text-lg md:text-xl text-gray-600 max-w-4xl mx-auto mb-10 leading-relaxed">
                At Barangay Looc Clinic, we are committed to providing compassionate, reliable, and high-quality healthcare to our community. Led by Dr. Adelinno Labro and supported by our dedicated team of skilled nurses and staff, we prioritize patient safety, confidentiality, and personalized care.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-full shadow-lg hover:shadow-green-200/50 hover:-translate-y-1 transition-all duration-300">
                    Get Started
                </a>
                @endif
                <a href="#about" class="px-8 py-4 bg-white border-2 border-green-600 text-green-700 font-semibold rounded-full hover:bg-green-50 transition-all">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <section id="about" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">About Us</h2>
                <div class="w-20 h-1.5 bg-green-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6 text-lg text-gray-700 leading-relaxed">
                    <p>
                        In the 1980s, the City Health Office of Calamba was established by the barangay captain of Looc, aiming to enhance community health services. While it has become a vital resource for local residents, one of its biggest challenges is fostering effective communication with patients. Many individuals struggle to fully understand the information provided, often due to varying levels of health literacy.
                    </p>
                    <p>
                        The Barangay Looc Clinic, situated in Barangay Looc, Calamba, Laguna, serves as a vital healthcare resource for the local community, providing essential services such as free medical checkups and, when available, complementary medications. This community clinic is focused on delivering accessible healthcare, particularly to residents who may not have the means to visit larger facilities.
                    </p>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-green-600 rounded-2xl rotate-3 opacity-20"></div>
                    <div class="relative bg-white p-2 rounded-2xl shadow-xl">
<div class="aspect-[4/3] rounded-xl bg-gray-200 flex items-center justify-center overflow-hidden">
    <img src="{{ asset('Image/clinic.png') }}" alt="About Us Image" class="w-full h-full object-cover">
</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="details" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
                <p class="text-gray-600 text-lg">Guiding principles that drive our service to the community.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 border-t-4 border-t-green-500">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Mission</h3>
                    <p class="text-gray-600 text-lg">Provide efficient, effective, and quality public health Care.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 border-t-4 border-t-green-500">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Vision</h3>
                    <p class="text-gray-600 text-lg">A healthy City, A Healthy Community With A Health population.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 border-t-4 border-t-green-500">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Goal</h3>
                    <p class="text-gray-600 text-lg">To Improve Health Status of all Calambunos.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="staff" class="py-24 bg-green-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-16">Our Dedicated Staff</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Dr. Adelinno Labro</h3>
                    <p class="text-gray-500 font-medium">Doctor</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">John Paul Dela Cruz</h3>
                    <p class="text-gray-500 font-medium">Nurse</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Krystal Mae Anarna</h3>
                    <p class="text-gray-500 font-medium">Nurse</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Elena Divina</h3>
                    <p class="text-gray-500 font-medium">Nutrition Scholar</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Nena Alcaraz</h3>
                    <p class="text-gray-500 font-medium">Nutrition Scholar</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Lolita Mane</h3>
                    <p class="text-gray-500 font-medium">Nutrition Scholar</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Christine Manalac</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Roberta Manlapaz</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Fia Delima</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Corazon Alcala</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Roberta Alintanahin</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Precila Magpantay</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Charmaine Dazo</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Evangeline Ignacio</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col items-center hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-700">Marites Ilanes</h3>
                    <p class="text-gray-500 font-medium">Health Worker</p>
                </div>

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
            
            <p class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} Barangay Looc Clinic. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>