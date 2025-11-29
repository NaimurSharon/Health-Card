@extends('layouts.principal')

@section('title', $class ? 'Edit Class' : 'Add New Class')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">
                        {{ $class ? 'Edit Class' : 'Add New Class' }}
                    </h3>
                    <p class="text-gray-600 mt-1">
                        {{ $class ? 'Update class details' : 'Create a new class in your school' }}
                    </p>
                </div>
                <a href="{{ route('principal.classes.index') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Classes
                </a>
            </div>
        </div>
    </div>

    <!-- Class Form -->
    <div class="content-card rounded-lg p-4 sm:p-6">

        <form 
            action="{{ $class ? route('principal.classes.update', $class->id) : route('principal.classes.store') }}" 
            method="POST" 
            class="space-y-6">

            @csrf
            @if($class)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Class Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class Name *</label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $class->name ?? '') }}" 
                           required 
                           placeholder="e.g., Class Six, Class Seven"
                           class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Numeric Value -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Numeric Value *</label>
                    <input type="number" 
                           name="numeric_value" 
                           value="{{ old('numeric_value', $class->numeric_value ?? '') }}"
                           min="1" max="12" required
                           placeholder="e.g., 6 for Class Six"
                           class="w-full px-4 py-2 border @error('numeric_value') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('numeric_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Shift -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shift *</label>
                    <select name="shift" required
                        class="w-full px-4 py-2 border @error('shift') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Shift</option>
                        <option value="morning" {{ old('shift', $class->shift ?? '') == 'morning' ? 'selected' : '' }}>Morning</option>
                        <option value="day" {{ old('shift', $class->shift ?? '') == 'day' ? 'selected' : '' }}>Day</option>
                    </select>
                    @error('shift')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity *</label>
                    <input type="number" 
                           name="capacity" 
                           value="{{ old('capacity', $class->capacity ?? '') }}"
                           min="1" max="100" required
                           placeholder="e.g., 40"
                           class="w-full px-4 py-2 border @error('capacity') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('capacity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required
                        class="w-full px-4 py-2 border @error('status') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" {{ old('status', $class->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $class->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                <a href="{{ route('principal.classes.index') }}" 
                   class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                    Cancel
                </a>

                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    {{ $class ? 'Update Class' : 'Create Class' }}
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
