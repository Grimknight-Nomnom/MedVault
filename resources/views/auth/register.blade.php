<x-guest-layout>
    <div class="hidden lg:flex w-1/2 bg-slate-900 relative items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-tl from-slate-900 via-slate-900 to-teal-900 opacity-90"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#14b8a6 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="relative z-10 p-12 text-white max-w-lg">
            <h2 class="text-4xl font-bold mb-6">Join MedVault Today.</h2>
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-teal-500/20 flex items-center justify-center text-teal-400 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Secure Records</h3>
                        <p class="text-slate-400 text-sm">Your history, fully encrypted.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-teal-500/20 flex items-center justify-center text-teal-400 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Family Management</h3>
                        <p class="text-slate-400 text-sm">Manage dependents easily.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white overflow-y-auto h-screen">
        <div class="w-full max-w-lg py-8">
            <div class="mb-8 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
                <p class="mt-2 text-sm text-gray-600">Enter your details to generate your secure ID.</p>
            </div>

            <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30" value="{{ old('first_name') }}">
                        @error('first_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Middle Name <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                        <input type="text" name="middle_name" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30" value="{{ old('middle_name') }}">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30" value="{{ old('last_name') }}">
                    @error('last_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" name="age" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30" value="{{ old('age') }}">
                        @error('age') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30" value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30">{{ old('address') }}</textarea>
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30" value="{{ old('email') }}">
                    @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30">
                        @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition bg-gray-50/30">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-full shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        Generate My Vault
                    </button>
                </div>

                <div class="text-center pb-8">
                    <span class="text-sm text-gray-600">Already registered? </span>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-500 transition-colors">
                        Log in here
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>