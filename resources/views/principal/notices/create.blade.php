@extends('layouts.principal')

@section('title', 'Create Notice')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Create New Notice</h3>
                <p class="text-gray-600 mt-1">Create a new notice for your school</p>
            </div>
            <a href="{{ route('principal.notices.index') }}" 
               class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i>Back to Notices
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="content-card rounded-lg p-4">
        <div class="bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <form action="{{ route('principal.notices.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Notice Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="Enter notice title">
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                <textarea id="content" name="content" rows="6" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                          placeholder="Enter notice content">{{ old('content') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Save as Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publish Now</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select id="priority" name="priority" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium Priority</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Target Roles -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="role_student" name="target_roles[]" value="student" 
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ is_array(old('target_roles')) && in_array('student', old('target_roles')) ? 'checked' : '' }}>
                                <label for="role_student" class="ml-2 text-sm text-gray-700">Students</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="role_teacher" name="target_roles[]" value="teacher"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ is_array(old('target_roles')) && in_array('teacher', old('target_roles')) ? 'checked' : '' }}>
                                <label for="role_teacher" class="ml-2 text-sm text-gray-700">Teachers</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="role_parent" name="target_roles[]" value="parent"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ is_array(old('target_roles')) && in_array('parent', old('target_roles')) ? 'checked' : '' }}>
                                <label for="role_parent" class="ml-2 text-sm text-gray-700">Parents</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="role_staff" name="target_roles[]" value="staff"
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ is_array(old('target_roles')) && in_array('staff', old('target_roles')) ? 'checked' : '' }}>
                                <label for="role_staff" class="ml-2 text-sm text-gray-700">Staff</label>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                        <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <p class="mt-1 text-sm text-gray-500">Notices will automatically expire after this date</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('principal.notices.index') }}" 
                   class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    Cancel
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Create Notice
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
    // Set minimum date for expiry date
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().split('T')[0];
    
    const expiryDateInput = document.getElementById('expiry_date');
    if (!expiryDateInput.value) {
        expiryDateInput.min = minDate;
        // Set default to 7 days from now
        const defaultDate = new Date(today);
        defaultDate.setDate(defaultDate.getDate() + 7);
        expiryDateInput.value = defaultDate.toISOString().split('T')[0];
    }
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const targetRoles = document.querySelectorAll('input[name="target_roles[]"]:checked');
        if (targetRoles.length === 0) {
            e.preventDefault();
            alert('Please select at least one target audience.');
            return false;
        }
        
        const status = document.getElementById('status').value;
        if (status === 'published') {
            if (!confirm('Are you sure you want to publish this notice immediately?')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endsection