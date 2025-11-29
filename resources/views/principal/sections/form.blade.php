@extends('layouts.principal')

@section('title', isset($section) ? 'Edit Section' : 'Add New Section')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">
                        {{ isset($section) ? 'Edit Section' : 'Add New Section' }}
                    </h3>
                    <p class="text-gray-600 mt-1">
                        {{ isset($section) ? 'Update section details' : 'Create a new section for a class' }}
                    </p>
                </div>
                <a href="{{ route('principal.sections.index') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Sections
                </a>
            </div>
        </div>
    </div>

    <!-- Section Form -->
    <div class="content-card rounded-lg p-4 sm:p-6">

        <!-- Show validation errors -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form 
            action="{{ isset($section) ? route('principal.sections.update', $section->id) : route('principal.sections.store') }}" 
            method="POST" 
            class="space-y-6"
        >
            @csrf
            @if(isset($section))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                    <select name="class_id" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               @error('class_id') border-red-500 @enderror"
                    >
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ old('class_id', $section->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('class_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section Name *</label>
                    <input type="text" name="name"
                        value="{{ old('name', $section->name ?? '') }}"
                        placeholder="e.g., A, B, Science, Commerce"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Room Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Number</label>
                    <input type="text" name="room_number"
                        value="{{ old('room_number', $section->room_number ?? '') }}"
                        placeholder="e.g., 101, Lab-1"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               @error('room_number') border-red-500 @enderror">
                    @error('room_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class Teacher -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class Teacher</label>
                    <select name="teacher_id"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               @error('teacher_id') border-red-500 @enderror"
                    >
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id', $section->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity *</label>
                    <input type="number" name="capacity"
                        value="{{ old('capacity', $section->capacity ?? '') }}"
                        min="1" max="100" placeholder="e.g., 40"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               @error('capacity') border-red-500 @enderror">
                    @error('capacity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               @error('status') border-red-500 @enderror"
                    >
                        <option value="active" 
                            {{ old('status', $section->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive"
                            {{ old('status', $section->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                <a href="{{ route('principal.sections.index') }}" 
                   class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center">
                    Cancel
                </a>

                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    {{ isset($section) ? 'Update Section' : 'Create Section' }}
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
@endsection
