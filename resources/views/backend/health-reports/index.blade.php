@extends('layouts.app')

@section('title', 'Health Reports')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Health Reports</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.health-report-fields.manage') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-cog mr-2"></i>Manage Fields
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.health-reports.index') }}">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Class Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                    <select name="class_id" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" 
                           class="w-40 border-gray-300 rounded-lg text-sm bg-white/90 px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" 
                           class="w-40 border-gray-300 rounded-lg text-sm bg-white/90 px-3 py-2">
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by student name..." 
                           class="w-64 border-gray-300 rounded-lg text-sm bg-white/90 px-3 py-2">
                </div>

                <!-- Action Buttons -->
                <div class="self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.health-reports.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Health Reports Table -->
    <div class="content-card overflow-hidden">
        <!-- Table Header -->
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h4 class="text-lg font-semibold">All Health Reports</h4>
            <div class="text-sm text-gray-600">
                Total: {{ $healthReports->total() }} reports
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full transparent-table">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Class
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Checkup Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Checked By
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Last Updated
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/40">
                    @forelse($healthReports as $report)
                    <tr class="hover:bg-white/60 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    @if($report->student->user->profile_image)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ asset('public/storage/' . $report->student->user->profile_image) }}" 
                                             alt="{{ $report->student->user->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:from-blue-200 group-hover:to-purple-200 transition-all duration-200">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $report->student->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $report->student->student_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $report->student->class->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">Roll: {{ $report->student->roll_number ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $report->checkup_date->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $report->checked_by }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $report->updated_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.health-reports.student', $report->student->user) }}" 
                                   class="text-blue-600 hover:text-blue-800 bg-white/70 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200"
                                   title="View Health Report">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.health-reports.edit', $report) }}" 
                                   class="text-green-600 hover:text-green-800 bg-white/70 hover:bg-green-50 p-2 rounded-lg transition-all duration-200"
                                   title="Edit Health Report">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admin.health-reports.destroy', $report) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 bg-white/70 hover:bg-red-50 p-2 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this health report?')"
                                            title="Delete Health Report">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="text-gray-500 text-lg">
                                <i class="fas fa-file-medical-alt text-4xl mb-4 opacity-50"></i>
                                <p>No health reports found.</p>
                                <p class="text-sm mt-2">Health reports are automatically created when you view a student's health record.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($healthReports->hasPages())
        <div class="table-header px-6 py-4 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $healthReports->firstItem() }} to {{ $healthReports->lastItem() }} of {{ $healthReports->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $healthReports->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .transparent-table {
        background: transparent;
        backdrop-filter: blur(10px);
    }
    
    .transparent-table thead tr {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        backdrop-filter: blur(10px);
    }
    
    .transparent-table tbody tr {
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
    }
    
    .transparent-table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .content-card {
        background: transparent;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection