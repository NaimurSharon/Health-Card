@extends('layouts.backend')

@section('title', 'Work Reports')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Work Reports
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage and track daily work activities of staff members
                </p>
            </div>
            <div class="mt-3 sm:mt-0 flex space-x-3">
                <a href="{{ route('admin.work-reports.reports') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Reports
                </a>
                <a href="{{ route('admin.work-reports.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Work Report
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                <input type="date" name="from_date" id="from_date" 
                       value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}" 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                <input type="date" name="to_date" id="to_date" 
                       value="{{ request('to_date', now()->endOfMonth()->format('Y-m-d')) }}" 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Staff</label>
                <select name="staff_id" id="staff_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Staff</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}" {{ request('staff_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->first_name }} {{ $member->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="work_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Work Type</label>
                <select name="work_type" id="work_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    @foreach($workTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('work_type') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex space-x-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('admin.work-reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-refresh mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Work Reports List -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Staff</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Work Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
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
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white max-w-xs truncate">
                            {{ Str::limit($report->work_description, 50) }}
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$report->task_status] }}">
                                {{ ucfirst(str_replace('_', ' ', $report->task_status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-4">
                                <a href="{{ route('admin.work-reports.show', $report) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors text-xl"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            
                                <a href="{{ route('admin.work-reports.edit', $report) }}" 
                                   class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors text-xl"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            
                                <form action="{{ route('admin.work-reports.destroy', $report) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this work report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors text-xl"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-clipboard-list text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p class="text-lg font-medium text-gray-900 dark:text-white mb-1">No work reports found</p>
                                <p class="text-gray-500 dark:text-gray-400">Get started by adding the first work report.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($workReports->hasPages())
        <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            {{ $workReports->links() }}
        </div>
    @endif
</div>
@endsection