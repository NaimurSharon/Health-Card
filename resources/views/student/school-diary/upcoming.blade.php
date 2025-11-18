@extends('layouts.student')

@section('title', 'Upcoming Homework')
@section('subtitle', 'Homework with upcoming due dates')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Error Message -->
    @if(isset($error))
    <div class="content-card rounded-lg p-4 sm:p-6 bg-red-50 border border-red-200">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-red-500 me-3 mt-1 flex-shrink-0"></i>
            <div class="flex-1">
                <h4 class="font-semibold text-red-800 text-sm sm:text-base">Error</h4>
                <p class="text-red-700 text-sm">{{ $error }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">Upcoming Homework</h3>
                    <p class="text-blue-100 text-sm sm:text-base">
                        @if($studentDetails && $studentDetails->class && $studentDetails->section)
                            Class: {{ $studentDetails->class->name }}, Section: {{ $studentDetails->section->name }}
                        @else
                            Class information not available
                        @endif
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <a href="{{ route('student.school-diary') }}" 
                       class="bg-white text-blue-600 px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm sm:text-base text-center">
                        All Homework
                    </a>
                    <a href="{{ route('student.school-diary.today') }}" 
                       class="bg-white text-green-600 px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm sm:text-base text-center">
                        Today's
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Homework List -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        @if(isset($homeworks) && $homeworks->count() > 0)
            <div class="space-y-4 sm:space-y-6">
                @foreach($homeworks as $homework)
                <div class="p-4 sm:p-6 bg-orange-50 border border-orange-200 rounded-lg">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3 sm:mb-4">
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 space-y-2 sm:space-y-0 mb-2">
                                <h4 class="text-base sm:text-lg font-semibold text-gray-900">{{ $homework->homework_title }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                                        {{ $homework->subject->name ?? 'General' }}
                                    </span>
                                    @if($homework->due_date)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                        Due: {{ $homework->due_date->format('M j') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-gray-700 text-sm sm:text-base mb-3 sm:mb-4">{{ $homework->homework_description }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm text-gray-600">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-user me-2 w-4 sm:w-5 text-xs"></i>
                                <span><strong>Teacher:</strong> {{ $homework->teacher->name ?? 'Teacher' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar me-2 w-4 sm:w-5 text-xs"></i>
                                <span><strong>Assigned:</strong> {{ $homework->entry_date->format('M j, Y') }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-clock me-2 w-4 sm:w-5 text-xs"></i>
                                <span><strong>Due Date:</strong> {{ $homework->due_date ? $homework->due_date->format('M j, Y') : 'No due date' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle me-2 w-4 sm:w-5 text-xs"></i>
                                <span><strong>Status:</strong> <span class="capitalize">{{ $homework->status }}</span></span>
                            </div>
                        </div>
                    </div>

                    @php
                        $attachments = is_string($homework->attachments) 
                            ? json_decode($homework->attachments, true) 
                            : $homework->attachments;
                    @endphp
                    
                    @if(!empty($attachments))
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-orange-200">
                            <h5 class="font-semibold text-gray-900 text-sm sm:text-base mb-2">Attachments:</h5>
                            <div class="flex flex-wrap gap-2">
                                @foreach($attachments as $attachment)
                                    @php
                                        $filePath = is_array($attachment) ? $attachment['path'] ?? $attachment[0] ?? '' : $attachment;
                                        $fileName = is_array($attachment) ? $attachment['name'] ?? basename($filePath) : basename($attachment);
                                    @endphp
                                    <a href="{{ asset('public/storage/' . $filePath) }}" 
                                       target="_blank"
                                       class="flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                                        <i class="fas fa-paperclip text-gray-400 text-xs sm:text-sm"></i>
                                        <span class="text-gray-700 truncate max-w-[120px] sm:max-w-[200px]">{{ $fileName }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4 sm:mt-6">
                {{ $homeworks->links() }}
            </div>
        @else
            <div class="text-center py-8 sm:py-12">
                <i class="fas fa-calendar-check text-3xl sm:text-4xl mb-3 sm:mb-4 text-orange-300"></i>
                <h4 class="text-lg sm:text-xl font-semibold text-gray-500 mb-2">No Upcoming Homework</h4>
                <p class="text-gray-400 text-sm sm:text-base">
                    @if(!$studentDetails)
                        Unable to load homework. Please contact administration.
                    @else
                        Great! You don't have any homework with upcoming due dates.
                    @endif
                </p>
                @if($studentDetails)
                <a href="{{ route('student.school-diary.today') }}" 
                   class="inline-block mt-4 bg-orange-600 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm sm:text-base">
                    Check Today's Homework
                </a>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
.content-card {
    backdrop-filter: blur(12px);
    border: 1px solid rgba(229, 231, 235, 0.8);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.table-header {
    background: #06AC73;
    color: white;
    border-bottom: 1px solid #e5e7eb;
    backdrop-filter: blur(4px);
}
</style>
@endsection