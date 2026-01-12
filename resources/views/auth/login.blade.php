<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - Barangay Looc Clinic</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Instrument Sans', 'sans-serif'] },
                    colors: {
                        teal: { 50: '#f0fdfa', 100: '#ccfbf1', 600: '#0d9488', 700: '#0f766e', 800: '#115e59', 900: '#134e4a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white font-sans text-gray-900 antialiased selection:bg-teal-100 selection:text-teal-900">

    <div class="min-h-screen w-full flex">
        
        <div class="hidden lg:flex lg:w-5/12 relative bg-teal-900 flex-col justify-between overflow-hidden p-12 text-white">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-800 to-slate-900"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#2dd4bf 1px, transparent 1px); background-size: 32px 32px;"></div>
            
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-teal-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

            <div class="relative z-10 h-full flex flex-col justify-center">
                <div class="mb-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 mb-8 shadow-xl">
                        <svg class="w-8 h-8 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12h2l2-3 2 6 2-3h2"></path>
                        </svg>
                    </div>
                    
                    <h1 class="text-4xl font-bold tracking-tight mb-2">Barangay Looc Clinic</h1>
                    <h2 class="text-2xl font-medium text-teal-200 mb-6">MedVault System</h2>
                    
                    <p class="text-teal-100 text-lg leading-relaxed opacity-90 max-w-md">
                        Securely access your medical history, manage prescriptions, and track your health journey with our trusted community platform.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-teal-500/20 flex items-center justify-center text-teal-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold">Official Health Portal</p>
                            <p class="text-sm text-teal-200">Serving the Looc Community.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-sm text-teal-200/60">
                &copy; {{ date('Y') }} Barangay Looc Clinic.
            </div>
        </div>

        <div class="w-full lg:w-7/12 flex flex-col relative bg-white">
            
            <div class="absolute top-6 right-6 z-20">
                <a href="{{ route('welcome') }}" class="group inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-medium text-gray-600 hover:text-teal-700 hover:bg-teal-50 transition-all duration-200">
                    Back to Home
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 lg:px-24">
                <div class="w-full max-w-md mx-auto">
                    
                    <div class="lg:hidden mb-8 flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12h2l2-3 2 6 2-3h2"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-xl text-gray-900">Barangay Looc Clinic</span>
                    </div>

                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Welcome back</h2>
                        <p class="mt-2 text-gray-500">Please enter your credentials to access the vault.</p>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-100 flex items-start gap-3">
                            <svg class="w-5 h-5 text-teal-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-teal-800 font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-1.5">
                            <label for="login_identifier" class="block text-sm font-semibold text-gray-700">Email or User ID</label>
                            <input id="login_identifier" name="login_identifier" type="text" required autofocus
                                class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 outline-none placeholder:text-gray-400 sm:text-sm"
                                placeholder="name@example.com"
                                value="{{ old('login_identifier') }}">
                            @error('login_identifier') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                            </div>
                            <input id="password" name="password" type="password" required 
                                class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 outline-none placeholder:text-gray-400 sm:text-sm"
                                placeholder="••••••••">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-teal-600/20 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <p class="text-sm text-gray-500">
                            New to MedVault? 
                            <a href="{{ route('register') }}" class="font-semibold text-teal-600 hover:text-teal-700 transition-colors">
                                Create an account
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>