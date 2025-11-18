@extends('layouts.app')

@section('title', $editor->exists ? 'Edit Content' : 'Add New Content')

@section('content')
<div class="space-y-6">
    <!-- Content Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ $editor->exists ? 'Edit Content' : 'Add New Content' }}</h3>
            <button type="submit" form="editor-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ $editor->exists ? 'Update Content' : 'Create Content' }}
            </button>
        </div>
    </div>

    <!-- Content Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="editor-form" action="{{ $editor->exists ? route('admin.editor.update', $editor) : route('admin.editor.store') }}" method="POST">
            @csrf
            @if($editor->exists)
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
                                   value="{{ old('title', $editor->title ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter content title">
                            @error('title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                            <select name="type" id="type" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Type</option>
                                <option value="page" {{ old('type', $editor->type ?? '') == 'page' ? 'selected' : '' }}>Page</option>
                                <option value="post" {{ old('type', $editor->type ?? '') == 'post' ? 'selected' : '' }}>Post</option>
                                <option value="notice" {{ old('type', $editor->type ?? '') == 'notice' ? 'selected' : '' }}>Notice</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Status</option>
                                <option value="published" {{ old('status', $editor->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ old('status', $editor->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Brief description of the content...">{{ old('excerpt', $editor->excerpt ?? '') }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            A short summary of your content. If left blank, it will be generated from the content.
                        </p>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Content</h4>
                    
                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                        <textarea name="content" id="content" rows="15"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Write your content here...">{{ old('content', $editor->content ?? '') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            You can use basic HTML formatting for better presentation.
                        </p>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">SEO Settings</h4>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" 
                                   value="{{ old('meta_title', $editor->meta_title ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Meta title for SEO">
                            @error('meta_title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    placeholder="Meta description for SEO...">{{ old('meta_description', $editor->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.editor.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ $editor->exists ? 'Update Content' : 'Create Content' }}
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

        // Auto-resize textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Trigger initial resize
            textarea.dispatchEvent(new Event('input'));
        });
    });
</script>
@endsection