@extends('layouts.app')

@section('title', 'Website Settings')

@section('content')
<div class="space-y-6">
    <!-- Settings Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Website Settings</h3>
            <button type="submit" form="website-settings-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors rounded-lg">
                <i class="fas fa-save mr-2"></i>Save All Settings
            </button>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="website-settings-form" action="{{ route('admin.website-settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Hero Section Settings -->
                <div class="space-y-6">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Hero Section</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- YouTube Playlist ID -->
                        <div>
                            <label for="youtube_playlist_id" class="block text-sm font-medium text-gray-700 mb-2">YouTube Playlist ID</label>
                            <input type="text" name="youtube_playlist_id" id="youtube_playlist_id" 
                                   value="{{ old('youtube_playlist_id', website_setting('hero', 'youtube_playlist_id')) }}"
                                   placeholder="PLXOM8m4bh3zWQesZ4vF1jVLLYw4pwrVv2"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <p class="mt-1 text-xs text-gray-500">
                                Enter the YouTube playlist ID (found in the playlist URL)
                            </p>
                        </div>
                        
                        <div>
                            <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                            <input type="text" name="hero_title" id="hero_title" 
                                   value="{{ old('hero_title', website_setting('hero', 'hero_title')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>
                        <!-- YouTube Playlist URL -->
                        <!--<div>-->
                        <!--    <label for="youtube_playlist_url" class="block text-sm font-medium text-gray-700 mb-2">YouTube Playlist URL</label>-->
                        <!--    <input type="url" name="youtube_playlist_url" id="youtube_playlist_url" -->
                        <!--           value="{{ old('youtube_playlist_url', website_setting('hero', 'youtube_playlist_url')) }}"-->
                        <!--           placeholder="https://www.youtube.com/playlist?list=..."-->
                        <!--           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">-->
                        <!--</div>-->
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- CTA Button Link -->
                        <div>
                            <label for="cta_button_link" class="block text-sm font-medium text-gray-700 mb-2">CTA Button Link</label>
                            <input type="text" name="cta_button_link" id="cta_button_link" 
                                   value="{{ old('cta_button_link', website_setting('hero', 'cta_button_link')) }}"
                                   placeholder="/register or https://..."
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>

                        <!-- CTA Button Text -->
                        <div>
                            <label for="cta_button_text" class="block text-sm font-medium text-gray-700 mb-2">CTA Button Text</label>
                            <input type="text" name="cta_button_text" id="cta_button_text" 
                                   value="{{ old('cta_button_text', website_setting('hero', 'cta_button_text')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>
                    </div>

                    <!-- Hero Subtitle -->
                    <div>
                        <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                        <textarea name="hero_subtitle" id="hero_subtitle" rows="8"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 resize-vertical">{{ old('hero_subtitle', website_setting('hero', 'hero_subtitle')) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            HTML tags are supported. Use &lt;br&gt; for line breaks, &lt;strong&gt; for bold text, etc.
                        </p>
                    </div>

                    <!-- YouTube Auto Play -->
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="youtube_auto_play" id="youtube_auto_play" value="1"
                               {{ old('youtube_auto_play', website_setting('hero', 'youtube_auto_play')) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="youtube_auto_play" class="text-sm font-medium text-gray-700">Auto-play YouTube videos</label>
                    </div>
                </div>

                <!-- Ministers Section -->
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xl font-semibold text-gray-900">Ministers Section</h4>
                        <button type="button" id="add-minister-btn" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm font-medium transition-colors rounded-lg">
                            <i class="fas fa-plus mr-2"></i>Add Minister
                        </button>
                    </div>

                    <!-- Ministers Section Title -->
                    <div>
                        <label for="section_title" class="block text-sm font-medium text-gray-700 mb-2">Section Title</label>
                        <input type="text" name="section_title" id="section_title" 
                               value="{{ old('section_title', website_setting('ministers', 'section_title')) }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                    </div>

                    <!-- Display Count -->
                    <div class="max-w-xs">
                        <label for="display_count" class="block text-sm font-medium text-gray-700 mb-2">Number of Ministers to Display</label>
                        <input type="number" name="display_count" id="display_count" min="1" max="20"
                               value="{{ old('display_count', website_setting('ministers', 'display_count', 6)) }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                    </div>

                    <!-- Ministers List -->
                    <div id="ministers-container" class="space-y-4">
                        @php
                            $ministers = old('ministers_list', website_setting('ministers', 'ministers_list', []));
                        @endphp
                        
                        @if(count($ministers) > 0)
                            @foreach($ministers as $index => $minister)
                                <div class="minister-item bg-white/50 rounded-lg p-4 border border-gray-200/60" data-index="{{ $index }}">
                                    <div class="flex justify-between items-start mb-4">
                                        <h5 class="text-lg font-medium text-gray-900">Minister #{{ $index + 1 }}</h5>
                                        <button type="button" class="remove-minister-btn text-red-600 hover:text-red-800 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                        <!-- Minister Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                            <input type="text" name="ministers_list[{{ $index }}][name]" 
                                                   value="{{ $minister['name'] ?? '' }}"
                                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                            @error("ministers_list.{$index}.name")
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Ministry -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Ministry</label>
                                            <input type="text" name="ministers_list[{{ $index }}][ministry]" 
                                                   value="{{ $minister['ministry'] ?? '' }}"
                                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                            @error("ministers_list.{$index}.ministry")
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Display Order -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                                            <input type="number" name="ministers_list[{{ $index }}][display_order]" 
                                                   value="{{ $minister['display_order'] ?? $index + 1 }}"
                                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                        </div>
                                    </div>

                                    <!-- Image Upload -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minister Image</label>
                                        <div class="flex items-center space-x-4">
                                            <input type="file" name="minister_images[{{ $index }}]" 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                                   class="flex-1 px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                            
                                            @if(!empty($minister['image_link']) && file_exists(public_path('storage/' . $minister['image_link'])))
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('public/storage/' . $minister['image_link']) }}" 
                                                         alt="Minister Image" 
                                                         class="h-16 w-16 object-cover rounded-lg border border-gray-300">
                                                    <p class="text-xs text-gray-500 mt-1 text-center">Current</p>
                                                </div>
                                            @endif
                                        </div>
                                        @error("minister_images.{$index}")
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                                <i class="fas fa-users text-4xl mb-3"></i>
                                <p>No ministers added yet. Click "Add Minister" to get started.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- General Settings Section -->
                <!--<div class="space-y-6">-->
                <!--    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">General Settings</h4>-->
                    
                <!--    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">-->
                        <!-- Site Title -->
                <!--        <div>-->
                <!--            <label for="site_title" class="block text-sm font-medium text-gray-700 mb-2">Site Title</label>-->
                <!--            <input type="text" name="site_title" id="site_title" -->
                <!--                   value="{{ old('site_title', website_setting('general', 'site_title')) }}"-->
                <!--                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">-->
                <!--            @error('site_title')-->
                <!--                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>-->
                <!--            @enderror-->
                <!--        </div>-->
                        
                        <!-- Contact Email -->
                <!--        <div>-->
                <!--            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>-->
                <!--            <input type="email" name="contact_email" id="contact_email" -->
                <!--                   value="{{ old('contact_email', website_setting('general', 'contact_email')) }}"-->
                <!--                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">-->
                <!--            @error('contact_email')-->
                <!--                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>-->
                <!--            @enderror-->
                <!--        </div>-->
                <!--    </div>-->

                    <!-- Site Description -->
                <!--    <div>-->
                <!--        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>-->
                <!--        <textarea name="site_description" id="site_description" rows="3"-->
                <!--                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">{{ old('site_description', website_setting('general', 'site_description')) }}</textarea>-->
                <!--        @error('site_description')-->
                <!--            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>-->
                <!--        @enderror-->
                <!--    </div>-->

                    <!-- Phone Number -->
                <!--    <div class="max-w-md">-->
                <!--        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>-->
                <!--        <input type="tel" name="phone_number" id="phone_number" -->
                <!--               value="{{ old('phone_number', website_setting('general', 'phone_number')) }}"-->
                <!--               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">-->
                <!--        @error('phone_number')-->
                <!--            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>-->
                <!--        @enderror-->
                <!--    </div>-->

                    <!-- Media Files -->
                <!--    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-4">-->
                        <!-- Site Logo -->
                <!--        <div class="space-y-4">-->
                <!--            <div>-->
                <!--                <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>-->
                <!--                <input type="file" name="site_logo" id="site_logo" -->
                <!--                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"-->
                <!--                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">-->
                <!--                <p class="mt-2 text-xs text-gray-500">-->
                <!--                    Recommended: PNG, JPG, WEBP (Max: 5MB)-->
                <!--                </p>-->
                <!--                @error('site_logo')-->
                <!--                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>-->
                <!--                @enderror-->
                <!--            </div>-->
                            
                <!--            @if(website_setting('general', 'site_logo') && file_exists(public_path('storage/' . website_setting('general', 'site_logo'))))-->
                <!--                <div class="bg-white/50 rounded-lg p-4 border border-gray-200/60">-->
                <!--                    <p class="text-sm font-medium text-gray-700 mb-2">Current Logo:</p>-->
                <!--                    <div class="flex items-center space-x-4">-->
                <!--                        <img src="{{ asset('public/storage/' . website_setting('general', 'site_logo')) }}" -->
                <!--                             alt="Site Logo" -->
                <!--                             class="h-16 object-contain bg-white p-3 rounded-lg shadow-sm">-->
                <!--                        <div class="text-sm text-gray-600">-->
                <!--                            <p class="font-medium">Uploaded Logo</p>-->
                <!--                            <p class="text-xs text-gray-500">Click to replace</p>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            @endif-->
                <!--        </div>-->

                        <!-- Site Favicon -->
                <!--        <div class="space-y-4">-->
                <!--            <div>-->
                <!--                <label for="site_favicon" class="block text-sm font-medium text-gray-700 mb-2">Site Favicon</label>-->
                <!--                <input type="file" name="site_favicon" id="site_favicon" -->
                <!--                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/x-icon"-->
                <!--                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">-->
                <!--                <p class="mt-2 text-xs text-gray-500">-->
                <!--                    Recommended: ICO, PNG, WEBP (Max: 2MB)-->
                <!--                </p>-->
                <!--                @error('site_favicon')-->
                <!--                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>-->
                <!--                @enderror-->
                <!--            </div>-->
                            
                <!--            @if(website_setting('general', 'site_favicon') && file_exists(public_path('storage/' . website_setting('general', 'site_favicon'))))-->
                <!--                <div class="bg-white/50 rounded-lg p-4 border border-gray-200/60">-->
                <!--                    <p class="text-sm font-medium text-gray-700 mb-2">Current Favicon:</p>-->
                <!--                    <div class="flex items-center space-x-4">-->
                <!--                        <img src="{{ asset('public/storage/' . website_setting('general', 'site_favicon')) }}" -->
                <!--                             alt="Site Favicon" -->
                <!--                             class="h-16 w-16 object-contain bg-white p-3 rounded-lg shadow-sm">-->
                <!--                        <div class="text-sm text-gray-600">-->
                <!--                            <p class="font-medium">Uploaded Favicon</p>-->
                <!--                            <p class="text-xs text-gray-500">Click to replace</p>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            @endif-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>

            <!-- Save Button -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Save All Settings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Minister Item Template (Hidden) -->
<template id="minister-template">
    <div class="minister-item bg-white/50 rounded-lg p-4 border border-gray-200/60" data-index="__INDEX__">
        <div class="flex justify-between items-start mb-4">
            <h5 class="text-lg font-medium text-gray-900">Minister #__DISPLAY_INDEX__</h5>
            <button type="button" class="remove-minister-btn text-red-600 hover:text-red-800 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Minister Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="ministers_list[__INDEX__][name]" 
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
            </div>

            <!-- Ministry -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ministry</label>
                <input type="text" name="ministers_list[__INDEX__][ministry]" 
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
            </div>

            <!-- Display Order -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="ministers_list[__INDEX__][display_order]" value="__DISPLAY_INDEX__"
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
            </div>
        </div>

        <!-- Image Upload -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Minister Image</label>
            <input type="file" name="minister_images[__INDEX__]" 
                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        </div>
    </div>
</template>

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

    .minister-item {
        transition: all 0.3s ease;
    }

    .minister-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let ministerIndex = {{ count($ministers) }};
    const ministersContainer = document.getElementById('ministers-container');
    const addMinisterBtn = document.getElementById('add-minister-btn');
    const ministerTemplate = document.getElementById('minister-template');

    // Add new minister
    addMinisterBtn.addEventListener('click', function() {
        const templateContent = ministerTemplate.innerHTML;
        const newMinisterHtml = templateContent
            .replace(/__INDEX__/g, ministerIndex)
            .replace(/__DISPLAY_INDEX__/g, ministerIndex + 1);
        
        const newElement = document.createElement('div');
        newElement.innerHTML = newMinisterHtml;
        
        // Remove the empty state if it exists
        const emptyState = ministersContainer.querySelector('.text-center');
        if (emptyState) {
            emptyState.remove();
        }
        
        ministersContainer.appendChild(newElement.firstElementChild);
        
        ministerIndex++;
        
        // Scroll to the new minister
        newElement.firstElementChild.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Show success message
        showNotification('New minister added successfully!', 'success');
    });

    // Remove minister
    ministersContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-minister-btn')) {
            const ministerItem = e.target.closest('.minister-item');
            const index = parseInt(ministerItem.dataset.index);
            
            if (!confirm('Are you sure you want to remove this minister? This action cannot be undone.')) {
                return;
            }
            
            // Send AJAX request to remove minister from server
            fetch('{{ route("admin.website-settings.remove-minister") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ index: index })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    ministerItem.remove();
                    updateMinisterDisplayNumbers();
                    showNotification('Minister removed successfully!', 'success');
                    
                    // Show empty state if no ministers left
                    if (ministersContainer.children.length === 0) {
                        ministersContainer.innerHTML = `
                            <div class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                                <i class="fas fa-users text-4xl mb-3"></i>
                                <p>No ministers added yet. Click "Add Minister" to get started.</p>
                            </div>
                        `;
                        ministerIndex = 0;
                    }
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error removing minister. Please try again.', 'error');
            });
        }
    });

    // Update display numbers when ministers are removed
    function updateMinisterDisplayNumbers() {
        const ministerItems = ministersContainer.querySelectorAll('.minister-item');
        ministerItems.forEach((item, index) => {
            const title = item.querySelector('h5');
            const displayOrderInput = item.querySelector('input[name$="[display_order]"]');
            
            // Update data-index attribute
            item.setAttribute('data-index', index);
            
            if (title) title.textContent = `Minister #${index + 1}`;
            if (displayOrderInput) displayOrderInput.value = index + 1;
            
            // Update all input names with new index
            const inputs = item.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name && name.includes('[')) {
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                }
            });
        });
        
        // Update ministerIndex for new additions
        ministerIndex = ministerItems.length;
    }

    // File input preview enhancement
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file chosen';
            const parent = this.parentElement;
            const existingMessage = parent.querySelector('.file-selected-message');
            
            if (existingMessage) {
                existingMessage.textContent = `Selected: ${fileName}`;
            } else {
                const message = document.createElement('p');
                message.className = 'mt-1 text-xs text-green-600 file-selected-message';
                message.textContent = `Selected: ${fileName}`;
                parent.appendChild(message);
            }
        });
    });

    // Smooth interactions
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
        });
    });

    // Form submission handling
    const form = document.getElementById('website-settings-form');
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });

    // Notification function
    function showNotification(message, type) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create new notification
        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 p-4 rounded-lg text-white z-50 transform transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('translate-x-0');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }

    // Initialize notification styles
    const style = document.createElement('style');
    style.textContent = `
        .custom-notification {
            transform: translateX(100%);
        }
        .custom-notification.translate-x-0 {
            transform: translateX(0);
        }
        .custom-notification.translate-x-full {
            transform: translateX(100%);
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection