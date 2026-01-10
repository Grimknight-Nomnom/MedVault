<x-guest-layout>
    <div class="hidden lg:flex w-1/2 bg-slate-900 relative items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900"></div>
        <div class="absolute inset-0 opacity-20">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="url(#grad1)" />
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:rgb(56, 189, 248);stop-opacity:1" />
                        <stop offset="100%" style="stop-color:rgb(20, 184, 166);stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        
        <div class="relative z-10 p-12 text-white max-w-lg">
            <div class="w-12 h-12 bg-teal-500 rounded-2xl mb-8 flex items-center justify-center shadow-lg shadow-teal-500/30">
                <span class="font-bold text-xl text-white">M</span>
            </div>
            <h2 class="text-4xl font-bold mb-4 tracking-tight">Your Health, <br>Vaulted Securely.</h2>
            <p class="text-slate-300 text-lg leading-relaxed">
                Access your complete medical history, track prescriptions, and manage appointments with enterprise-grade security.
            </p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md space-y-8">
            <div class="text-center lg:text-left">
                <h2 class="text-3xl font-bold text-gray-900">Welcome back</h2>
                <p class="mt-2 text-sm text-gray-600">Enter your details to access your vault.</p>
            </div>

            @if(session('success'))
                <div class="p-4 rounded-xl bg-teal-50 text-teal-800 text-sm font-medium border border-teal-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="mt-8 space-y-6">
                @csrf

                <div class="space-y-1">
                    <label for="login_identifier" class="block text-sm font-medium text-gray-700">Email or User Number</label>
                    <input id="login_identifier" name="login_identifier" type="text" required autofocus
                        class="appearance-none block w-full px-4 py-3.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm bg-gray-50/50" 
                        placeholder="e.g. 105 or you@email.com"
                        value="{{ old('login_identifier') }}">
                    @error('login_identifier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    </div>
                    <input id="password" name="password" type="password" required 
                        class="appearance-none block w-full px-4 py-3.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm bg-gray-50/50"
                        placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-full shadow-lg shadow-teal-500/20 text-sm font-bold text-white bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all transform hover:-translate-y-0.5">
                        Sign In to Vault
                    </button>
                </div>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Don't have a vault? 
                        <a href="{{ route('register') }}" class="font-semibold text-teal-600 hover:text-teal-500 transition-colors">
                            Create an account
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>