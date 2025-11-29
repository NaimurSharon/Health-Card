<<<<<<< HEAD
@extends('layouts.app')

@section('title', 'Edit Profile')
=======
@extends('layouts.doctor')

@section('title', 'My Profile')
>>>>>>> c356163 (video call ui setup)

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
<<<<<<< HEAD
            <h3 class="text-2xl font-bold">Edit Profile</h3>
            <button type="submit" form="profile-form" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
=======
            <h3 class="text-2xl font-bold">My Profile</h3>
            <button type="submit" form="doctor-profile-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
>>>>>>> c356163 (video call ui setup)
                <i class="fas fa-save mr-2"></i>Update Profile
            </button>
        </div>
    </div>

<<<<<<< HEAD
    <!-- Profile Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="profile-form" action="{{ route('doctor.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

=======
    <!-- Doctor Profile Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="doctor-profile-form" 
              action="{{ route('doctor.profile.update') }}" 
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Hidden input for image removal -->
            <input type="hidden" name="remove_profile_image" id="remove_profile_image" value="0">
            <input type="hidden" name="remove_signature" id="remove_signature" value="0">
            
            <!-- Profile Image and Signature Section -->
            <div class="space-y-6">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Profile Images</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Profile Image -->
                    <div class="space-y-4">
                        <h5 class="text-lg font-medium text-gray-700">Profile Image</h5>
                        
                        <div class="flex flex-col items-center gap-4">
                            <!-- Current Profile Image -->
                            <div class="relative">
                                <div id="current-image" class="{{ $doctor->profile_image ? '' : 'hidden' }}">
                                    <img src="{{ $doctor->profile_image ? asset('public/storage/' . $doctor->profile_image) : asset('images/default-avatar.png') }}" 
                                         alt="{{ $doctor->name }}"
                                         class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg">
                                    @if($doctor->profile_image)
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

                            <!-- Profile Image Upload -->
                            <div class="w-full">
                                <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Profile Image
                                </label>
                                <input type="file" 
                                       name="profile_image" 
                                       id="profile_image"
                                       accept="image/*"
                                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       onchange="previewImage(this, 'profile')">
                                <p class="mt-1 text-xs text-gray-500">
                                    Recommended: Square image, 500x500 pixels, JPG, PNG or WebP format. Max 2MB.
                                </p>
                                @error('profile_image')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Profile Image Preview -->
                            <div id="profile-image-preview" class="hidden w-full">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                <div class="flex items-center gap-4">
                                    <img id="profile-preview" class="h-20 w-20 rounded-full object-cover border-2 border-blue-500">
                                    <button type="button" 
                                            onclick="cancelImageUpload('profile')"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        <i class="fas fa-times mr-1"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Signature Image -->
                    <div class="space-y-4">
                        <h5 class="text-lg font-medium text-gray-700">Digital Signature</h5>
                        
                        <div class="flex flex-col items-center gap-4">
                            <!-- Current Signature -->
                            <div class="relative">
                                <div id="current-signature" class="{{ $doctor->doctorDetail->signature ?? '' ? '' : 'hidden' }}">
                                    <img src="{{ $doctor->doctorDetail->signature ? asset('public/storage/' . $doctor->doctorDetail->signature) : '' }}" 
                                         alt="Doctor Signature"
                                         class="h-32 w-48 object-contain border-2 border-gray-300 bg-white rounded-lg shadow-sm">
                                    @if($doctor->doctorDetail->signature ?? '')
                                    <button type="button" 
                                            onclick="removeSignature()"
                                            class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-lg">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    @endif
                                </div>
                                
                                <!-- Default Signature Placeholder -->
                                <div id="default-signature" class="{{ $doctor->doctorDetail->signature ?? '' ? 'hidden' : '' }}">
                                    <div class="h-32 w-48 bg-gradient-to-br from-gray-100 to-gray-200 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center">
                                        <i class="fas fa-signature text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-xs text-gray-500 text-center">No Signature<br>Uploaded</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Signature Upload -->
                            <div class="w-full">
                                <label for="signature" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Digital Signature
                                </label>
                                <input type="file" 
                                       name="signature" 
                                       id="signature"
                                       accept="image/png,image/svg+xml"
                                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                                       onchange="previewImage(this, 'signature')">
                                <p class="mt-1 text-xs text-gray-500">
                                    Recommended: PNG format with transparent background. Max 1MB.
                                </p>
                                @error('signature')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Signature Preview -->
                            <div id="signature-image-preview" class="hidden w-full">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                <div class="flex items-center gap-4">
                                    <img id="signature-preview" class="h-16 w-32 object-contain border-2 border-green-500 bg-white rounded">
                                    <button type="button" 
                                            onclick="cancelImageUpload('signature')"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        <i class="fas fa-times mr-1"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

>>>>>>> c356163 (video call ui setup)
            <div class="space-y-8">
                <!-- Personal Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Personal Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" id="name" 
<<<<<<< HEAD
                                   value="{{ old('name', $user->name) }}" required
=======
                                   value="{{ old('name', $doctor->name) }}"
>>>>>>> c356163 (video call ui setup)
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter your full name">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" 
<<<<<<< HEAD
                                   value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter your email address">
=======
                                   value="{{ old('email', $doctor->email) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter email address">
>>>>>>> c356163 (video call ui setup)
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
<<<<<<< HEAD
                                   value="{{ old('phone', $user->phone) }}" required
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter your phone number">
=======
                                   value="{{ old('phone', $doctor->phone) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., 01812345678">
>>>>>>> c356163 (video call ui setup)
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
<<<<<<< HEAD
                                   value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
=======
                                   value="{{ old('date_of_birth', $doctor->date_of_birth ? $doctor->date_of_birth->format('Y-m-d') : '') }}"
>>>>>>> c356163 (video call ui setup)
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('date_of_birth')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
<<<<<<< HEAD
                            <select name="gender" id="gender" required
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
=======
                            <select name="gender" id="gender" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $doctor->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $doctor->gender) == 'female' ? 'selected' : '' }}>Female</option>
>>>>>>> c356163 (video call ui setup)
                            </select>
                            @error('gender')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
<<<<<<< HEAD
                        <textarea name="address" id="address" rows="3" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter your complete address">{{ old('address', $user->address) }}</textarea>
=======
                        <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter complete address">{{ old('address', $doctor->address) }}</textarea>
>>>>>>> c356163 (video call ui setup)
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
                            <input type="text" name="specialization" id="specialization" 
<<<<<<< HEAD
                                   value="{{ old('specialization', $user->specialization) }}" required
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter your medical specialization">
=======
                                   value="{{ old('specialization', $doctor->specialization) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., Pediatrics, Cardiology">
>>>>>>> c356163 (video call ui setup)
                            @error('specialization')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
<<<<<<< HEAD
                    </div>

                    <!-- Qualifications -->
                    <div>
                        <label for="qualifications" class="block text-sm font-medium text-gray-700 mb-2">Qualifications *</label>
                        <textarea name="qualifications" id="qualifications" rows="3" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter your qualifications and certifications">{{ old('qualifications', $user->qualifications) }}</textarea>
                        @error('qualifications')
=======

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
                                        {{ $hospital->name }}
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
                                placeholder="Write a brief professional bio about yourself...">{{ old('bio', $doctor->doctorDetail->bio ?? '') }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Languages -->
                    <div>
                        <label for="languages" class="block text-sm font-medium text-gray-700 mb-2">Languages Spoken</label>
                        <input type="text" name="languages" id="languages" 
                           value="{{ old('languages', is_array($doctor->doctorDetail->languages ?? null) 
                               ? implode(', ', $doctor->doctorDetail->languages) 
                               : ($doctor->doctorDetail->languages ?? 'Bangla, English')) }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="e.g., Bangla, English, Hindi (comma separated)">

                        <p class="mt-1 text-xs text-gray-500">Enter languages separated by commas</p>
                        @error('languages')
>>>>>>> c356163 (video call ui setup)
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

<<<<<<< HEAD
                <!-- Change Password Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Change Password</h4>
                    <p class="text-sm text-gray-600">Leave these fields blank if you don't want to change your password.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter current password">
                            @error('current_password')
=======
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
>>>>>>> c356163 (video call ui setup)
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

<<<<<<< HEAD
                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="new_password" id="new_password" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter new password">
                            @error('new_password')
=======
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
>>>>>>> c356163 (video call ui setup)
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

<<<<<<< HEAD
                    <!-- Confirm New Password -->
                    <div class="md:col-span-2">
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               placeholder="Confirm new password">
=======
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

                <!-- Password Change Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Change Password</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                New Password (Leave blank to keep current)
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter new password">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Confirm new password">
                        </div>
>>>>>>> c356163 (video call ui setup)
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('doctor.dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Update Profile
                </button>
            </div>
        </form>
    </div>
<<<<<<< HEAD

    <!-- Profile Information Card -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Profile Information</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Role</p>
                    <p class="text-lg font-semibold text-gray-900 capitalize">{{ $user->role }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Account Status</p>
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Member Since</p>
                    <p class="text-lg text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Last Login</p>
                    <p class="text-lg text-gray-900">
                        @if($user->last_login_at)
                            {{ $user->last_login_at->format('F j, Y g:i A') }}
                        @else
                            Never logged in
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Profile Last Updated</p>
                    <p class="text-lg text-gray-900">{{ $user->updated_at->format('F j, Y g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
=======
>>>>>>> c356163 (video call ui setup)
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
<<<<<<< HEAD
=======
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

>>>>>>> c356163 (video call ui setup)
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
<<<<<<< HEAD

        // Password field validation
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('new_password_confirmation');

        function validatePassword() {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords don't match");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        newPassword.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    });
=======
    });

    // Image upload and preview functionality
    window.previewImage = function(input, type) {
        const preview = document.getElementById(`${type}-preview`);
        const imagePreview = document.getElementById(`${type}-image-preview`);
        const file = input.files[0];
        
        if (file) {
            // Validate file type for signature
            if (type === 'signature') {
                const validTypes = ['image/png', 'image/svg+xml'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a PNG or SVG file for signature.');
                    input.value = '';
                    return;
                }
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(file);
        }
    };

    window.cancelImageUpload = function(type) {
        const input = document.getElementById(type === 'signature' ? 'signature' : 'profile_image');
        const imagePreview = document.getElementById(`${type}-image-preview`);
        
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
        const imagePreview = document.getElementById('profile-image-preview');
        imagePreview.classList.add('hidden');
    };

    window.removeSignature = function() {
        const removeInput = document.getElementById('remove_signature');
        const currentSignature = document.getElementById('current-signature');
        const defaultSignature = document.getElementById('default-signature');
        
        // Set flag to remove signature
        removeInput.value = '1';
        
        // Hide current signature and show default placeholder
        currentSignature.classList.add('hidden');
        defaultSignature.classList.remove('hidden');
        
        // Also clear any file input
        const fileInput = document.getElementById('signature');
        fileInput.value = '';
        
        // Hide preview if shown
        const imagePreview = document.getElementById('signature-image-preview');
        imagePreview.classList.add('hidden');
    };

    // Reset remove flags if new images are selected
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

    const signatureInput = document.getElementById('signature');
    if (signatureInput) {
        signatureInput.addEventListener('change', function() {
            const removeInput = document.getElementById('remove_signature');
            removeInput.value = '0';
            
            // Show current signature section when new signature is selected
            const currentSignature = document.getElementById('current-signature');
            const defaultSignature = document.getElementById('default-signature');
            currentSignature.classList.remove('hidden');
            defaultSignature.classList.add('hidden');
        });
    }
>>>>>>> c356163 (video call ui setup)
</script>
@endsection