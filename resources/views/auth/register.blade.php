<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - Barangay Looc Clinic</title>
    
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
        
        <div class="hidden lg:flex lg:w-5/12 relative bg-teal-900 flex-col justify-center overflow-hidden p-12 text-white">
            <div class="absolute inset-0 bg-gradient-to-bl from-slate-900 to-teal-900"></div>
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 32px 32px;"></div>
            
            <div class="relative z-10 max-w-md mx-auto">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-teal-500 mb-8 shadow-lg shadow-teal-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12h2l2-3 2 6 2-3h2"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold mb-2 tracking-tight">Barangay Looc Clinic</h1>
                <h2 class="text-xl font-medium text-teal-200 mb-8">One ID for your entire health journey.</h2>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/10 flex items-center justify-center border border-white/10 mt-1">
                            <span class="text-teal-300 font-bold text-sm">1</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-white">Permanent Record</h3>
                            <p class="text-teal-100/80 text-sm leading-relaxed mt-1">Create once, use forever. Your history stays with you even if you change doctors.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/10 flex items-center justify-center border border-white/10 mt-1">
                            <span class="text-teal-300 font-bold text-sm">2</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-white">Digital Prescriptions</h3>
                            <p class="text-teal-100/80 text-sm leading-relaxed mt-1">Instant access to your prescriptions. No more lost papers at the pharmacy.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-7/12 flex flex-col relative bg-white h-screen overflow-y-auto">
            
            <div class="absolute top-6 right-6 z-20">
                <a href="{{ route('welcome') }}" class="group inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-medium text-gray-600 hover:text-teal-700 hover:bg-teal-50 transition-all duration-200">
                    Back to Home
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <div class="flex-1 flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-24">
                <div class="w-full max-w-lg mx-auto">
                    
                    <div class="lg:hidden mb-8 flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12h2l2-3 2 6 2-3h2"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-xl text-gray-900">Barangay Looc Clinic</span>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Create your account</h2>
                        <p class="mt-2 text-gray-500">Fill in your details to generate your secure Medical ID.</p>
                    </div>

                    {{-- Global Error Message (Duplicate Warning will appear here via 'email' key) --}}
                    @if($errors->has('email'))
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-red-800 font-medium">{{ $errors->first('email') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-5">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Personal Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">First Name</label>
                                    <input type="text" name="first_name" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('first_name') }}">
                                    @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Middle Name <span class="font-normal text-gray-400">(Optional)</span></label>
                                    <input type="text" name="middle_name" class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('middle_name') }}">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Last Name</label>
                                <input type="text" name="last_name" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('last_name') }}">
                                @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Date of Birth</label>
                                    <input type="date" name="date_of_birth" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Age</label>
                                    <input type="number" name="age" required min="0" class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('age') }}">
                                    @error('age') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Phone Number</label>
                                <input type="text" name="phone" class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('phone') }}">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Address</label>
                                <textarea name="address" rows="2" class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm resize-none">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-5 pt-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Login Credentials</h3>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                                <input type="email" name="email" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('email') }}">
                                {{-- We handled the global email error above, but keep this for standard validation --}}
                                @if(!$errors->has('email')) 
                                    @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Password</label>
                                    <input type="password" name="password" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm">
                                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-teal-600/20 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                Create Account
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-100 text-center pb-8">
                        <p class="text-sm text-gray-500">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="font-semibold text-teal-600 hover:text-teal-700 transition-colors">
                                Sign in
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>