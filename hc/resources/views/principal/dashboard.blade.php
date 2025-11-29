@extends('layouts.principal')

@section('title', 'Principal Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div>
                
                <h3 class="text-2xl font-bold">Principal Dashboard</h3>
                <p class="text-gray-200 mt-1">Welcome back, {{ $teacher->name }}!</p>
                <p class="text-sm text-gray-100 mt-1">{{ $school->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-200">{{ $today->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Students</h4>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_students'] }}</p>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Teachers</h4>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_teachers'] }}</p>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-door-open text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Classes</h4>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_classes'] }}</p>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-th-large text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Sections</h4>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sections'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Class Distribution -->
            <div class="content-card rounded-lg p-6">
                <h4 class="text-xl font-semibold text-gray-900 mb-6">Student Distribution by Class</h4>
                
                @if($classDistribution->count() > 0)
                    <div class="space-y-4">
                        @foreach($classDistribution as $distribution)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold">{{ $distribution->class->numeric_value ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">{{ $distribution->class->name ?? 'N/A' }}</h5>
                                    <p class="text-sm text-gray-500">{{ $distribution->class->sections_count ?? 0 }} Sections</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900">{{ $distribution->total }} Students</p>
                                <p class="text-sm text-gray-500">
                                    Capacity: {{ $distribution->class->capacity ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No student data available</p>
                    </div>
                @endif
            </div>

            <!-- Recent Notices -->
            <div class="content-card rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-xl font-semibold text-gray-900">Recent Notices</h4>
                    <a href="{{ route('principal.notices.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All Notices
                    </a>
                </div>
                
                @if($recentNotices->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentNotices as $notice)
                        <div class="p-4 bg-gray-50 rounded-lg border-l-4 
                            {{ $notice->priority == 'high' ? 'border-red-500 bg-red-50' : 
                               ($notice->priority == 'medium' ? 'border-yellow-500 bg-yellow-50' : 'border-blue-500 bg-blue-50') }}">
                            <div class="flex justify-between items-start mb-2">
                                <h5 class="font-semibold text-gray-900">{{ $notice->title }}</h5>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $notice->priority == 'high' ? 'bg-red-100 text-red-800' : 
                                       ($notice->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($notice->priority) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($notice->content, 150) }}</p>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>Expires: {{ $notice->expiry_date->format('M j, Y') }}</span>
                                <span>Published: {{ $notice->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No recent notices</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6">
                <h4 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('principal.students.index') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-user-graduate mr-2"></i>Manage Students
                    </a>
                    <a href="{{ route('principal.teachers.index') }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>Manage Teachers
                    </a>
                    <a href="{{ route('principal.classes.index') }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-door-open mr-2"></i>Manage Classes
                    </a>
                    <a href="{{ route('principal.notices.create') }}" 
                       class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-bullhorn mr-2"></i>Create Notice
                    </a>
                    <a href="{{ route('principal.routine.index') }}" 
                       class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Class Routine
                    </a>
                </div>
            </div>

            <!-- Recent Homeworks -->
            <div class="content-card rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xl font-semibold text-gray-900">Recent Homeworks</h4>
                    <a href="{{ route('principal.homework.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View All
                    </a>
                </div>
                
                @if($recentHomeworks->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentHomeworks as $homework)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <h5 class="font-medium text-gray-900 text-sm">{{ $homework->homework_title }}</h5>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                    {{ $homework->status == 'active' ? 'bg-green-100 text-green-800' : 
                                       ($homework->status == 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($homework->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-2">
                                {{ $homework->class->name }} - {{ $homework->section->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Due: {{ $homework->due_date ? \Carbon\Carbon::parse($homework->due_date)->format('M j') : 'No due date' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                By: {{ $homework->teacher->name ?? 'N/A' }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-tasks text-2xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500 text-sm">No recent homeworks</p>
                    </div>
                @endif
            </div>

            <!-- School Information -->
            <div class="content-card rounded-lg p-6">
                <h4 class="text-xl font-semibold text-gray-900 mb-4">School Information</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">School Code:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $school->code }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Established:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $school->established_year }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Type:</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($school->type) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Medium:</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($school->medium) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Academic System:</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($school->academic_system) }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('principal.school.edit') }}" 
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-cog mr-2"></i>Edit School Info
                    </a>
                </div>
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

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>
@endsection