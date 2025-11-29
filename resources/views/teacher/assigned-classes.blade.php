<<<<<<< HEAD
@extends('layouts.app')
=======
@extends('layouts.teacher')
>>>>>>> c356163 (video call ui setup)

@section('title', 'My Assigned Classes')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">My Assigned Classes</h3>
            <div class="flex space-x-3">
                <a href="{{ route('teacher.dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Assigned Classes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            @if($assignedClasses->count() > 0)
                @foreach($assignedClasses as $classId => $subjects)
                @php $class = $subjects->first()->class; @endphp
                <div class="content-card rounded-lg p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900">{{ $class->name }}</h4>
                            <p class="text-gray-600 mt-1">{{ $class->shift }} Shift</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $class->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($class->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Class Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $classStats[$classId]['total_sections'] ?? 0 }}</div>
                            <div class="text-sm text-blue-600">Sections</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $classStats[$classId]['total_subjects'] ?? 0 }}</div>
                            <div class="text-sm text-green-600">Subjects</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $classStats[$classId]['total_students'] ?? 0 }}</div>
                            <div class="text-sm text-purple-600">Students</div>
                        </div>
                    </div>

                    <!-- Sections and Subjects -->
                    <div class="space-y-4">
                        <h5 class="text-lg font-medium text-gray-900">Teaching Assignments</h5>
                        @foreach($subjects->groupBy('section_id') as $sectionId => $sectionSubjects)
                        @php $section = $sectionSubjects->first()->section; @endphp
                        <div class="border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h6 class="font-medium text-gray-900">{{ $section->name }}</h6>
                                <p class="text-sm text-gray-600">Room: {{ $section->room_number ?? 'N/A' }}</p>
                            </div>
                            <div class="p-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($sectionSubjects as $assignment)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $assignment->subject->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
                <div class="content-card rounded-lg p-12 text-center">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Classes Assigned</h3>
                    <p class="text-gray-600">You haven't been assigned to any classes yet.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Summary -->
            <div class="content-card rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Teaching Summary</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Classes</span>
                        <span class="font-semibold text-gray-900">{{ $assignedClasses->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Sections</span>
                        <span class="font-semibold text-gray-900">{{ array_sum(array_column($classStats, 'total_sections')) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Subjects</span>
                        <span class="font-semibold text-gray-900">{{ array_sum(array_column($classStats, 'total_subjects')) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Students</span>
                        <span class="font-semibold text-gray-900">{{ array_sum(array_column($classStats, 'total_students')) }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('teacher.routine.index') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-calendar-alt mr-2"></i>View Routine
                    </a>
                    <a href="{{ route('teacher.homework.create') }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Add Homework
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