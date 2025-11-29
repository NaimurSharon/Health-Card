@extends('layouts.app')

@section('title', isset($notice) ? 'Edit Notice' : 'Add New Notice')

@section('content')
<div class="space-y-6">
    <!-- Notice Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ isset($notice) ? 'Edit Notice' : 'Add New Notice' }}</h3>
            <button type="submit" form="notice-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ isset($notice) ? 'Update Notice' : 'Create Notice' }}
            </button>
        </div>
    </div>

    <!-- Notice Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="notice-form" action="{{ isset($notice) ? route('admin.notices.update', ($notice ?? null)) : route('admin.notices.store') }}" method="POST">
            @csrf
            @if(isset($notice))
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
                                   value="{{ old('title', ($notice->title ?? '') ) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter notice title">
                            @error('title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                            <select name="priority" id="priority" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Priority</option>
                                <option value="high" {{ old('priority', ($notice->priority ?? '')) == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ old('priority', ($notice->priority ?? '')) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ old('priority', ($notice->priority ?? '')) == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                            <input type="date" name="expiry_date" id="expiry_date" 
                                   value="{{ old('expiry_date', ($notice->expiry_date ?? now()->addDays(7))->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('expiry_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Status</option>
                                <option value="published" {{ old('status', ($notice->status ?? '')) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ old('status', ($notice->status ?? '')) == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Target Roles -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php
                                $roles = ['students', 'teachers', 'parents', 'medical_staff'];
                                $selectedRoles = old('target_roles', ($notice->target_roles ?? []));
                            @endphp
                            @foreach($roles as $role)
                                <label class="flex items-center space-x-2 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                                    <input type="checkbox" name="target_roles[]" value="{{ $role }}" 
                                           {{ in_array($role, $selectedRoles) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700 capitalize">{{ $role }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('target_roles')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
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
                                placeholder="Write the notice content here...">{{ old('content', ($notice->content ?? '') ) }}</textarea>
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
                <a href="{{ route('admin.notices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ isset($notice) ? 'Update Notice' : 'Create Notice' }}
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

        // Set minimum expiry date to today
        const expiryDateInput = document.getElementById('expiry_date');
        if (expiryDateInput) {
            const today = new Date().toISOString().split('T')[0];
            expiryDateInput.min = today;
        }
    });
</script>
@endsection