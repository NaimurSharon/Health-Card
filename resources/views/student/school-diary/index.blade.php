@extends('layouts.student')

@section('title', 'School Diary - Homework')
@section('subtitle', 'View your class homework and assignments')

@section('content')
<div class="space-y-6">
    @if(isset($error))
    <div class="rounded-lg p-4 bg-red-50 border border-red-200 shadow-sm">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-red-500 me-3 mt-1 flex-shrink-0"></i>
            <div class="flex-1">
                <h4 class="font-semibold text-red-800 text-sm">Error</h4>
                <p class="text-red-700 text-sm">{{ $error }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="glass-card rounded-xl p-4 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800  { detectLanguageClass('School Diary') }}">Diary of</h3>
                <p class="text-gray-500 text-sm {{ detectLanguageClass($studentDetails && $studentDetails->class && $studentDetails->section ? 'Class: ' . $studentDetails->class->name . ' | Section: ' . $studentDetails->section->name : '') }}">
                    @if($studentDetails && $studentDetails->class && $studentDetails->section)
                        Class: {{ $studentDetails->class->name }} | Section: {{ $studentDetails->section->name }}
                    @endif
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                 <form method="GET" action="{{ route('student.school-diary') }}" class="flex items-center gap-2">
                    <input type="date" 
                        name="date" 
                        value="{{ $filterDate }}"
                        class="px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium text-sm">
                        Go
                    </button>
                </form>

                @if($todaysHomeworks->count() > 0)
                <div class="flex gap-2">
                    <a href="{{ route('student.school-diary.download-pdf', ['date' => $filterDate]) }}" 
                       class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition font-medium flex items-center text-sm">
                        <i class="fas fa-download me-2"></i> PDF
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="w-full">

        @if($todaysHomeworks->count() > 0)
            <!-- Desktop/Laptop View -->
            <div class="hidden lg:block w-full overflow-x-auto">
                <div class="diary-grid mb-4 font-bold text-white text-lg tracking-wide">
                    <div class="header-card text-center">Period</div>
                    <div class="header-card text-center">Subject</div>
                    <div class="header-card text-center">Description</div>
                    <div class="header-card text-center">Teacher</div>
                </div>

                <div class="space-y-2">
                    @foreach($todaysHomeworks as $index => $homework)
                    <div class="diary-grid content-row py-1 px-2 items-stretch">
                        <div class="bg-white rounded-lg p-4 shadow-sm flex items-center justify-center font-medium text-gray-600">
                            {{ $index + 1 }}
                        </div>
                    
                        <div class="bg-white rounded-lg p-4 shadow-sm flex items-center text-gray-800 font-medium">
                            {{ $homework->subject->name ?? 'General' }}
                        </div>
                    
                        <div class="bg-white rounded-lg px-4 py-3 shadow-sm text-gray-700 text-sm leading-relaxed">
                            <div class="font-medium h6 text-gray-900 mb-1">{{ $homework->homework_title }}</div>
                            <div class="text-gray-600">{{ $homework->homework_description }}</div>
                        </div>
                    
                        <div class="bg-white rounded-lg p-4 shadow-sm flex items-center text-gray-600 font-medium">
                            {{ $homework->teacher->name ?? 'N/A' }}
                        </div>
                    </div>
                    @if(!$loop->last)
                    <!--<div class="border-b border-gray-200/50 mx-2"></div>-->
                    @endif
                    @endforeach
                </div>
            </div>
            
            <div class="lg:hidden space-y-4 w-full overflow-x-auto">
                <div class="diary-grid mb-4 font-bold text-white text-lg tracking-wide">
                    <div class="header-card text-center">Period</div>
                    <div class="header-card text-center">Subject</div>
                    <div class="header-card text-center">Description</div>
                    <div class="header-card text-center">Teacher</div>
                </div>

                <div class="space-y-2">
                    @foreach($todaysHomeworks as $index => $homework)
                    <div class="diary-grid content-row py-1 px-2 items-stretch">
                        <div class="bg-white rounded-lg p-1 shadow-sm flex items-center justify-center font-medium text-gray-600">
                            {{ $index + 1 }}
                        </div>
                    
                        <div class="bg-white rounded-lg p-1 shadow-sm flex items-center text-gray-800 font-medium">
                            {{ $homework->subject->name ?? 'General' }}
                        </div>
                    
                        <div class="bg-white rounded-lg px-1 py-3 shadow-sm text-gray-700 text-sm leading-relaxed">
                            <div class="font-medium h6 text-gray-900 mb-1">{{ $homework->homework_title }}</div>
                            <div class="text-gray-600">{{ $homework->homework_description }}</div>
                        </div>
                    
                        <div class="bg-white rounded-lg p-1 shadow-sm flex items-center text-gray-600 font-medium">
                            {{ $homework->teacher->name ?? 'N/A' }}
                        </div>
                    </div>
                    @if(!$loop->last)
                    <!--<div class="border-b border-gray-200/50 mx-2"></div>-->
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Mobile/Tablet View -->
            <div class="lg:hidden space-y-4">
                @foreach($todaysHomeworks as $index => $homework)
                <div class="glass-card rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="bg-green-50 rounded-lg p-3 text-center">
                            <div class="text-xs text-green-600 font-medium mb-1">Period</div>
                            <div class="text-gray-800 font-semibold">{{ $index + 1 }}</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3 text-center">
                            <div class="text-xs text-green-600 font-medium mb-1">Subject</div>
                            <div class="text-gray-800 font-semibold">{{ $homework->subject->name ?? 'General' }}</div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <div class="text-xs text-gray-500 font-medium mb-1">Description</div>
                            <div class="text-gray-800">
                                <div class="font-semibold text-sm mb-1">{{ $homework->homework_title }}</div>
                                <div class="text-gray-600 text-sm">{{ $homework->homework_description }}</div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-xs text-gray-500 font-medium mb-1">Teacher</div>
                            <div class="text-gray-800 font-medium text-sm">{{ $homework->teacher->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="glass-card rounded-xl p-8 sm:p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clipboard-check text-green-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-600">No tasks found</h3>
                <p class="text-gray-400 mt-2">There is no homework assigned for this date.</p>
                @if($filterDate != today()->format('Y-m-d'))
                    <a href="{{ route('student.school-diary') }}" class="inline-block mt-4 text-green-600 font-medium hover:underline">Go to Today</a>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
    /* CSS Grid Configuration for Desktop */
    .diary-grid {
        display: grid;
        grid-template-columns: 100px 220px 1fr 180px;
        gap: 20px;
        align-items: start;
    }

    /* The Separated Header Blocks */
    .header-card {
        background-color: #00a884;
        padding: 14px 10px;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* Glass Effect for containers */
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .overflow-x-auto {
            overflow-x: auto;
            padding-bottom: 20px;
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 720px) {
        .diary-grid {
            grid-template-columns: 80px 140px 1fr 100px; /* reduced widths */
            font-size: 14px; /* slightly smaller text */
        }
    }
</style>
@endsection