@extends('layouts.backend')

@section('title', 'Work Reports & Analytics')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Work Reports & Analytics
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Comprehensive analysis of staff work activities and productivity
                </p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-3">
                <a href="{{ route('admin.work-reports.export-excel', request()->query()) }}" 
                   class="inline-flex items-center px-4 py-2 border border-green-300 dark:border-green-600 rounded-md shadow-sm text-sm font-medium text-green-700 dark:text-green-300 bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-green-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Excel
                </a>
                <a href="{{ route('admin.work-reports.export-pdf', request()->query()) }}" 
                   class="inline-flex items-center px-4 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export PDF
                </a>
                <a href="{{ route('admin.work-reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                <input type="date" name="from_date" id="from_date" 
                       value="{{ $startDate }}" 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                <input type="date" name="to_date" id="to_date" 
                       value="{{ $endDate }}" 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Staff Member</label>
                <select name="staff_id" id="staff_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Staff</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}" {{ $staffId == $member->id ? 'selected' : '' }}>
                            {{ $member->first_name }} {{ $member->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
            </div>
            <div class="flex items-end">
                <a href="{{ route('admin.work-reports.reports') }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-refresh mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="px-4 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <!-- Total Tasks -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tasks</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTasks }}</p>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed Tasks</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $completedTasks }}</p>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                            {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0 }}% Completion Rate
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
        <!-- Hours by Work Type -->

        <!-- Hours by Staff -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                <i class="fas fa-users mr-2 text-green-500"></i>
                Hours by Staff Member
            </h4>
            <div class="space-y-4">
                @foreach($hoursByStaff as $staffData)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                {{ substr($staffData['staff']->first_name, 0, 1) }}{{ substr($staffData['staff']->last_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $staffData['staff']->first_name }} {{ $staffData['staff']->last_name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $staffData['task_count'] }} tasks</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($staffData['total_hours'], 1) }}h</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $totalHours > 0 ? number_format(($staffData['total_hours'] / $totalHours) * 100, 1) : 0 }}%
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="px-6 pb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-table mr-2 text-gray-500"></i>
                    Detailed Work Report
                    <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                        ({{ $workReports->count() }} records)
                    </span>
                </h4>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Work Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($workReports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $report->work_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $report->staff->first_name }} {{ $report->staff->last_name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $report->project_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white max-w-xs">
                                    <div class="truncate" title="{{ $report->work_description }}">
                                        {{ Str::limit($report->work_description, 60) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $report->hours_worked }}h
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 capitalize">
                                        {{ $report->work_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $statusColors = [
                                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'blocked' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$report->task_status] }} capitalize">
                                        {{ str_replace('_', ' ', $report->task_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-chart-bar text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white mb-1">No work reports found</p>
                                        <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or date range.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Report Period Info -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
            <div>
                <i class="fas fa-calendar-alt mr-1"></i>
                Report Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                @if($staffId)
                    | Staff: {{ $staff->firstWhere('id', $staffId)->first_name ?? 'Unknown' }}
                @endif
            </div>
            <div>
                Generated on: {{ now()->format('M d, Y \\a\\t h:i A') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form when date inputs change for real-time updates
    document.addEventListener('DOMContentLoaded', function() {
        const fromDate = document.getElementById('from_date');
        const toDate = document.getElementById('to_date');
        const staffSelect = document.getElementById('staff_id');
        
        [fromDate, toDate, staffSelect].forEach(element => {
            if (element) {
                element.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    });
</script>
@endpush