@extends('layouts.principal')

@section('title', 'Health Reports')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div
                class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">Health Reports</h3>
                    <p class="text-gray-200 mt-1">Manage student health reports and annual checkups</p>
                </div>
                <a href="{{ route('principal.students.index') }}"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-user-graduate mr-2"></i>View Students
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <form action="{{ route('principal.health.reports.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <select name="class_id"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                <div>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="md:col-span-4 flex justify-end gap-3">
                    <button type="submit"
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('principal.health.reports.index') }}"
                        class="w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Health Reports List -->
        <div class="space-y-4">
            @forelse($healthReports as $report)
                <div class="content-card rounded-lg p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <!-- Report Info -->
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ $report->student->user->name ?? 'N/A' }}
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <i class="fas fa-door-open mr-1"></i>
                                            {{ $report->student->class->name ?? 'N/A' }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-sort-numeric-up mr-1"></i>
                                            Roll: {{ $report->student->roll_number ?? 'N/A' }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar-day mr-1"></i>
                                            {{ $report->checkup_date->format('M j, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Reported
                                    </span>
                                </div>
                            </div>

                            <!-- Report Summary -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-user-md mr-2 w-4"></i>
                                    <span>By: {{ $report->checked_by }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-clock mr-2 w-4"></i>
                                    <span>Updated: {{ $report->updated_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-heartbeat mr-2 w-4"></i>
                                    <span>Data Points: {{ $report->reportData->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex items-center justify-end lg:justify-start space-x-2 lg:flex-col lg:space-x-0 lg:space-y-2">
                            <a href="#"
                                class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <i class="fas fa-eye text-sm"></i>
                                <span class="hidden lg:inline ml-2">View</span>
                            </a>
                            <a href="#"
                                class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                                <span class="hidden lg:inline ml-2">Edit</span>
                            </a>
                            <form action="#" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this health report?')">
                                    <i class="fas fa-trash text-sm"></i>
                                    <span class="hidden lg:inline ml-2">Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="content-card rounded-lg p-8 text-center">
                    <i class="fas fa-file-medical-alt text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No health reports found</p>
                    <p class="text-sm text-gray-400 mt-2">Health reports are automatically created when you view a student's
                        health record.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($healthReports->hasPages())
            <div class="content-card rounded-lg p-4">
                {{ $healthReports->links() }}
            </div>
        @endif
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