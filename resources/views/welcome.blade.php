<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedVault - Secure Medical History</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        /* Smooth scrolling for anchor links */
        html { scroll-behavior: smooth; }
        /* Hide scrollbar for clean look in some elements */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 selection:bg-indigo-100 selection:text-indigo-700">

    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">M</div>
                    <span class="font-bold text-xl tracking-tight text-gray-900">MedVault</span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 font-medium transition">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-indigo-600 font-medium transition">How it Works</a>
                    <a href="#faq" class="text-gray-600 hover:text-indigo-600 font-medium transition">FAQs</a>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hidden md:block text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-full transition shadow-lg shadow-indigo-200">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-20 pb-24 lg:pt-32 lg:pb-40 overflow-hidden">
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0">
            <div class="absolute top-0 left-1/4 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-72 h-72 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight text-gray-900 mb-6 leading-[1.1]">
                Your Health Journey, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">One Secure Vault.</span>
            </h1>
            
            <p class="mt-4 text-xl text-gray-600 max-w-2xl mx-auto mb-10">
                Stop losing track of your prescriptions and records. Securely store, manage, and access your medical history anytime, anywhere.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-full shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    Get Started for Free
                </a>
                <a href="#how-it-works" class="px-8 py-4 bg-white border border-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all">
                    Learn More
                </a>
            </div>

            <div class="relative mx-auto max-w-5xl rounded-2xl border border-gray-200 bg-white/50 backdrop-blur-xl p-2 shadow-2xl">
                <div class="aspect-[16/9] rounded-xl bg-gray-100 overflow-hidden relative group cursor-default">
                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                         <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-indigo-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-gray-400 font-medium text-lg">Dashboard Preview</span>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 border-y border-gray-100 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-6">Trusted by Patients & Clinics for Privacy</p>
            <div class="flex flex-wrap justify-center gap-8 md:gap-16 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="flex items-center gap-2 text-xl font-bold text-gray-700"><span class="w-6 h-6 bg-gray-400 rounded-full"></span> MediCare</div>
                <div class="flex items-center gap-2 text-xl font-bold text-gray-700"><span class="w-6 h-6 bg-gray-400 rounded-full"></span> HealthPoint</div>
                <div class="flex items-center gap-2 text-xl font-bold text-gray-700"><span class="w-6 h-6 bg-gray-400 rounded-full"></span> SecureDocs</div>
                <div class="flex items-center gap-2 text-xl font-bold text-gray-700"><span class="w-6 h-6 bg-gray-400 rounded-full"></span> ClinicOne</div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Simplify Your Health in 3 Steps</h2>
                <p class="text-lg text-gray-600">No complex onboarding. Just secure, simple management.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-indigo-100 to-transparent -z-10"></div>

                <div class="relative bg-white p-6 rounded-2xl border border-transparent hover:border-gray-100 hover:shadow-xl transition-all duration-300 group text-center">
                    <div class="w-16 h-16 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 transition-transform">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Create Your Vault</h3>
                    <p class="text-gray-600 leading-relaxed">Sign up in seconds and build your personal health profile. It's your digital filing cabinet.</p>
                </div>

                <div class="relative bg-white p-6 rounded-2xl border border-transparent hover:border-gray-100 hover:shadow-xl transition-all duration-300 group text-center">
                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 transition-transform">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Upload Records</h3>
                    <p class="text-gray-600 leading-relaxed">Snap a photo of prescriptions or upload lab PDFs. We digitize and organize them for you.</p>
                </div>

                <div class="relative bg-white p-6 rounded-2xl border border-transparent hover:border-gray-100 hover:shadow-xl transition-all duration-300 group text-center">
                    <div class="w-16 h-16 mx-auto bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 transition-transform">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Access Anywhere</h3>
                    <p class="text-gray-600 leading-relaxed">Retrieve your history during doctor visits or emergencies from any device, securely.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Why MedVault?</h2>
                <p class="text-lg text-gray-600">Designed for patients who want peace of mind, not administrative headaches.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Medicine Inventory</h3>
                    <p class="text-gray-600">Track your current stock. Get notified before you run out of essential maintenance meds.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Appointment Tracker</h3>
                    <p class="text-gray-600">Never miss a check-up. Log upcoming visits and keep notes on doctor's advice.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Encrypted Privacy</h3>
                    <p class="text-gray-600">Your health data is sensitive. We use industry-standard encryption to keep it yours.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Digital Prescriptions</h3>
                    <p class="text-gray-600">Stop carrying crumpled papers. Show your digital prescription at the pharmacy.</p>
                </div>
                
                 <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Family Profiles</h3>
                    <p class="text-gray-600">Manage the health records of your children or elderly parents from one account.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 flex items-center justify-center bg-indigo-50">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-indigo-900 mb-3">Ready to organize?</h3>
                        <a href="{{ route('register') }}" class="inline-flex items-center text-indigo-600 font-semibold hover:underline">
                            Create Free Account &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Frequently Asked Questions</h2>
            
            <div class="space-y-4">
                <details class="group bg-gray-50 rounded-2xl p-6 [&_summary::-webkit-details-marker]:hidden cursor-pointer open:bg-white open:shadow-lg open:ring-1 open:ring-black/5 transition-all duration-300">
                    <summary class="flex items-center justify-between text-lg font-semibold text-gray-900">
                        Is my medical data safe?
                        <span class="ml-4 flex-shrink-0 transition duration-300 group-open:-rotate-180">
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <div class="mt-4 text-gray-600 leading-relaxed">
                        Absolutely. We use enterprise-grade encryption for all data storage. Your records are only accessible by you and authorized users you explicitly share them with.
                    </div>
                </details>

                <details class="group bg-gray-50 rounded-2xl p-6 [&_summary::-webkit-details-marker]:hidden cursor-pointer open:bg-white open:shadow-lg open:ring-1 open:ring-black/5 transition-all duration-300">
                    <summary class="flex items-center justify-between text-lg font-semibold text-gray-900">
                        Can I upload PDF lab results?
                        <span class="ml-4 flex-shrink-0 transition duration-300 group-open:-rotate-180">
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <div class="mt-4 text-gray-600 leading-relaxed">
                        Yes! MedVault supports image files (JPG, PNG) and PDF documents. You can categorize them by date or doctor name for easy retrieval.
                    </div>
                </details>

                <details class="group bg-gray-50 rounded-2xl p-6 [&_summary::-webkit-details-marker]:hidden cursor-pointer open:bg-white open:shadow-lg open:ring-1 open:ring-black/5 transition-all duration-300">
                    <summary class="flex items-center justify-between text-lg font-semibold text-gray-900">
                        Is MedVault free to use?
                        <span class="ml-4 flex-shrink-0 transition duration-300 group-open:-rotate-180">
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <div class="mt-4 text-gray-600 leading-relaxed">
                        The core features—storing records, tracking medicines, and appointments—are completely free for individual users.
                    </div>
                </details>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-300 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-4 text-white">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center font-bold">M</div>
                    <span class="font-bold text-xl">MedVault</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Empowering patients to take control of their medical history. Secure, simple, and always accessible.
                </p>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-4">Product</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-indigo-400 transition">Features</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Security</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Pricing</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-4">Company</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-indigo-400 transition">About Us</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Careers</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-semibold mb-4">Get in touch</h4>
                <ul class="space-y-2 text-sm">
                    <li>support@medvault.com</li>
                    <li>+1 (555) 123-4567</li>
                    <li class="flex gap-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Facebook</span>FB</a>
                        <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Twitter</span>TW</a>
                        <a href="#" class="text-gray-400 hover:text-white"><span class="sr-only">Instagram</span>IG</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} MedVault. All rights reserved.
        </div>
    </footer>
</body>
</html>