<<<<<<< HEAD
@extends('layouts.app')
=======
@extends('layouts.teacher')
>>>>>>> c356163 (video call ui setup)

@section('title', 'Class Routine')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Class Routine</h3>
            <div class="flex space-x-3">
                <a href="{{ route('teacher.routine.weekly') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-calendar-week mr-2"></i>Weekly View
                </a>
                <a href="{{ route('teacher.dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Day Filter -->
    <div class="content-card rounded-lg p-6">
        <div class="flex flex-wrap gap-2">
            @foreach($days as $day)
            <a href="{{ route('teacher.routine.index', ['day' => $day]) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ $selectedDay == $day ? 
                         'bg-blue-600 text-white' : 
                         'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ ucfirst($day) }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Routine for Selected Day -->
    <div class="content-card rounded-lg p-6">
        <h4 class="text-xl font-semibold text-gray-900 mb-6">
            Schedule for {{ ucfirst($selectedDay) }}
        </h4>

        @if($routines->count() > 0)
            <div class="space-y-6">
                @foreach($routines as $classId => $classRoutines)
                @php $class = $classRoutines->first()->class; @endphp
                <div class="border border-gray-200 rounded-lg">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h5 class="text-lg font-semibold text-gray-900">{{ $class->name }}</h5>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($classRoutines as $routine)
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center space-x-6">
                                <div class="flex-shrink-0 w-16 text-center">
                                    <span class="text-2xl font-bold text-blue-600">{{ $routine->period }}</span>
                                    <div class="text-xs text-gray-500 mt-1">Period</div>
                                </div>
                                <div class="flex-1">
                                    <h6 class="font-semibold text-gray-900">{{ $routine->subject->name }}</h6>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $routine->section->name }} • 
                                        {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($routine->end_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-door-open mr-1"></i>
                                    {{ $routine->room ?? 'TBA' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Classes Scheduled</h3>
                <p class="text-gray-600">You have no classes scheduled for {{ ucfirst($selectedDay) }}.</p>
            </div>
        @endif
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