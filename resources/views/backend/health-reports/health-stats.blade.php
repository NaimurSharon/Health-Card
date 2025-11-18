@extends('layouts.app')

@section('title', 'Health Reports Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <h3 class="text-2xl font-bold">Health Reports Dashboard</h3>
            <p class="text-gray-600 mt-1">Overview of student health reports and statistics</p>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Reports -->
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-medical text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Reports</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalReports }}</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $reportsThisMonth }} this month</span>
                </div>
            </div>
        </div>

        <!-- Recent Checkups -->
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Recent Checkups</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $recentCheckups }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Last 7 days</p>
            </div>
        </div>

        <!-- Students Covered -->
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Students Covered</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $studentsWithReports }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">of {{ $totalStudents }} total</p>
            </div>
        </div>

        <!-- Average BMI -->
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-weight text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg. BMI</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($averageBMI, 1) }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Healthy range: 18.5-24.9</p>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="content-card rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h4 class="text-lg font-semibold text-gray-900">Recent Health Reports</h4>
            <a href="{{ route('admin.health-reports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View All
            </a>
        </div>
        
        <div class="space-y-4">
            @forelse($recentReports as $report)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-white transition-colors duration-200">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $report->student->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $report->student->class->name ?? 'N/A' }} • {{ $report->checkup_date->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-500">By {{ $report->checked_by }}</span>
                    <a href="{{ route('admin.health-reports.student', $report->student->user) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-file-medical-alt text-3xl mb-3 opacity-50"></i>
                <p>No recent health reports.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Quick Reports -->
        <div class="content-card rounded-lg p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h4>
            <div class="space-y-3">
                <a href="{{ route('admin.health-reports.create') }}" 
                   class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                    <i class="fas fa-plus text-blue-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Create New Report</span>
                </a>
                <a href="{{ route('admin.health-report-fields.manage') }}" 
                   class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200">
                    <i class="fas fa-cog text-green-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Manage Report Fields</span>
                </a>
                <a href="{{ route('admin.health-reports.export') }}" 
                   class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                    <i class="fas fa-download text-purple-600 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900">Export Reports</span>
                </a>
            </div>
        </div>

        <!-- Health Alerts -->
        <div class="content-card rounded-lg p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Health Alerts</h4>
            <div class="space-y-3">
                @forelse($healthAlerts as $alert)
                <div class="flex items-center p-3 bg-{{ $alert['color'] }}-50 rounded-lg">
                    <i class="fas fa-{{ $alert['icon'] }} text-{{ $alert['color'] }}-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $alert['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $alert['description'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>
                    <p class="text-sm">No health alerts at this time.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection