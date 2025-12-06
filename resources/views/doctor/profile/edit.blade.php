@extends('layouts.doctor')

@section('title', 'Edit Profile')

@section('content')
    <div class="space-y-6">
            <!-- Page Header -->
            <div class="content-card rounded-lg overflow-hidden">
                <div class="table-header px-4 py-4 sm:px-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold">Edit Profile</h3>
                            <p class="text-gray-200 mt-1">Update your personal and professional information</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="{{ route('doctor.dashboard') }}"
                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="content-card rounded-lg p-4 bg-green-50 border border-green-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400 text-lg mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="content-card rounded-lg p-4 bg-red-50 border border-red-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 text-lg mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Profile Image & Basic Info -->
                    <div class="space-y-6">
                        <!-- Profile Image Card -->
                        <div class="content-card rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                                Profile Photo
                            </h4>
                            <div class="flex flex-col items-center">
                                <div class="relative">
                                    @if($doctor->profile_image)
                                        <img class="h-40 w-40 rounded-full object-cover border-4 border-white shadow-lg"
                                            src="{{ asset('public/storage/' . $doctor->profile_image) }}" alt="Profile photo">
                                    @else
                                        <div class="h-40 w-40 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center border-4 border-white shadow-lg">
                                            <i class="fas fa-user-md text-blue-300 text-5xl"></i>
                                        </div>
                                    @endif
                                    <label for="profile_image"
                                        class="absolute bottom-2 right-2 bg-blue-500 text-white rounded-full p-2 cursor-pointer hover:bg-blue-600 shadow-md transition-colors">
                                        <i class="fas fa-camera text-sm"></i>
                                    </label>
                                    <input type="file" id="profile_image" name="profile_image" class="hidden" accept="image/*">
                                </div>
                                <div class="mt-4 text-center">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $doctor->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $doctor->specialization }}</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $doctor->doctorDetail?->license_number ?? 'No license' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Password Change Card -->
                        <div class="content-card rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-key mr-2 text-purple-600"></i>
                                Change Password
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                    <input type="password" name="password"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <p class="text-xs text-gray-500">Leave blank if you don't want to change password</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Columns - Forms -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Personal Information -->
                        <div class="content-card rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user mr-2 text-green-600"></i>
                                Personal Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                    <input type="text" name="name" value="{{ old('name', $doctor->name) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="email" value="{{ old('email', $doctor->email) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                                    <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                    <input type="date" name="date_of_birth"
                                        value="{{ old('date_of_birth', $doctor->date_of_birth ? \Carbon\Carbon::parse($doctor->date_of_birth)->format('Y-m-d') : '') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('date_of_birth')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                                    <select name="gender" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $doctor->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $doctor->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hospital *</label>
                                    <select name="hospital_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select Hospital</option>
                                        @foreach($hospitals as $hospital)
                                            <option value="{{ $hospital->id }}" {{ old('hospital_id', $doctor->hospital_id) == $hospital->id ? 'selected' : '' }}>
                                                {{ $hospital->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hospital_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                                    <textarea name="address" rows="2" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('address', $doctor->address) }}</textarea>
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="content-card rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-briefcase-medical mr-2 text-red-600"></i>
                                Professional Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Specialization *</label>
                                    <input type="text" name="specialization"
                                        value="{{ old('specialization', $doctor->specialization) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('specialization')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">License Number *</label>
                                    <input type="text" name="license_number"
                                        value="{{ old('license_number', $doctor->doctorDetail?->license_number) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('license_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                    <input type="text" name="department"
                                        value="{{ old('department', $doctor->doctorDetail?->department) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('department')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                                    <input type="text" name="designation"
                                        value="{{ old('designation', $doctor->doctorDetail?->designation) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('designation')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Fee (৳) *</label>
                                    <input type="number" step="0.01" name="consultation_fee"
                                        value="{{ old('consultation_fee', $doctor->doctorDetail?->consultation_fee) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('consultation_fee')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Fee (৳)</label>
                                    <input type="number" step="0.01" name="follow_up_fee"
                                        value="{{ old('follow_up_fee', $doctor->doctorDetail?->follow_up_fee) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('follow_up_fee')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Fee (৳)</label>
                                    <input type="number" step="0.01" name="emergency_fee"
                                        value="{{ old('emergency_fee', $doctor->doctorDetail?->emergency_fee) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('emergency_fee')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Experience</label>
                                    <input type="text" name="experience"
                                        value="{{ old('experience', $doctor->doctorDetail?->experience) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="e.g., 5 years">
                                    @error('experience')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Qualifications *</label>
                                    <textarea name="qualifications" rows="2" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="e.g., MBBS, MD, FCPS">{{ old('qualifications', $doctor->qualifications) }}</textarea>
                                    @error('qualifications')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                                    <textarea name="bio" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('bio', $doctor->doctorDetail?->bio) }}</textarea>
                                    @error('bio')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6">
                            <a href="{{ route('doctor.dashboard') }}"
                                class="w-full sm:w-auto px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-3 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Profile image preview
                const profileImageInput = document.getElementById('profile_image');
                if (profileImageInput) {
                    profileImageInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                const img = document.querySelector('.rounded-full img');
                                if (img) {
                                    img.src = e.target.result;
                                } else {
                                    // If no image exists, replace the placeholder div with an img
                                    const placeholder = document.querySelector('.rounded-full div');
                                    if (placeholder) {
                                        const newImg = document.createElement('img');
                                        newImg.className = 'h-40 w-40 rounded-full object-cover border-4 border-white shadow-lg';
                                        newImg.src = e.target.result;
                                        placeholder.parentNode.replaceChild(newImg, placeholder);
                                    }
                                }
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                }
            });
        </script>

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

            input, select, textarea {
                transition: all 0.2s ease-in-out;
            }

            input:focus, select:focus, textarea:focus {
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            @media (max-width: 640px) {
                .text-xl {
                    font-size: 1.25rem;
                }

                .text-lg {
                    font-size: 1.125rem;
                }
            }
        </style>
@endsection