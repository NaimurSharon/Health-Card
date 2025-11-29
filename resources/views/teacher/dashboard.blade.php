@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold">Teacher Dashboard</h3>
                <p class="text-gray-600 mt-1">Welcome back, {{ $teacher->name }}!</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">{{ $today->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Today's Classes</h4>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_classes_today'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Upcoming Classes</h4>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['upcoming_classes'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Assigned Subjects</h4>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['assigned_subjects'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Sections</h4>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sections'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's Schedule -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Upcoming Classes -->
            <div class="content-card rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-xl font-semibold text-gray-900">Upcoming Classes (Next 2 Hours)</h4>
                    <a href="{{ route('teacher.routine.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Full Routine
                    </a>
                </div>
                
                @if($upcomingClasses->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingClasses as $class)
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-white rounded-lg border border-blue-200 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold">{{ $class->period }}</span>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">{{ $class->subject->name ?? 'N/A' }}</h5>
                                    <p class="text-sm text-gray-600">
                                        {{ $class->class->name ?? 'N/A' }} - {{ $class->section->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Room: {{ $class->room ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clock text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No upcoming classes in the next 2 hours</p>
                    </div>
                @endif
            </div>

            <!-- Today's Full Schedule -->
            <div class="content-card rounded-lg p-6">
                <h4 class="text-xl font-semibold text-gray-900 mb-6">Today's Schedule</h4>
                
                @if($todayRoutines->count() > 0)
                    <div class="space-y-3">
                        @foreach($todayRoutines as $routine)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="text-lg font-semibold text-gray-900">{{ $routine->period }}</span>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">{{ $routine->subject->name ?? 'N/A' }}</h5>
                                    <p class="text-sm text-gray-600">
                                        {{ $routine->class->name ?? 'N/A' }} - {{ $routine->section->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                </p>
                                <span class="text-xs text-gray-500">{{ $routine->room ?? 'No room' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No classes scheduled for today</p>
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
                    <a href="{{ route('teacher.homework.create') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Add Homework
                    </a>
                    <a href="{{ route('teacher.homework.index') }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-tasks mr-2"></i>View Homeworks
                    </a>
                    <a href="{{ route('teacher.assigned-classes') }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-users mr-2"></i>My Classes
                    </a>
                    <a href="{{ route('teacher.routine.index') }}" 
                       class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Weekly Routine
                    </a>
                </div>
            </div>

            <!-- Recent Homeworks -->
            <div class="content-card rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xl font-semibold text-gray-900">Recent Homeworks</h4>
                    <a href="{{ route('teacher.homework.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
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
                                Due: {{ $homework->due_date ? $homework->due_date->format('M j') : 'No due date' }}
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

            <!-- Assigned Subjects -->
            <div class="content-card rounded-lg p-6">
                <h4 class="text-xl font-semibold text-gray-900 mb-4">My Subjects</h4>
                
                @if($assignedSubjects->count() > 0)
                    <div class="space-y-3">
                        @foreach($assignedSubjects->take(5) as $classId => $subjects)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <h5 class="font-medium text-gray-900 text-sm mb-2">
                                {{ $subjects->first()->class->name ?? 'N/A' }}
                            </h5>
                            <div class="flex flex-wrap gap-1">
                                @foreach($subjects->unique('subject_id') as $subject)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $subject->subject->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-book text-2xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500 text-sm">No assigned subjects</p>
                    </div>
                @endif
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