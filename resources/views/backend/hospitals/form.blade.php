@extends('layouts.app')

@section('title', isset($hospital) ? 'Edit Hospital' : 'Add New Hospital')

@section('content')
<div class="space-y-6">
    <!-- Hospital Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ ($hospital ?? null)?->exists ? 'Edit Hospital' : 'Add New Hospital' }}</h3>
            <button type="submit" form="hospital-form" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ ($hospital ?? null)?->exists ? 'Update Hospital' : 'Create Hospital' }}
            </button>
        </div>
    </div>

    <!-- Hospital Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="hospital-form" 
              action="{{ ($hospital ?? null)?->exists ? route('admin.hospitals.update', ($hospital ?? null)) : route('admin.hospitals.store') }}" 
              method="POST">
            @csrf
            @if(($hospital ?? null)?->exists)
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Basic Information</h4>
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Hospital Name *</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', ($hospital->name ?? '')) }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               placeholder="Enter hospital name">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Hospital Type *</label>
                            <select name="type" id="type" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Type</option>
                                <option value="government" {{ old('type', ($hospital->type ?? '')) == 'government' ? 'selected' : '' }}>Government</option>
                                <option value="private" {{ old('type', ($hospital->type ?? '')) == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="specialized" {{ old('type', ($hospital->type ?? '')) == 'specialized' ? 'selected' : '' }}>Specialized</option>
                                <option value="clinic" {{ old('type', ($hospital->type ?? '')) == 'clinic' ? 'selected' : '' }}>Clinic</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', ($hospital->status ?? '')) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', ($hospital->status ?? '')) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Contact Information</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="text" name="phone" id="phone" 
                                   value="{{ old('phone', ($hospital->phone ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter phone number">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', ($hospital->email ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter email address">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Emergency Contact -->
                        <div>
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact *</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" 
                                   value="{{ old('emergency_contact', ($hospital->emergency_contact ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter emergency contact number">
                            @error('emergency_contact')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" name="website" id="website" 
                                   value="{{ old('website', ($hospital->website ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="https://example.com">
                            @error('website')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter full hospital address">{{ old('address', ($hospital->address ?? '')) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Services & Facilities Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Services & Facilities</h4>
                    
                    <!-- Services -->
                    <div>
                        <label for="services" class="block text-sm font-medium text-gray-700 mb-2">Services</label>
                        <textarea name="services" id="services" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="List hospital services (comma separated)...">{{ old('services', is_array(($hospital->services ?? '')) ? implode(', ', ($hospital->services ?? [])) : ($hospital->services ?? '')) }}</textarea>
                        @error('services')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Facilities -->
                    <div>
                        <label for="facilities" class="block text-sm font-medium text-gray-700 mb-2">Facilities</label>
                        <textarea name="facilities" id="facilities" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Describe hospital facilities...">{{ old('facilities', ($hospital->facilities ?? '')) }}</textarea>
                        @error('facilities')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.hospitals.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ ($hospital ?? null)?->exists ? 'Update Hospital' : 'Create Hospital' }}
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
    });
</script>
@endsection