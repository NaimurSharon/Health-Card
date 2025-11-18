@extends('layouts.app')

@section('title', $teacher->name . ' - Teacher Details')

@section('content')
<div class="space-y-6">
    <!-- Teacher Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ $teacher->name }}</h3>
                    <p class="text-white">{{ $teacher->qualification ?? 'Teacher' }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Teacher
                </a>
                <a href="{{ route('admin.teachers.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Teacher Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Basic Information -->
        <div class="space-y-6">
            <!-- Profile Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Profile Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Email</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $teacher->email }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Phone</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $teacher->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Gender</span>
                        <span class="text-sm text-gray-900 font-semibold capitalize">{{ $teacher->gender ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Date of Birth</span>
                        <span class="text-sm text-gray-900 font-semibold">
                            {{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Professional Information</h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Qualification</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $teacher->qualification ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Experience</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $teacher->experience ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Joining Date</span>
                        <span class="text-sm text-gray-900 font-semibold">
                            {{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Salary</span>
                        <span class="text-sm text-gray-900 font-semibold">
                            {{ $teacher->salary ? '$' . number_format($teacher->salary, 2) : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Classes -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Assigned Classes & Subjects</h4>
                
                @if($teacher->classSubjects->count() > 0)
                <div class="space-y-3">
                    @foreach($classes as $classData)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $classData['class']->name }}</p>
                            @if($classData['sections'])
                                <p class="text-xs text-gray-500">Sections: {{ $classData['sections'] }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">
                                Subjects: 
                                @php
                                    $subjects = $teacher->classSubjects
                                        ->where('class_id', $classData['class']->id)
                                        ->pluck('subject.name')
                                        ->filter()
                                        ->unique()
                                        ->implode(', ');
                                @endphp
                                {{ $subjects }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $classData['class']->shift ?? 'Regular' }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500">No classes assigned</p>
                @endif
            </div>
        </div>

        <!-- Middle Column - Contact & Subjects -->
        <div class="space-y-6">
            <!-- Contact Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Contact Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-envelope text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Email</p>
                            <p class="text-sm text-gray-900">{{ $teacher->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-phone text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Phone</p>
                            <p class="text-sm text-gray-900">{{ $teacher->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Address</p>
                            <p class="text-sm text-gray-900">{{ $teacher->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Subjects</h4>
                
                @if($teacher->subjects->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($teacher->subjects as $subject)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                        {{ $subject->name }}
                    </span>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500">No subjects assigned</p>
                @endif
            </div>
        </div>

        <!-- Right Column - Classes & Status -->
        <div class="space-y-6">
            <!-- Classes -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Assigned Classes</h4>
                
                @if($teacher->classes->count() > 0)
                <div class="space-y-3">
                    @foreach($teacher->classes as $class)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $class->name }}</p>
                            <p class="text-xs text-gray-500">
                                @if($class->section)
                                    Section: {{ $class->section->name }}
                                @endif
                            </p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $class->shift ?? 'Regular' }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500">No classes assigned</p>
                @endif
            </div>

            <!-- Status & Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Status</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Account Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($teacher->status) }}
                        </span>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200/60">
                        <p class="text-sm font-medium text-gray-600 mb-3">Quick Actions</p>
                        <div class="space-y-2">
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                               class="flex items-center space-x-2 text-blue-600 hover:text-blue-700 text-sm">
                                <i class="fas fa-edit"></i>
                                <span>Edit Teacher Information</span>
                            </a>
                            <a href="#" 
                               class="flex items-center space-x-2 text-green-600 hover:text-green-700 text-sm">
                                <i class="fas fa-calendar"></i>
                                <span>View Schedule</span>
                            </a>
                            <a href="#" 
                               class="flex items-center space-x-2 text-purple-600 hover:text-purple-700 text-sm">
                                <i class="fas fa-tasks"></i>
                                <span>View Assigned Tasks</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="content-card rounded-lg p-6 text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-chalkboard text-blue-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $teacher->classes->count() }}</p>
            <p class="text-sm text-gray-600">Classes</p>
        </div>
        
        <div class="content-card rounded-lg p-6 text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-book text-green-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $teacher->subjects->count() }}</p>
            <p class="text-sm text-gray-600">Subjects</p>
        </div>
        
        <div class="content-card rounded-lg p-6 text-center">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-clock text-purple-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">
                @if($teacher->joining_date)
                    {{ \Carbon\Carbon::parse($teacher->joining_date)->diffInYears(now()) }}y
                @else
                    N/A
                @endif
            </p>
            <p class="text-sm text-gray-600">Experience</p>
        </div>
        
        <div class="content-card rounded-lg p-6 text-center">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-star text-orange-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">Active</p>
            <p class="text-sm text-gray-600">Status</p>
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