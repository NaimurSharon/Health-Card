@extends('layouts.app')

@section('title', $doctor->exists ? 'Edit Doctor' : 'Add New Doctor')

@section('content')
<div class="space-y-6">
    <!-- Doctor Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ $doctor->exists ? 'Edit Doctor' : 'Add New Doctor' }}</h3>
            <button type="submit" form="doctor-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ $doctor->exists ? 'Update Doctor' : 'Create Doctor' }}
            </button>
        </div>
    </div>

    <!-- Doctor Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="doctor-form" 
              action="{{ $doctor->exists ? route('admin.doctors.update', $doctor) : route('admin.doctors.store') }}" 
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @if($doctor->exists)
                @method('PUT')
            @endif

            <!-- Hidden input for image removal -->
            <input type="hidden" name="remove_profile_image" id="remove_profile_image" value="0">
            
            <!-- Profile Image Section -->
            <div class="space-y-4">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Profile Image</h4>
                
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <!-- Current Profile Image -->
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <div id="current-image" class="{{ $doctor->profile_image ? '' : 'hidden' }}">
                                <img src="{{ $doctor->profile_image ? asset('public/storage/' . $doctor->profile_image) : asset('images/default-avatar.png') }}" 
                                     alt="{{ $doctor->name }}"
                                     class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg">
                                @if($doctor->exists && $doctor->profile_image)
                                <button type="button" 
                                        onclick="removeProfileImage()"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-lg">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                                @endif
                            </div>
                            
                            <!-- Default Avatar -->
                            <div id="default-avatar" class="{{ $doctor->profile_image ? 'hidden' : '' }}">
                                <div class="h-32 w-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                    <i class="fas fa-user-md text-white text-4xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Controls -->
                    <div class="flex-1 space-y-4">
                        <div>
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Profile Image
                            </label>
                            <input type="file" 
                                   name="profile_image" 
                                   id="profile_image"
                                   accept="image/*"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   onchange="previewImage(this)">
                            <p class="mt-1 text-xs text-gray-500">
                                Recommended: Square image, 500x500 pixels, JPG, PNG or WebP format. Max 2MB.
                            </p>
                            @error('profile_image')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Preview -->
                        <div id="image-preview" class="hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                            <div class="flex items-center gap-4">
                                <img id="preview" class="h-20 w-20 rounded-full object-cover border-2 border-blue-500">
                                <button type="button" 
                                        onclick="cancelImageUpload()"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    <i class="fas fa-times mr-1"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Personal Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Personal Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $doctor->name) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter doctor's full name">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $doctor->email) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter email address">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="text" name="phone" id="phone" 
                                   value="{{ old('phone', $doctor->phone) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., 01812345678">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   value="{{ old('date_of_birth', $doctor->date_of_birth ? $doctor->date_of_birth->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('date_of_birth')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                            <select name="gender" id="gender" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $doctor->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $doctor->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $doctor->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter complete address">{{ old('address', $doctor->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Professional Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Specialization -->
                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization *</label>
                            <select name="specialization" id="specialization" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Specialization</option>
                                @foreach($specializations as $spec)
                                    <option value="{{ $spec }}" {{ old('specialization', $doctor->specialization) == $spec ? 'selected' : '' }}>
                                        {{ $spec }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialization')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Qualifications -->
                        <div>
                            <label for="qualifications" class="block text-sm font-medium text-gray-700 mb-2">Qualifications *</label>
                            <input type="text" name="qualifications" id="qualifications" 
                                   value="{{ old('qualifications', $doctor->qualifications) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., MBBS, MD, FCPS, etc.">
                            @error('qualifications')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Experience -->
                        <div>
                            <label for="experience" class="block text-sm font-medium text-gray-700 mb-2">Experience *</label>
                            <input type="text" name="experience" id="experience" 
                                   value="{{ old('experience', $doctor->doctorDetail->experience ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., 10 years">
                            @error('experience')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Number -->
                        <div>
                            <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                            <input type="text" name="license_number" id="license_number" 
                                   value="{{ old('license_number', $doctor->doctorDetail->license_number ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., BMDC-12345">
                            @error('license_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hospital -->
                        <div>
                            <label for="hospital_id" class="block text-sm font-medium text-gray-700 mb-2">Hospital *</label>
                            <select name="hospital_id" id="hospital_id" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}" {{ old('hospital_id', $doctor->hospital_id) == $hospital->id ? 'selected' : '' }}>
                                        {{ $hospital->name }} - {{ $hospital->address }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hospital_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Department -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" name="department" id="department" 
                                   value="{{ old('department', $doctor->doctorDetail->department ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., Pediatrics Department">
                            @error('department')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Designation -->
                        <div>
                            <label for="designation" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                            <input type="text" name="designation" id="designation" 
                                   value="{{ old('designation', $doctor->doctorDetail->designation ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., Senior Consultant">
                            @error('designation')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Professional Bio</label>
                        <textarea name="bio" id="bio" rows="4"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Write a brief professional bio about the doctor...">{{ old('bio', $doctor->doctorDetail->bio ?? '') }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Languages -->
                    <div>
                        <label for="languages" class="block text-sm font-medium text-gray-700 mb-2">Languages Spoken</label>
                        <input type="text" name="languages" id="languages" 
                               value="{{ old('languages', $doctor->doctorDetail ? implode(', ', $doctor->doctorDetail->languages ?? []) : 'Bangla, English') }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               placeholder="e.g., Bangla, English, Hindi (comma separated)">
                        <p class="mt-1 text-xs text-gray-500">Enter languages separated by commas</p>
                        @error('languages')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fees Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Fee Structure (BDT)</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Consultation Fee -->
                        <div>
                            <label for="consultation_fee" class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee *</label>
                            <input type="number" name="consultation_fee" id="consultation_fee" step="0.01" min="0"
                                   value="{{ old('consultation_fee', $doctor->doctorDetail->consultation_fee ?? '500') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="0.00">
                            @error('consultation_fee')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Follow-up Fee -->
                        <div>
                            <label for="follow_up_fee" class="block text-sm font-medium text-gray-700 mb-2">Follow-up Fee</label>
                            <input type="number" name="follow_up_fee" id="follow_up_fee" step="0.01" min="0"
                                   value="{{ old('follow_up_fee', $doctor->doctorDetail->follow_up_fee ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="0.00">
                            @error('follow_up_fee')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Emergency Fee -->
                        <div>
                            <label for="emergency_fee" class="block text-sm font-medium text-gray-700 mb-2">Emergency Fee</label>
                            <input type="number" name="emergency_fee" id="emergency_fee" step="0.01" min="0"
                                   value="{{ old('emergency_fee', $doctor->doctorDetail->emergency_fee ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="0.00">
                            @error('emergency_fee')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Max Patients Per Day -->
                    <div class="max-w-xs">
                        <label for="max_patients_per_day" class="block text-sm font-medium text-gray-700 mb-2">Max Patients Per Day</label>
                        <input type="number" name="max_patients_per_day" id="max_patients_per_day" min="1" max="50"
                               value="{{ old('max_patients_per_day', $doctor->doctorDetail->max_patients_per_day ?? 20) }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        @error('max_patients_per_day')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Availability Schedule Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Weekly Availability Schedule</h4>
                    <p class="text-sm text-gray-600 mb-4">Set the doctor's weekly schedule. Check the days when the doctor is available.</p>
                    
                    <div class="space-y-4" id="availability-container">
                        @php
                            $daysOfWeek = [
                                'sunday' => 'Sunday',
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday', 
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday'
                            ];
                            
                            $existingAvailabilities = $doctor->doctorAvailabilities->keyBy('day_of_week') ?? [];
                        @endphp
                
                        @foreach($daysOfWeek as $dayKey => $dayName)
                            @php
                                $availability = $existingAvailabilities[$dayKey] ?? null;
                                $isEnabled = $availability ? true : false;
                            @endphp
                            <div class="availability-day p-4 border border-gray-200 rounded-lg bg-white/50">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="flex items-center text-sm font-medium text-gray-700">
                                        <input type="checkbox" name="availabilities[{{ $dayKey }}][enabled]" 
                                               value="1" 
                                               {{ $isEnabled ? 'checked' : '' }}
                                               class="day-toggle mr-3 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                               data-day="{{ $dayKey }}">
                                        {{ $dayName }}
                                    </label>
                                </div>
                                
                                <div class="availability-fields grid grid-cols-1 md:grid-cols-4 gap-4 {{ $isEnabled ? '' : 'hidden' }}" 
                                     id="fields-{{ $dayKey }}">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Start Time *</label>
                                        <input type="time" name="availabilities[{{ $dayKey }}][start_time]" 
                                               value="{{ old("availabilities.$dayKey.start_time", $availability ? $availability->start_time->format('H:i') : '09:00') }}"
                                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-200"
                                               {{ $isEnabled ? 'required' : '' }}>
                                        @error("availabilities.$dayKey.start_time")
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">End Time *</label>
                                        <input type="time" name="availabilities[{{ $dayKey }}][end_time]" 
                                               value="{{ old("availabilities.$dayKey.end_time", $availability ? $availability->end_time->format('H:i') : '17:00') }}"
                                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-200"
                                               {{ $isEnabled ? 'required' : '' }}>
                                        @error("availabilities.$dayKey.end_time")
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Slot Duration (min)</label>
                                        <input type="number" name="availabilities[{{ $dayKey }}][slot_duration]" 
                                               value="{{ old("availabilities.$dayKey.slot_duration", $availability ? $availability->slot_duration : 30) }}"
                                               min="15" max="120" step="15"
                                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Max Appointments</label>
                                        <input type="number" name="availabilities[{{ $dayKey }}][max_appointments]" 
                                               value="{{ old("availabilities.$dayKey.max_appointments", $availability ? $availability->max_appointments : 10) }}"
                                               min="1" max="30"
                                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('availabilities')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Account Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password {{ $doctor->exists ? '(Leave blank to keep current)' : '*' }}
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter password" {{ $doctor->exists ? '' : 'required' }}>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password {{ $doctor->exists ? '(Leave blank to keep current)' : '*' }}
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Confirm password" {{ $doctor->exists ? '' : 'required' }}>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="max-w-xs">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" 
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status', $doctor->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $doctor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.doctors.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ $doctor->exists ? 'Update Doctor' : 'Create Doctor' }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle availability fields
        const dayToggles = document.querySelectorAll('.day-toggle');
        dayToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const day = this.getAttribute('data-day');
                const fields = document.getElementById(`fields-${day}`);
                if (fields) {
                    fields.classList.toggle('hidden', !this.checked);
                    
                    // Toggle required attribute on time fields
                    const timeInputs = fields.querySelectorAll('input[type="time"]');
                    timeInputs.forEach(input => {
                        input.required = this.checked;
                    });
                    
                    // Clear time fields when unchecked
                    if (!this.checked) {
                        timeInputs.forEach(input => input.value = '');
                    }
                }
            });
            
            // Initialize required attributes
            const day = toggle.getAttribute('data-day');
            const fields = document.getElementById(`fields-${day}`);
            if (fields && toggle.checked) {
                const timeInputs = fields.querySelectorAll('input[type="time"]');
                timeInputs.forEach(input => {
                    input.required = true;
                });
            }
        });

        // Auto-resize textarea
        const addressTextarea = document.getElementById('address');
        const bioTextarea = document.getElementById('bio');
        
        [addressTextarea, bioTextarea].forEach(textarea => {
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
                
                // Trigger initial resize
                textarea.dispatchEvent(new Event('input'));
            }
        });

        // Add smooth interactions
        const inputs = document.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });

        // Form validation before submit
        const form = document.getElementById('doctor-form');
        form.addEventListener('submit', function(e) {
            let hasValidAvailability = false;
            dayToggles.forEach(toggle => {
                if (toggle.checked) {
                    const day = toggle.getAttribute('data-day');
                    const startTime = document.querySelector(`input[name="availabilities[${day}][start_time]"]`);
                    const endTime = document.querySelector(`input[name="availabilities[${day}][end_time]"]`);
                    
                    if (startTime && endTime && startTime.value && endTime.value) {
                        hasValidAvailability = true;
                    }
                }
            });
            
            if (!hasValidAvailability) {
                e.preventDefault();
                alert('Please set at least one available day with valid time slots.');
                return false;
            }
        });

        // Image upload and preview functionality
        window.previewImage = function(input) {
            const preview = document.getElementById('preview');
            const imagePreview = document.getElementById('image-preview');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(file);
            }
        };

        window.cancelImageUpload = function() {
            const input = document.getElementById('profile_image');
            const imagePreview = document.getElementById('image-preview');
            
            input.value = '';
            imagePreview.classList.add('hidden');
        };

        window.removeProfileImage = function() {
            const removeInput = document.getElementById('remove_profile_image');
            const currentImage = document.getElementById('current-image');
            const defaultAvatar = document.getElementById('default-avatar');
            
            // Set flag to remove image
            removeInput.value = '1';
            
            // Hide current image and show default avatar
            currentImage.classList.add('hidden');
            defaultAvatar.classList.remove('hidden');
            
            // Also clear any file input
            const fileInput = document.getElementById('profile_image');
            fileInput.value = '';
            
            // Hide preview if shown
            const imagePreview = document.getElementById('image-preview');
            imagePreview.classList.add('hidden');
        };

        // Reset remove flag if new image is selected
        const profileImageInput = document.getElementById('profile_image');
        if (profileImageInput) {
            profileImageInput.addEventListener('change', function() {
                const removeInput = document.getElementById('remove_profile_image');
                removeInput.value = '0';
                
                // Show current image section when new image is selected
                const currentImage = document.getElementById('current-image');
                const defaultAvatar = document.getElementById('default-avatar');
                currentImage.classList.remove('hidden');
                defaultAvatar.classList.add('hidden');
            });
        }
    });
</script>
@endsection