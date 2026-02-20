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

    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Instrument Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Tailwind requires Keyframes to be defined in CSS or Config */
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 1s ease-out forwards; opacity: 0; transform: translateY(20px); }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        @keyframes line { 0% { background-position-x: 390px; } }
        .animate-wavy-line { animation: line 0.50s; }

        @keyframes color_anim { 0% { fill: white; } 50% { fill: #34d399; } 100% { fill: white; } }
        .animate-svg-color { animation: color_anim 1s infinite; }
    </style>
</head>
<body class="antialiased bg-white text-slate-600 selection:bg-emerald-500 selection:text-white">

    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-emerald-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <div class="flex items-center gap-3 cursor-pointer group" onclick="window.scrollTo(0,0)">
                    <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 transition-transform group-hover:scale-105 object-contain">
                    <div>
                        <span class="block font-bold text-lg sm:text-xl text-slate-900 leading-none">MedVault</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Barangay Looc</span>
                    </div>
                </div>

                {{-- WAVY MENU: Fully Tailwind Converted --}}
                <div class="hidden md:flex items-center">
                    <ul class="flex justify-center items-center m-0 p-0 list-none">
                        @foreach(['Home' => '#home', 'Updates' => '#announcements', 'About' => '#about', 'Team' => '#staff'] as $name => $link)
                        <li class="group w-[100px] h-[50px] text-center flex items-center justify-center transition-all duration-[0.3s] ease-linear hover:bg-[url('data:image/svg+xml;utf8,%3Csvg%20version=%221.1%22%20xmlns=%22http://www.w3.org/2000/svg%22%20width=%22390px%22%20height=%2250px%22%20viewBox=%220%200%20390%2050%22%3E%3Cpath%20fill=%22none%22%20stroke=%22%23059669%22%20stroke-width=%221.5%22%20d=%22M0,47.585c0,0,97.5,0,130,0%20c13.75,0,28.74-38.778,46.168-19.416C192.669,46.5,243.603,47.585,260,47.585c31.821,0,130,0,130,0%22/%3E%3C/svg%3E')] hover:bg-no-repeat hover:bg-bottom hover:animate-wavy-line">
                            <a href="{{ $link }}" class="font-['Varela_Round'] text-[15px] text-slate-600 no-underline transition-all duration-[0.2s] font-semibold w-full h-full flex items-center justify-center group-hover:text-emerald-600">
                                {{ $name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="sm:hidden px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-full hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">Login</a>
                        <div class="hidden sm:flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-full hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">Register</a>
                            @endif
                        </div>
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

                    <div class="flex flex-row gap-3 animate-fade-in delay-300 mb-8">
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="group relative px-6 py-3 bg-emerald-600 text-white font-bold text-sm sm:text-base rounded-full shadow-lg shadow-emerald-200/50 transition-all hover:scale-105 hover:shadow-emerald-300/50 hover:-translate-y-1 text-center">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Book Appointment <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </a>
                        @endif
                        <a href="#about" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold text-sm sm:text-base rounded-full hover:bg-slate-50 hover:border-emerald-300 hover:text-emerald-700 transition-all flex items-center justify-center gap-2 shadow-sm">
                            Learn More 
                        </a>
                    </div>

                    {{-- FACEBOOK BUTTON: Fully Tailwind Converted --}}
                    <div class="flex justify-start animate-fade-in delay-300">
                        <a href="https://www.facebook.com/bhs.looc" target="_blank" class="group flex items-center px-[35px] py-[12px] no-underline font-['Instrument_Sans'] text-[18px] text-white bg-[#059669] shadow-[5px_5px_0_#0f172a] rounded-[4px] -skew-x-[15deg] transition-all duration-[1s] hover:duration-[0.5s] hover:bg-[#047857] hover:shadow-[5px_5px_0_#34d399] outline-none">
                            <span class="font-bold tracking-wider skew-x-[15deg]">VISIT FACEBOOK</span>
                            <span class="flex items-center w-[25px] ml-[20px] mr-0 transition-all duration-[0.5s] skew-x-[15deg] group-hover:mr-[25px]">
                                <svg width="40px" height="26px" viewBox="0 0 66 43" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <path class="transition-transform duration-[0.4s] -translate-x-[60%] group-hover:translate-x-[0%] group-hover:animate-svg-color group-hover:[animation-delay:0.6s]" d="M40.1543933,3.89485454 L43.9763149,0.139296592 C44.1708311,-0.0518420739 44.4826329,-0.0518571125 44.6771675,0.139262789 L65.6916134,20.7848311 C66.0855801,21.1718824 66.0911863,21.8050225 65.704135,22.1989893 C65.7000188,22.2031791 65.6958657,22.2073326 65.6916762,22.2114492 L44.677098,42.8607841 C44.4825957,43.0519059 44.1708242,43.0519358 43.9762853,42.8608513 L40.1545186,39.1069479 C39.9575152,38.9134427 39.9546793,38.5968729 40.1481845,38.3998695 C40.1502893,38.3977268 40.1524132,38.395603 40.1545562,38.3934985 L56.9937789,21.8567812 C57.1908028,21.6632968 57.193672,21.3467273 57.0001876,21.1497035 C56.9980647,21.1475418 56.9959223,21.1453995 56.9937605,21.1432767 L40.1545208,4.60825197 C39.9574869,4.41477773 39.9546013,4.09820839 40.1480756,3.90117456 C40.1501626,3.89904911 40.1522686,3.89694235 40.1543933,3.89485454 Z" fill="#FFFFFF"></path>
                                        <path class="transition-transform duration-[0.5s] -translate-x-[30%] group-hover:translate-x-[0%] group-hover:animate-svg-color group-hover:[animation-delay:0.4s]" d="M20.1543933,3.89485454 L23.9763149,0.139296592 C24.1708311,-0.0518420739 24.4826329,-0.0518571125 24.6771675,0.139262789 L45.6916134,20.7848311 C46.0855801,21.1718824 46.0911863,21.8050225 45.704135,22.1989893 C45.7000188,22.2031791 45.6958657,22.2073326 45.6916762,22.2114492 L24.677098,42.8607841 C24.4825957,43.0519059 24.1708242,43.0519358 23.9762853,42.8608513 L20.1545186,39.1069479 C19.9575152,38.9134427 19.9546793,38.5968729 20.1481845,38.3998695 C20.1502893,38.3977268 20.1524132,38.395603 20.1545562,38.3934985 L36.9937789,21.8567812 C37.1908028,21.6632968 37.193672,21.3467273 37.0001876,21.1497035 C36.9980647,21.1475418 36.9959223,21.1453995 36.9937605,21.1432767 L20.1545208,4.60825197 C19.9574869,4.41477773 19.9546013,4.09820839 20.1480756,3.90117456 C20.1501626,3.89904911 20.1522686,3.89694235 20.1543933,3.89485454 Z" fill="#FFFFFF"></path>
                                        <path class="group-hover:animate-svg-color group-hover:[animation-delay:0.2s]" d="M0.154393339,3.89485454 L3.97631488,0.139296592 C4.17083111,-0.0518420739 4.48263286,-0.0518571125 4.67716753,0.139262789 L25.6916134,20.7848311 C26.0855801,21.1718824 26.0911863,21.8050225 25.704135,22.1989893 C25.7000188,22.2031791 25.6958657,22.2073326 25.6916762,22.2114492 L4.67709797,42.8607841 C4.48259567,43.0519059 4.17082418,43.0519358 3.97628526,42.8608513 L0.154518591,39.1069479 C-0.0424848215,38.9134427 -0.0453206733,38.5968729 0.148184538,38.3998695 C0.150289256,38.3977268 0.152413239,38.395603 0.154556228,38.3934985 L16.9937789,21.8567812 C17.1908028,21.6632968 17.193672,21.3467273 17.0001876,21.1497035 C16.9980647,21.1475418 16.9959223,21.1453995 16.9937605,21.1432767 L0.15452076,4.60825197 C-0.0425130651,4.41477773 -0.0453986756,4.09820839 0.148075568,3.90117456 C0.150162624,3.89904911 0.152268631,3.89694235 0.154393339,3.89485454 Z" fill="#FFFFFF"></path>
                                    </g>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>

                {{-- GALLERY SPLIT IMAGE: 100% Tailwind --}}
                <div class="hidden md:flex w-full md:w-1/2 lg:w-5/12 items-center justify-center animate-fade-in delay-200 order-2">
                    
                    <div class="group grid bg-white rounded-[1.5rem] shadow-[0_20px_25px_-5px_rgba(0,0,0,0.1)] [clip-path:inset(1px)] w-full max-w-[280px] sm:max-w-[320px] lg:max-w-[400px]">
                        
                        {{-- Top Left Image (Peer) --}}
                        <img src="{{ asset('Image/clinic.png') }}" alt="Clinic Exterior" 
                             class="peer col-start-1 row-start-1 w-full aspect-square object-cover cursor-pointer transition-all duration-[400ms] delay-[100ms] 
                                    [clip-path:polygon(0_0,calc(100%_-_8px)_0,0_calc(100%_-_8px))] 
                                    group-hover:[clip-path:polygon(0_0,calc(50%_-_8px)_0,0_calc(50%_-_8px))] 
                                    hover:![clip-path:polygon(0_0,calc(150%_-_8px)_0,0_calc(150%_-_8px))]">
                        
                        {{-- Bottom Right Image --}}
                        <img src="{{ asset('Image/background.jpg') }}" alt="Medical Equipment" 
                             class="col-start-1 row-start-1 w-full aspect-square object-cover cursor-pointer transition-all duration-[400ms] delay-[100ms] 
                                    [clip-path:polygon(100%_100%,100%_8px,8px_100%)] 
                                    group-hover:[clip-path:polygon(100%_100%,100%_calc(-50%_+_8px),calc(-50%_+_8px)_100%)] 
                                    peer-hover:![clip-path:polygon(100%_100%,100%_calc(50%_+_8px),calc(50%_+_8px)_100%)]">
                                    
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

    <section id="staff" class="min-h-screen py-24 bg-green-50/50 flex flex-col justify-center scroll-mt-20">
        <div class="text-center w-full mb-12">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">Our Dedicated Staff</h2>
            <div class="w-16 h-1.5 bg-green-500 mx-auto rounded-full mt-4"></div>
        </div>

        <div class="relative w-full max-w-[1100px] flex items-center justify-center flex-wrap p-[30px] mx-auto gap-4 pt-[60px]">
            @forelse(isset($staff) ? $staff : [] as $member)
                <div class="group relative w-[300px] h-[215px] bg-white mt-[40px] mb-[30px] px-[15px] py-[20px] flex flex-col shadow-[0_5px_20px_rgba(0,0,0,0.1)] transition-all duration-300 ease-in-out rounded-[15px] hover:h-[320px] hover:shadow-[0_10px_30px_rgba(0,0,0,0.15)]">
                    
                    <div class="relative w-[260px] h-[260px] -top-[40%] left-[5px] shadow-[0_5px_20px_rgba(0,0,0,0.2)] z-10 rounded-[15px] bg-gray-200 shrink-0">
                        @if($member->picture_path)
                            <img src="{{ asset('storage/' . $member->picture_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover rounded-[15px]">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=10b981&color=fff&size=512" alt="{{ $member->name }}" class="w-full h-full object-cover rounded-[15px]">
                        @endif
                    </div>
                    
                    <div class="relative -top-[140px] px-[15px] py-[10px] text-center invisible opacity-0 transition-all duration-300 ease-in-out group-hover:mt-[30px] group-hover:visible group-hover:opacity-100 group-hover:delay-200">
                        <h3 class="text-xl font-bold text-gray-900">{{ $member->name }}</h3>
                        <p class="text-sm mt-2 text-emerald-600 font-bold tracking-wider uppercase">{{ $member->role }}</p>
                    </div>
                    
                </div>
            @empty
                <div class="text-center text-gray-500 py-10 w-full">
                    <i class="fas fa-users-slash fa-3x mb-3 opacity-50"></i>
                    <p>No staff members have been added yet.</p>
                </div>
            @endforelse
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                <span class="font-bold text-xl">MedVault</span>
            </div>
            
            <p class="text-gray-400 text-sm text-center md:text-right">
                &copy; {{ date('Y') }} Barangay Looc Clinic. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>