@extends('layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="space-y-6">
    <!-- Settings Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Site Settings</h3>
            <button type="submit" form="settings-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Site Identity Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Site Identity</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Site Title -->
                        <div>
                            <label for="site_title" class="block text-sm font-medium text-gray-700 mb-2">Site Title</label>
                            <input type="text" name="site_title" id="site_title" 
                                   value="{{ old('site_title', setting('site_title')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>
                        
                        <!-- Currency -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                            <input type="text" name="currency" id="currency" 
                                   value="{{ old('currency', setting('currency')) }}"
                                   placeholder="e.g. ৳, $, €"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <p class="mt-1 text-xs text-gray-500">
                                Enter the currency symbol (e.g. ৳ for Taka, $ for Dollar, € for Euro)
                            </p>
                        </div>
                    </div>

                    <!-- Site Description -->
                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                        <textarea name="site_description" id="site_description" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">{{ old('site_description', setting('site_description')) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tagline -->
                        <div>
                            <label for="site_tagline" class="block text-sm font-medium text-gray-700 mb-2">Tagline</label>
                            <input type="text" name="site_tagline" id="site_tagline" 
                                   value="{{ old('site_tagline', setting('site_tagline')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>
                        
                        <!-- Keywords -->
                        <div>
                            <label for="site_keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                            <input type="text" name="site_keywords" id="site_keywords" 
                                   value="{{ old('site_keywords', setting('site_keywords')) }}"
                                   placeholder="comma, separated, keywords"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Contact Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contact Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                            <input type="email" name="contact_email" id="contact_email" 
                                   value="{{ old('contact_email', setting('contact_email')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>
                        
                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone_number" 
                                   value="{{ old('phone_number', setting('phone_number')) }}"
                                   pattern="[0-9+ -]*" maxlength="20" placeholder="+8801XXXXXXXXX"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>
                    </div>

                    <!-- Website URL -->
                    <div>
                        <label for="app_url" class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
                        <input type="url" name="app_url" id="app_url" 
                               value="{{ old('app_url', setting('app_url')) }}"
                               placeholder="https://yourwebsite.com"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                    </div>
                </div>

                <!-- Media Files Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Media Files</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Site Logo -->
                        <div class="space-y-4">
                            <div>
                                <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                                <input type="file" name="site_logo" id="site_logo" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml"
                                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-2 text-xs text-gray-500">
                                    Recommended: PNG, JPG, WEBP (Max: 10MB)
                                </p>
                            </div>
                            
                            @if(setting('site_logo'))
                                <div class="bg-white/50 rounded-lg p-4 border border-gray-200/60">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Current Logo:</p>
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ asset('public/storage/' . setting('site_logo')) }}" 
                                             alt="Site Logo" 
                                             class="h-16 object-contain bg-white p-3 rounded-lg shadow-sm">
                                        <div class="text-sm text-gray-600">
                                            <p class="font-medium">Uploaded Logo</p>
                                            <p class="text-xs text-gray-500">Click to replace</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Site Favicon -->
                        <div class="space-y-4">
                            <div>
                                <label for="site_favicon" class="block text-sm font-medium text-gray-700 mb-2">Site Favicon</label>
                                <input type="file" name="site_favicon" id="site_favicon" 
                                       accept="image/jpeg,image/png,image/jpg,image/svg+xml,image/gif,image/webp,image/x-icon"
                                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-2 text-xs text-gray-500">
                                    Recommended: ICO, PNG (Max: 5MB)
                                </p>
                            </div>
                            
                            @if(setting('site_favicon'))
                                <div class="bg-white/50 rounded-lg p-4 border border-gray-200/60">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Current Favicon:</p>
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ asset('public/storage/' . setting('site_favicon')) }}" 
                                             alt="Site Favicon" 
                                             class="h-16 w-16 object-contain bg-white p-3 rounded-lg shadow-sm">
                                        <div class="text-sm text-gray-600">
                                            <p class="font-medium">Uploaded Favicon</p>
                                            <p class="text-xs text-gray-500">Click to replace</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Settings Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Additional Settings</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Timezone -->
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                            <select name="timezone" id="timezone" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="Asia/Dhaka" {{ old('timezone', setting('timezone')) == 'Asia/Dhaka' ? 'selected' : '' }}>Asia/Dhaka (Bangladesh)</option>
                                <option value="UTC" {{ old('timezone', setting('timezone')) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <!-- Add more timezone options as needed -->
                            </select>
                        </div>
                        
                        <!-- Date Format -->
                        <div>
                            <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                            <select name="date_format" id="date_format" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="d/m/Y" {{ old('date_format', setting('date_format')) == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="m/d/Y" {{ old('date_format', setting('date_format')) == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                <option value="Y-m-d" {{ old('date_format', setting('date_format')) == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Save Settings
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


    .file\:bg-blue-50 {
        background-color: rgba(59, 130, 246, 0.1);
    }

    .file\:text-blue-700 {
        color: #1D4ED8;
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

        // File input preview enhancement
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'No file chosen';
                const label = this.nextElementSibling;
                if (label && label.classList.contains('text-xs')) {
                    label.textContent = `Selected: ${fileName}`;
                }
            });
        });
    });
</script>
@endsection