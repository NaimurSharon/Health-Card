@extends('layouts.backend')

@section('title', isset($workReport) ? 'Edit Work Report' : 'Add Work Report')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ isset($workReport) ? 'Edit Work Report' : 'Add New Work Report' }}
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ isset($workReport) ? 'Update work report information' : 'Record daily work activities' }}
                </p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('admin.work-reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Work Reports
                </a>
            </div>
        </div>
    </div>

    <form action="{{ isset($workReport) ? route('admin.work-reports.update', $workReport) : route('admin.work-reports.store') }}" method="POST" class="p-4 sm:p-6">
        @csrf
        @if(isset($workReport))
            @method('PUT')
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Basic Information
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Staff Member *</label>
                            
                            @if(auth()->user()->role === 'staff')
                                <!-- Locked field for staff users -->
                                <div class="relative">
                                    <select name="staff_id" id="staff_id" 
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed" 
                                            disabled required>
                                        <option value="{{ auth()->id() }}" selected>
                                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }} (You)
                                        </option>
                                    </select>
                                    <input type="hidden" name="staff_id" value="{{ auth()->id() }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Editable field for admin users -->
                                <select name="staff_id" id="staff_id" 
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('staff_id') border-red-500 @enderror" 
                                        required>
                                    <option value="">Select Staff Member</option>
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}" 
                                            {{ old('staff_id', $workReport->staff_id ?? '') == $member->id ? 'selected' : '' }}>
                                            {{ $member->first_name }} {{ $member->last_name }}
                                            @if($member->id == auth()->id())
                                                (You)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                        <div>
                            <label for="work_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Work Date *</label>
                            <input type="date" name="work_date" id="work_date" 
                                   value="{{ old('work_date', isset($workReport) ? $workReport->work_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('work_date') border-red-500 @enderror" 
                                   required>
                            @error('work_date')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="task_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Task Status *</label>
                            <select name="task_status" id="task_status" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('task_status') border-red-500 @enderror" 
                                    required>
                                @foreach($taskStatuses as $value => $label)
                                    <option value="{{ $value }}" 
                                        {{ old('task_status', $workReport->task_status ?? 'in_progress') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('task_status')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Details -->
            <div class="space-y-4">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-tasks mr-2 text-green-500"></i>
                        Additional Notes
                    </h4>
                    
                    <div>
                        <textarea name="notes" id="notes" rows="5" 
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror" 
                                  placeholder="Any additional notes, challenges faced, or next steps...">{{ old('notes', $workReport->notes ?? '') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Optional: Add any challenges, blockers, or important observations.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Description & Notes -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-1 gap-6">
            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-align-left mr-2 text-purple-500"></i>
                    Work Description *
                </h4>
                <div>
                    <textarea name="work_description" id="work_description" rows="5" 
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('work_description') border-red-500 @enderror" 
                              placeholder="Describe the work done in detail..." required>{{ old('work_description', $workReport->work_description ?? '') }}</textarea>
                    @error('work_description')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Be specific about the tasks completed, features worked on, or issues resolved.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 pt-6 border-t dark:border-gray-700 mt-6">
            <a href="{{ route('admin.work-reports.index') }}" 
               class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-{{ isset($workReport) ? 'save' : 'plus' }} mr-2"></i>
                {{ isset($workReport) ? 'Update Work Report' : 'Create Work Report' }}
            </button>
        </div>
    </form>
</div>
@endsection