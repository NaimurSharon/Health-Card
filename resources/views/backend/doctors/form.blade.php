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
        <form id="doctor-form" action="{{ $doctor->exists ? route('admin.doctors.update', $doctor) : route('admin.doctors.store') }}" method="POST">
            @csrf
            @if($doctor->exists)
                @method('PUT')
            @endif

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
                                   placeholder="Enter phone number">
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
                            <input type="text" name="specialization" id="specialization" 
                                   value="{{ old('specialization', $doctor->specialization) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., Pediatrics, Cardiology, etc.">
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
                            <label for="experience" class="block text-sm font-medium text-gray-700 mb-2">Experience (Years)</label>
                            <input type="text" name="experience" id="experience" 
                                   value="{{ old('experience', $doctor->experience) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., 10 years">
                            @error('experience')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Number -->
                        <div>
                            <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">License Number</label>
                            <input type="text" name="license_number" id="license_number" 
                                   value="{{ old('license_number', $doctor->license_number) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Medical license number">
                            @error('license_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hospital -->
                        <div>
                            <label for="hospital_id" class="block text-sm font-medium text-gray-700 mb-2">Hospital</label>
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

        // Auto-resize textarea
        const addressTextarea = document.getElementById('address');
        if (addressTextarea) {
            addressTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Trigger initial resize
            addressTextarea.dispatchEvent(new Event('input'));
        }
    });
</script>
@endsection