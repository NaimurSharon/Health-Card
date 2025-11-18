@extends('layouts.app')

@section('title', $class->name . ' - Class Details')

@section('content')
<div class="space-y-6">
    <!-- Class Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ $class->name }}</h3>
                    <p class="text-gray-600">Class {{ $class->numeric_value }} • {{ ucfirst($class->shift) }} Shift</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.classes.edit', $class) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Class
                </a>
                <a href="{{ route('admin.classes.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Class Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Basic Information -->
        <div class="space-y-6">
            <!-- Class Details Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Class Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Class Name</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $class->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Numeric Value</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $class->numeric_value }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Shift</span>
                        <span class="text-sm text-gray-900 font-semibold capitalize">{{ $class->shift }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Capacity</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $class->capacity }} students</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $class->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($class->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Statistics -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Statistics</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Total Sections</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $sections->count() }}</p>
                        </div>
                        <i class="fas fa-layer-group text-blue-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-green-600">Total Students</p>
                            <p class="text-2xl font-bold text-green-700">{{ $students->total() }}</p>
                        </div>
                        <i class="fas fa-users text-green-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Subjects</p>
                            <p class="text-2xl font-bold text-purple-700">6969</p>
                        </div>
                        <i class="fas fa-book text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Column - Sections & Subjects -->
        <div class="space-y-6">
            <!-- Sections List -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Sections</h4>
                
                <div class="space-y-3">
                    @forelse($sections as $section)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200 border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-green-600">{{ $section->name }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $section->name }} Section</p>
                                <p class="text-xs text-gray-500">Room {{ $section->room_number }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ $section->students_count ?? 0 }} students</p>
                            <p class="text-xs text-gray-500">Capacity: {{ $section->capacity }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 border border-gray-200 rounded-lg bg-gray-50">
                        <i class="fas fa-layer-group text-2xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No sections found</p>
                        <p class="text-sm text-gray-400 mt-1">Sections will appear here once added</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Subjects -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Subjects</h4>
                
                <div class="space-y-3">
                </div>
            </div>
        </div>

        <!-- Right Column - Quick Actions & Recent Students -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.sections.create') }}" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Add Section</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Manage Students</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-purple-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Manage Subjects</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-orange-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">View Routine</span>
                    </a>
                </div>
            </div>

            <!-- Recent Students -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Recent Students</h4>
                
                <div class="space-y-3">
                    @forelse($students->take(5) as $student)
                    <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded transition-colors duration-200">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-medium text-gray-700">
                                {{ substr($student->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $student->user->name }}</p>
                            <p class="text-xs text-gray-500">Roll: {{ $student->roll_number }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-users text-xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500 text-sm">No students found</p>
                    </div>
                    @endforelse
                    
                    @if($students->count() > 5)
                    <div class="text-center pt-2">
                        <a href="{{ route('admin.classes.students', $class) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            View All Students ({{ $students->total() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Class Routine Preview -->
    <div class="content-card rounded-lg shadow-sm">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h4 class="text-xl font-semibold">Class Routine</h4>
            <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                View Full Routine
            </a>
        </div>
        <div class="p-6">
            @if($routines->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($routines->take(3) as $day => $periods)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-semibold text-gray-900 mb-3 capitalize">{{ $day }}</h5>
                    <div class="space-y-2">
                        @foreach($periods->take(3) as $routine)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700">{{ $routine->subject->name }}</span>
                            <span class="text-gray-500">{{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 border border-gray-200 rounded-lg bg-gray-50">
                <i class="fas fa-calendar-alt text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500">No routine scheduled</p>
                <p class="text-sm text-gray-400 mt-1">Class routine will appear here once created</p>
            </div>
            @endif
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