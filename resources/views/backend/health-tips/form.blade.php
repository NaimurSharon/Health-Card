@extends('layouts.app')

@section('title', isset($healthTip) ? 'Edit Health Tip' : 'Add New Health Tip')

@section('content')
<div class="space-y-6">
    <!-- Health Tip Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ ($healthTip ?? null)?->exists ? 'Edit Health Tip' : 'Add New Health Tip' }}</h3>
            <button type="submit" form="health-tip-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ ($healthTip ?? null)?->exists ? 'Update Health Tip' : 'Create Health Tip' }}
            </button>
        </div>
    </div>

    <!-- Health Tip Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="health-tip-form" 
              action="{{ ($healthTip ?? null)?->exists ? route('admin.health-tips.update', ($healthTip ?? null)) : route('admin.health-tips.store') }}" 
              method="POST">
            @csrf
            @if(($healthTip ?? null)?->exists)
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title" id="title" 
                                   value="{{ old('title', ($healthTip->title ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter health tip title">
                            @error('title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" id="category" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Category</option>
                                <option value="general" {{ old('category', ($healthTip->category ?? '')) == 'general' ? 'selected' : '' }}>General</option>
                                <option value="nutrition" {{ old('category', ($healthTip->category ?? '')) == 'nutrition' ? 'selected' : '' }}>Nutrition</option>
                                <option value="exercise" {{ old('category', ($healthTip->category ?? '')) == 'exercise' ? 'selected' : '' }}>Exercise</option>
                                <option value="mental_health" {{ old('category', ($healthTip->category ?? '')) == 'mental_health' ? 'selected' : '' }}>Mental Health</option>
                                <option value="hygiene" {{ old('category', ($healthTip->category ?? '')) == 'hygiene' ? 'selected' : '' }}>Hygiene</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Audience -->
                        <div>
                            <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                            <select name="target_audience" id="target_audience" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Audience</option>
                                <option value="all" {{ old('target_audience', ($healthTip->target_audience ?? '')) == 'all' ? 'selected' : '' }}>All</option>
                                <option value="students" {{ old('target_audience', ($healthTip->target_audience ?? '')) == 'students' ? 'selected' : '' }}>Students</option>
                                <option value="teachers" {{ old('target_audience', ($healthTip->target_audience ?? '')) == 'teachers' ? 'selected' : '' }}>Teachers</option>
                                <option value="parents" {{ old('target_audience', ($healthTip->target_audience ?? '')) == 'parents' ? 'selected' : '' }}>Parents</option>
                            </select>
                            @error('target_audience')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Status</option>
                                <option value="published" {{ old('status', ($healthTip->status ?? '')) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ old('status', ($healthTip->status ?? '')) == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Content</h4>
                    
                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                        <textarea name="content" id="content" rows="12"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Write the health tip content here...">{{ old('content', ($healthTip->content ?? '')) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            You can use basic HTML formatting for better presentation.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.health-tips.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ ($healthTip ?? null)?->exists ? 'Update Health Tip' : 'Create Health Tip' }}
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
        const contentTextarea = document.getElementById('content');
        if (contentTextarea) {
            contentTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Trigger initial resize
            contentTextarea.dispatchEvent(new Event('input'));
        }
    });
</script>
@endsection