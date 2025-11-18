@extends('layouts.backend')

@section('title', 'Work Report Details')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Work Report Details
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Complete information about the work report
                </p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-3">
                <a href="{{ route('admin.work-reports.edit', $workReport) }}" 
                   class="inline-flex items-center px-4 py-2 border border-green-300 dark:border-green-600 rounded-md shadow-sm text-sm font-medium text-green-700 dark:text-green-300 bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-green-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Report
                </a>
                <form action="{{ route('admin.work-reports.destroy', $workReport) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this work report?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Delete
                    </button>
                </form>
                <a href="{{ route('admin.work-reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Work Report Details -->
    <div class="px-4 py-6 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Basic Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Work Description -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>
                        Work Description
                    </h4>
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $workReport->work_description }}</p>
                    </div>
                </div>

                <!-- Additional Notes -->
                @if($workReport->notes)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                        Additional Notes
                    </h4>
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $workReport->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar - Meta Information -->
            <div class="space-y-6">
                <!-- Report Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-green-500"></i>
                        Report Summary
                    </h4>
                    <div class="space-y-4">
                        <!-- Staff -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Staff Member:</span>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-2">
                                    {{ substr($workReport->staff->first_name, 0, 1) }}{{ substr($workReport->staff->last_name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $workReport->staff->first_name }} {{ $workReport->staff->last_name }}
                                </span>
                            </div>
                        </div>

                        <!-- Work Date -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Work Date:</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">
                                {{ $workReport->work_date->format('F d, Y') }}
                            </span>
                        </div>

                        <!-- Hours Worked -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Hours Worked:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $workReport->hours_worked }} hours
                            </span>
                        </div>

                        <!-- Work Type -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Work Type:</span>
                            @php
                                $workTypeColors = [
                                    'development' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                    'design' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
                                    'testing' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'meeting' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'documentation' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                                    'support' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    'other' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                ];
                                $workTypeIcons = [
                                    'development' => 'fas fa-code',
                                    'design' => 'fas fa-palette',
                                    'testing' => 'fas fa-vial',
                                    'meeting' => 'fas fa-users',
                                    'documentation' => 'fas fa-file-alt',
                                    'support' => 'fas fa-headset',
                                    'other' => 'fas fa-ellipsis-h',
                                ];
                            @endphp
                            </span>
                        </div>

                        <!-- Task Status -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Task Status:</span>
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'blocked' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                ];
                                $statusIcons = [
                                    'completed' => 'fas fa-check-circle',
                                    'in_progress' => 'fas fa-spinner',
                                    'pending' => 'fas fa-clock',
                                    'blocked' => 'fas fa-exclamation-triangle',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$workReport->task_status] }}">
                                <i class="{{ $statusIcons[$workReport->task_status] }} mr-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $workReport->task_status)) }}
                            </span>
                        </div>

                        <!-- Project -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Project:</span>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">
                                {{ $workReport->project_name ?? 'No project specified' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Report Metadata -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-database mr-2 text-gray-500"></i>
                        Report Metadata
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Report ID:</span>
                            <span class="text-sm text-gray-900 dark:text-white font-mono">#{{ $workReport->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Created By:</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $workReport->createdBy->first_name ?? 'System' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Created At:</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $workReport->created_at->format('M d, Y h:i A') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated:</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $workReport->updated_at->format('M d, Y h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-bolt mr-2 text-orange-500"></i>
                        Quick Actions
                    </h4>
                    <div class="space-y-2">
                        <a href="{{ route('admin.work-reports.edit', $workReport) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 dark:border-green-600 rounded-md text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit This Report
                        </a>
                        <a href="{{ route('admin.work-reports.create') }}?staff_id={{ $workReport->staff_id }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 dark:border-blue-600 rounded-md text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            New Report for {{ $workReport->staff->first_name }}
                        </a>
                        <a href="{{ route('admin.work-reports.index') }}?staff_id={{ $workReport->staff_id }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-purple-300 dark:border-purple-600 rounded-md text-sm font-medium text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            View All Reports by {{ $workReport->staff->first_name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Footer -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-info-circle mr-1"></i>
                Work report details for {{ $workReport->staff->first_name }} {{ $workReport->staff->last_name }}
            </div>
            <div class="mt-2 sm:mt-0 flex space-x-3">
                @if($previousReport)
                <a href="{{ route('admin.work-reports.show', $previousReport) }}" 
                   class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-chevron-left mr-1"></i>
                    Previous
                </a>
                @endif
                
                @if($nextReport)
                <a href="{{ route('admin.work-reports.show', $nextReport) }}" 
                   class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Next
                    <i class="fas fa-chevron-right ml-1"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.prose {
    max-width: none;
}
.prose p {
    margin-bottom: 0;
}
</style>
@endpush