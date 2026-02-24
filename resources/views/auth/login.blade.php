<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - Barangay Looc Clinic</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
@vite(['resources/css/app.css', 'resources/js/app.js'])

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
                    <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="w-20 h-20 mb-8 object-contain">
                    <h1 class="text-4xl font-bold tracking-tight mb-2">Barangay Looc Clinic</h1>
                    <h2 class="text-2xl font-medium text-teal-200 mb-6">MedVault System</h2>
                    <p class="text-teal-100 text-lg leading-relaxed opacity-90 max-w-md">
                        Securely access your medical history, manage prescriptions, and track your health journey with our trusted community platform.
                    </p>
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
                        <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain shadow-md rounded-lg">
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

                    {{-- NEW: Verification Error and Resend Link --}}
                    @if(session('errors') && session('errors')->has('unverified'))
                        <div class="mb-6 p-4 rounded-xl bg-orange-50 border border-orange-100 flex flex-col gap-3">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="text-sm text-orange-800 font-medium">{{ session('errors')->first('unverified') }}</p>
                            </div>
                            
                            @if(session('unverified_identifier'))
                            <form id="resendVerifyForm" action="{{ route('verification.resend') }}" method="POST" class="ml-8">
                                @csrf
                                <input type="hidden" name="login_identifier" value="{{ session('unverified_identifier') }}">
                                <button type="submit" id="resendVerifyBtn" class="text-sm font-bold text-orange-600 hover:text-orange-700 transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    <span id="resendVerifyText">Resend Verification Link</span>
                                </button>
                            </form>
                            @endif
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
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700 transition-colors">
                                    Forgot Password?
                                </a>
                            </div>
                            
                            <div class="flex items-center w-full rounded-xl border border-gray-200 bg-gray-50 focus-within:bg-white focus-within:border-teal-500 focus-within:ring-2 focus-within:ring-teal-500/20 transition-all duration-200">
                                <input id="password" name="password" type="password" required 
                                    class="flex-1 block w-full px-4 py-3 bg-transparent text-gray-900 sm:text-sm border-0 focus:ring-0 outline-none placeholder:text-gray-400" 
                                    style="box-shadow: none;"
                                    placeholder="••••••••">
                                
                                <button type="button" onclick="togglePassword('password', 'eye-path-login')" class="px-4 text-gray-400 hover:text-teal-600 focus:outline-none bg-transparent">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path id="eye-path-login" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
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

    {{-- Frontend Javascript Timer and Toggle Password --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let cooldownKey = "verify_link_cooldown";
            let btn = document.getElementById("resendVerifyBtn");
            let txt = document.getElementById("resendVerifyText");

            if(btn && txt) {
                function updateTimer() {
                    let expireTime = localStorage.getItem(cooldownKey);
                    if(expireTime) {
                        let now = new Date().getTime();
                        let diff = Math.floor((expireTime - now) / 1000);
                        if(diff > 0) {
                            btn.disabled = true;
                            btn.classList.add('opacity-50', 'cursor-not-allowed');
                            txt.innerText = "Resend in " + diff + "s";
                            setTimeout(updateTimer, 1000);
                        } else {
                            btn.disabled = false;
                            btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            txt.innerText = "Resend Verification Link";
                            localStorage.removeItem(cooldownKey);
                        }
                    }
                }
                updateTimer();

                document.getElementById("resendVerifyForm").addEventListener("submit", function() {
                    localStorage.setItem(cooldownKey, new Date().getTime() + 30000); // Set 30s
                });
            }
        });

        // Toggle Password Logic
        function togglePassword(inputId, pathId) {
            const input = document.getElementById(inputId);
            const path = document.getElementById(pathId);
            
            if (input.type === 'password') {
                input.type = 'text';
                path.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
            } else {
                input.type = 'password';
                path.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
            }
        }
    </script>
</body>
</html>