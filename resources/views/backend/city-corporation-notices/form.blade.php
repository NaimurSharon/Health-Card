@extends('layouts.app')

@section('title', $notice ? 'Edit Notice' : 'Create City Corporation Notice')

@section('content')
<div class="space-y-6">
    <div class="content-card">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $notice ? 'Edit Notice' : 'Create City Corporation Notice' }}</h1>

        <form action="{{ $notice ? route('admin.city-corporation-notices.update', $notice->id) : route('admin.city-corporation-notices.store') }}" 
              method="POST">
            @csrf
            @if($notice) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $notice->title ?? '') }}" 
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Content *</label>
                    <textarea name="content" rows="6" 
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('content', $notice->content ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Priority *</label>
                        <select name="priority" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="low" {{ old('priority', $notice->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $notice->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $notice->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <!-- Target Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Target Type *</label>
                        <select name="target_type" id="target_type" 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="all_schools" {{ old('target_type', $notice->target_type ?? '') == 'all_schools' ? 'selected' : '' }}>All Schools</option>
                            <option value="specific_schools" {{ old('target_type', $notice->target_type ?? '') == 'specific_schools' ? 'selected' : '' }}>Specific Schools</option>
                        </select>
                    </div>
                </div>

                <!-- Target Schools (Conditional) -->
                <div id="target_schools_section" style="{{ old('target_type', $notice->target_type ?? 'all_schools') == 'specific_schools' ? '' : 'display: none;' }}">
                    <label class="block text-sm font-medium text-gray-700">Select Schools *</label>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded-lg p-4">
                        @foreach($schools as $school)
                        <label class="flex items-center">
                            <input type="checkbox" name="target_schools[]" value="{{ $school->id }}" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   {{ in_array($school->id, old('target_schools', $notice->target_schools ?? [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">{{ $school->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Target Roles -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Target Roles *</label>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="student" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   {{ in_array('student', old('target_roles', $notice->target_roles ?? [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Students</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="teacher" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   {{ in_array('teacher', old('target_roles', $notice->target_roles ?? [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Teachers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="parent" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   {{ in_array('parent', old('target_roles', $notice->target_roles ?? [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Parents</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Expiry Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Expiry Date *</label>
                        <input 
                            type="date" 
                            name="expiry_date" 
                            value="{{ old('expiry_date', isset($notice) && $notice && $notice->expiry_date ? $notice->expiry_date->format('Y-m-d') : '') }}" 
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            required
                        >

                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="draft" {{ old('status', $notice->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $notice->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.city-corporation-notices.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        {{ $notice ? 'Update' : 'Create' }} Notice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('target_type').addEventListener('change', function() {
    const targetSchoolsSection = document.getElementById('target_schools_section');
    if (this.value === 'specific_schools') {
        targetSchoolsSection.style.display = 'block';
    } else {
        targetSchoolsSection.style.display = 'none';
    }
});
</script>
@endsection