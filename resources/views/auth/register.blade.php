<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - Barangay Looc Clinic</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-day.selected {
            background: #0d9488 !important;
            border-color: #0d9488 !important;
        }
        .custom-year-select {
            margin-left: 5px;
            padding: 2px;
            border-radius: 4px;
            border: 1px solid transparent;
            background: transparent;
            font-weight: 500;
            color: inherit;
            cursor: pointer;
        }
        .custom-year-select:hover {
            background: rgba(0,0,0,0.05);
        }
        
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-gray-900 antialiased selection:bg-teal-100 selection:text-teal-900">

    <div class="min-h-screen w-full flex">
        
        <div class="hidden lg:flex lg:w-5/12 relative bg-teal-900 flex-col justify-center overflow-hidden p-12 text-white">
            <div class="absolute inset-0 bg-gradient-to-bl from-slate-900 to-teal-900"></div>
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 32px 32px;"></div>
            
            <div class="relative z-10 max-w-md mx-auto">
                <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="w-20 h-20 mb-8 object-contain">

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
                        <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain shadow-md rounded-lg">
                        <span class="font-bold text-xl text-gray-900">Barangay Looc Clinic</span>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Create your account</h2>
                        <p class="mt-2 text-gray-500">Fill in your details to generate your secure Medical ID.</p>
                    </div>

                    @if($errors->has('email') || $errors->has('first_name'))
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-red-800 font-medium">{{ $errors->first('email') ?: $errors->first('first_name') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="space-y-5">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Personal Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">First Name</label>
                                    <input type="text" name="first_name" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('first_name') }}">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Middle Name <span class="font-normal text-gray-400">(Optional)</span></label>
                                    <input type="text" name="middle_name" class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('middle_name') }}">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Last Name</label>
                                <input type="text" name="last_name" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('last_name') }}">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Date of Birth</label>
                                    <input type="text" name="date_of_birth" id="date_of_birth" required 
                                        class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-white text-gray-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm cursor-pointer" 
                                        placeholder="Select Date..."
                                        value="{{ old('date_of_birth') }}">
                                </div>

                                
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Age</label>
                                    <input type="text" name="age" id="age" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-100 text-gray-500 focus:outline-none sm:text-sm" value="{{ old('age') }}" readonly tabindex="-1">
                                </div>
                            </div>

                            
                            <div class="space-y-1.5">
    <label for="gender" class="block text-sm font-semibold text-gray-700">Gender</label>
    <select id="gender" name="gender" required 
        class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 outline-none sm:text-sm">
        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
    </select>
    @error('gender')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="space-y-1.5">
    <label for="civil_status" class="block text-sm font-semibold text-gray-700">Civil Status</label>
    <select id="civil_status" name="civil_status" required 
        class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 outline-none sm:text-sm">
        <option value="" disabled {{ old('civil_status') ? '' : 'selected' }}>Select Civil Status</option>
        <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
        <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
        <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
        <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
    </select>
    @error('civil_status')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Phone Number</label>
                                <input type="text" name="phone" required maxlength="13" pattern="^\+639\d{9}$" placeholder="+639123456789"
                                    class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" 
                                    value="{{ old('phone', '+63') }}"
                                    oninput="
                                        this.value = this.value.replace(/[^\d+]/g, '');
                                        if (!this.value.startsWith('+63')) {
                                            this.value = '+63' + this.value.replace(/^\+?6?3?/, '');
                                        }
                                    "
                                    onkeydown="
                                        if ((event.key === 'Backspace' || event.key === 'Delete') && this.selectionStart <= 3) {
                                            event.preventDefault();
                                        }
                                    ">
                                @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Address</label>
                                <textarea name="address" rows="2" class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm resize-none">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-5 pt-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Residency Verification</h3>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700">Certificate of Indigency / Proof of Residence</label>
                                
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-center gap-4">
                                    <div class="relative group cursor-pointer flex-shrink-0" onclick="openImageModal()">
                                        <img src="{{ asset('Image/image_ff8e08.png') }}" alt="Sample Document" class="w-20 h-20 rounded-lg object-cover border border-gray-300 shadow-sm bg-white group-hover:opacity-75 transition-opacity duration-200">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <div class="bg-black/40 p-1.5 rounded-full">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-600 flex-1">
                                        <p class="font-medium text-gray-900 mb-1">Take a photo or upload a scan</p>
                                        <p class="text-xs mb-1.5">Please upload a clear copy of your Barangay Indigency or Proof of Residence. (PNG/JPG, Max 5MB).</p>
                                        <button type="button" onclick="openImageModal()" class="text-teal-600 hover:text-teal-700 text-xs font-semibold focus:outline-none flex items-center gap-1">
                                            View sample document
                                        </button>
                                    </div>
                                </div>

                                <input type="file" name="patient_photo" accept="image/jpeg, image/png, image/jpg, image/*" required 
                                    class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                
                                @error('patient_photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-5 pt-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Login Credentials</h3>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                                <input type="email" name="email" required class="block w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all outline-none sm:text-sm" value="{{ old('email') }}">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Password</label>
                                    <div class="flex items-center w-full rounded-xl border border-gray-200 bg-gray-50 focus-within:bg-white focus-within:border-teal-500 focus-within:ring-2 focus-within:ring-teal-500/20 transition-all">
                                        <input type="password" name="password" id="password" required minlength="8" 
                                               class="flex-1 block w-full px-4 py-3 bg-transparent text-gray-900 sm:text-sm border-0 focus:ring-0 outline-none" style="box-shadow: none;">
                                        
                                        <button type="button" onclick="togglePassword('password', 'eye-path-1')" class="px-4 text-gray-400 hover:text-teal-600 focus:outline-none bg-transparent">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path id="eye-path-1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                                    <div class="flex items-center w-full rounded-xl border border-gray-200 bg-gray-50 focus-within:bg-white focus-within:border-teal-500 focus-within:ring-2 focus-within:ring-teal-500/20 transition-all">
                                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8" 
                                               class="flex-1 block w-full px-4 py-3 bg-transparent text-gray-900 sm:text-sm border-0 focus:ring-0 outline-none" style="box-shadow: none;">
                                        
                                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-path-2')" class="px-4 text-gray-400 hover:text-teal-600 focus:outline-none bg-transparent">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path id="eye-path-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
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

    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-gray-900/80 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0" onclick="closeImageModal()">
        <div class="relative max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="modalContent" onclick="event.stopPropagation()">
            
            <div class="absolute top-4 right-4 z-10">
                <button type="button" onclick="closeImageModal()" class="bg-black/50 hover:bg-black/70 text-white rounded-full p-2 focus:outline-none transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 bg-gray-100 flex justify-center items-center">
                <img src="{{ asset('Image/image_ff8e08.png') }}" alt="Sample Document Enlarged" class="w-auto h-auto max-w-full max-h-[50vh] object-contain rounded-lg shadow-md border border-gray-300 bg-white">
            </div>
            
            <div class="p-5 bg-white border-t border-gray-100">
                <h3 class="font-bold text-gray-900 text-lg">Acceptable Document Guidelines</h3>
                <ul class="text-sm text-gray-500 mt-2 space-y-1 list-disc list-inside">
                    <li>Ensure the document is well-lit and not blurry.</li>
                    <li>Make sure your Name and Address are clearly readable.</li>
                    <li>All four corners of the document should be visible.</li>
                </ul>
                <div class="mt-5 w-full">
                    <button type="button" onclick="closeImageModal()" class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                        Got it
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#date_of_birth", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            allowInput: true,
            onReady: function(selectedDates, dateStr, instance) {
                const yearInputWrapper = instance.currentYearElement.parentNode;
                const yearDropdown = document.createElement("select");
                yearDropdown.className = "custom-year-select flatpickr-monthDropdown-months";
                
                const currentYear = new Date().getFullYear();
                for (let i = currentYear; i >= 1920; i--) {
                    const option = document.createElement("option");
                    option.value = i;
                    option.text = i;
                    yearDropdown.appendChild(option);
                }
                
                yearDropdown.value = instance.currentYear;
                yearDropdown.addEventListener("change", function(e) {
                    instance.changeYear(Number(e.target.value));
                });
                instance.config.onYearChange.push(function() {
                    yearDropdown.value = instance.currentYear;
                });
                yearInputWrapper.parentNode.replaceChild(yearDropdown, yearInputWrapper);
            },
            onChange: function(selectedDates, dateStr, instance) {
                calculateAge(dateStr);
            }
        });

        function calculateAge(dobInput) {
            if (!dobInput) {
                document.getElementById('age').value = "";
                return;
            }

            let dob;
            if (dobInput.includes('-')) {
                const parts = dobInput.split('-');
                const year = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) - 1; 
                const day = parseInt(parts[2], 10);
                dob = new Date(year, month, day);
            } else {
                dob = new Date(dobInput);
            }

            if (isNaN(dob.getTime())) return;

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            let years = today.getFullYear() - dob.getFullYear();
            let months = today.getMonth() - dob.getMonth();
            let days = today.getDate() - dob.getDate();

            if (days < 0) {
                months--;
                const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                days += prevMonth.getDate();
            }
            if (months < 0) { 
                years--; 
                months += 12; 
            }

            let ageText = "";
            
            // STRICT LOGIC: Show ONLY years, OR months, OR days
            if (years > 0) {
                ageText = years + (years === 1 ? " year" : " years");
            } else if (months > 0) {
                ageText = months + (months === 1 ? " month" : " months");
            } else if (days > 0) {
                ageText = days + (days === 1 ? " day" : " days");
            } else {
                ageText = "Newborn";
            }

            const ageField = document.getElementById('age');
            if (ageField) {
                ageField.value = ageText;
            }
        }

        window.onload = function() {
            const existingDate = document.getElementById('date_of_birth').value;
            if(existingDate) {
                calculateAge(existingDate);
            }
        };

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

        // --- Image Modal Logic ---
        function openImageModal() {
            const modal = document.getElementById('imageModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>