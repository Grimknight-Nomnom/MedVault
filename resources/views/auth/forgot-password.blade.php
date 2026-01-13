<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - Barangay Looc Clinic</title>
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
            <div class="relative z-10 h-full flex flex-col justify-center">
                 <div class="mb-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 mb-8 shadow-xl">
                        <svg class="w-8 h-8 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight mb-2">Barangay Looc Clinic</h1>
                    <h2 class="text-2xl font-medium text-teal-200 mb-6">Account Recovery</h2>
                    <p class="text-teal-100 text-lg leading-relaxed opacity-90 max-w-md">Forgot your password? No problem. Just let us know your email address and we will email you a code to choose a new one.</p>
                </div>
            </div>
             <div class="relative z-10 text-sm text-teal-200/60">&copy; {{ date('Y') }} Barangay Looc Clinic.</div>
        </div>

        <div class="w-full lg:w-7/12 flex flex-col relative bg-white">
            <div class="absolute top-6 right-6 z-20">
                <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-medium text-gray-600 hover:text-teal-700 hover:bg-teal-50 transition-all duration-200">
                    Back to Login
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-teal-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 lg:px-24">
                <div class="w-full max-w-md mx-auto">
                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Forgot Password?</h2>
                        <p class="mt-2 text-gray-500">Enter your email to receive a verification code.</p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf
                        <div class="space-y-1.5">
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                            <input id="email" name="email" type="email" required autofocus
                                class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 outline-none placeholder:text-gray-400 sm:text-sm"
                                placeholder="name@example.com">
                            @error('email') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-teal-600/20 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                Send Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>