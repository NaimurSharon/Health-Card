@extends('layouts.student')

@section('title', 'Homework Details')
@section('subtitle', 'View homework details and instructions')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">Homework Details</h3>
                    <p class="text-blue-100 text-sm sm:text-base">
                        @if(isset($studentDetails) && $studentDetails->class && $studentDetails->section)
                            Class: {{ $studentDetails->class->name }}, Section: {{ $studentDetails->section->name }}
                        @else
                            Homework Details
                        @endif
                    </p>
                </div>
                <a href="{{ route('student.school-diary') }}" 
                   class="bg-white text-blue-600 px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm sm:text-base w-full sm:w-auto text-center">
                    Back to Diary
                </a>
            </div>
        </div>
    </div>

    @if(isset($homework))
    <!-- Homework Details -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4 sm:mb-6">
            <div class="flex-1">
                <h4 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">{{ $homework->homework_title }}</h4>
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4 space-y-1 sm:space-y-0">
                    <span class="flex items-center">
                        <i class="fas fa-book me-2 text-xs"></i>
                        {{ $homework->subject->name ?? 'General' }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-user me-2 text-xs"></i>
                        {{ $homework->teacher->name ?? 'Teacher' }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-calendar me-2 text-xs"></i>
                        {{ $homework->entry_date->format('M j, Y') }}
                    </span>
                </div>
            </div>
            <span class="px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium 
                {{ $homework->status == 'completed' ? 'bg-green-100 text-green-800' : 
                   ($homework->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                {{ ucfirst($homework->status) }}
            </span>
        </div>

        <!-- Homework Description -->
        <div class="mb-4 sm:mb-6">
            <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3">Instructions</h5>
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                <p class="text-gray-700 whitespace-pre-line text-sm sm:text-base">{{ $homework->homework_description }}</p>
            </div>
        </div>

        <!-- Homework Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <h6 class="font-semibold text-gray-900 text-sm sm:text-base mb-2">Assignment Information</h6>
                    <div class="space-y-2 text-xs sm:text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Assigned Date:</span>
                            <span class="font-medium">{{ $homework->entry_date->format('F j, Y') }}</span>
                        </div>
                        @if($homework->due_date)
                        <div class="flex justify-between">
                            <span>Due Date:</span>
                            <span class="font-medium text-red-600">{{ $homework->due_date->format('F j, Y') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span class="font-medium capitalize">{{ $homework->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <h6 class="font-semibold text-gray-900 text-sm sm:text-base mb-2">Teacher Information</h6>
                    <div class="space-y-2 text-xs sm:text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Teacher:</span>
                            <span class="font-medium">{{ $homework->teacher->name ?? 'Not Available' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Subject:</span>
                            <span class="font-medium">{{ $homework->subject->name ?? 'General' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Class & Section:</span>
                            <span class="font-medium">
                                {{ $homework->class->name ?? 'N/A' }} - {{ $homework->section->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attachments -->
        @php
            $attachments = is_string($homework->attachments) 
                ? json_decode($homework->attachments, true) 
                : $homework->attachments;
        @endphp
        
        @if(!empty($attachments))
            <div class="mt-4 pt-4 border-t border-orange-200">
                <h5 class="font-semibold text-gray-900 text-sm sm:text-base mb-2">Attachments:</h5>
                <div class="flex flex-wrap gap-2">
                    @foreach($attachments as $attachment)
                        @php
                            $filePath = is_array($attachment) ? $attachment['path'] ?? $attachment[0] ?? '' : $attachment;
                            $fileName = is_array($attachment) ? $attachment['name'] ?? basename($filePath) : basename($attachment);
                        @endphp
                        <a href="{{ asset('storage/' . $filePath) }}" 
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
    @else
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm text-center">
        <i class="fas fa-exclamation-triangle text-3xl sm:text-4xl mb-3 sm:mb-4 text-yellow-500"></i>
        <h4 class="text-lg sm:text-xl font-semibold text-gray-500 mb-2">Homework Not Found</h4>
        <p class="text-gray-400 text-sm sm:text-base mb-4">The homework you're looking for doesn't exist or you don't have access to view it.</p>
        <a href="{{ route('student.school-diary') }}" 
           class="bg-blue-600 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
            Back to School Diary
        </a>
    </div>
    @endif
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