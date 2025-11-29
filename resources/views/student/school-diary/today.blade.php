@extends('layouts.student')

@section('title', "Today's Homework")
@section('subtitle', 'Homework assigned for today')

@section('content')
<div class="space-y-6">
    <!-- Error Message -->
    @if(isset($error))
    <div class="content-card rounded-lg p-6 bg-red-50 border border-red-200">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-500 me-3"></i>
            <div>
                <h4 class="font-semibold text-red-800">Error</h4>
                <p class="text-red-700">{{ $error }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">Today's Homework</h3>
                    <p class="text-blue-100">
                        {{ today()->format('l, F j, Y') }} - 
                        @if($studentDetails && $studentDetails->class && $studentDetails->section)
                            Class: {{ $studentDetails->class->name }}, Section: {{ $studentDetails->section->name }}
                        @else
                            Class information not available
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('student.school-diary') }}" 
                       class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        All Homework
                    </a>
                    <a href="{{ route('student.school-diary.upcoming') }}" 
                       class="bg-white text-orange-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        Upcoming
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Homework List -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        @if(isset($homeworks) && $homeworks->count() > 0)
            <div class="space-y-6">
                @foreach($homeworks as $homework)
                <div class="p-6 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $homework->homework_title }}</h4>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    {{ $homework->subject->name ?? 'General' }}
                                </span>
                            </div>
                            <p class="text-gray-700 mb-4">{{ $homework->homework_description }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-user me-2 w-5"></i>
                                <span><strong>Teacher:</strong> {{ $homework->teacher->name ?? 'Teacher' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar me-2 w-5"></i>
                                <span><strong>Assigned:</strong> {{ $homework->entry_date->format('M j, Y') }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @if($homework->due_date)
                            <div class="flex items-center">
                                <i class="fas fa-clock me-2 w-5"></i>
                                <span><strong>Due Date:</strong> {{ $homework->due_date->format('M j, Y') }}</span>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <i class="fas fa-check-circle me-2 w-5"></i>
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
                        <div class="mt-4 pt-4 border-t border-orange-200">
                            <h5 class="font-semibold text-gray-900 mb-2">Attachments:</h5>
                            <div class="flex flex-wrap gap-2">
                                @foreach($attachments as $attachment)
                                    @php
                                        // If stored as simple list like ["file.pdf"]
                                        $filePath = is_array($attachment) ? $attachment['path'] ?? $attachment[0] ?? '' : $attachment;
                                        $fileName = is_array($attachment) ? $attachment['name'] ?? basename($filePath) : basename($attachment);
                                    @endphp
                                    <a href="{{ asset('storage/' . $filePath) }}" 
                                       target="_blank"
                                       class="flex items-center space-x-2 bg-white px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-paperclip text-gray-400"></i>
                                        <span class="text-sm text-gray-700">{{ $fileName }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $homeworks->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-check-circle text-4xl mb-4 text-green-300"></i>
                <h4 class="text-xl font-semibold text-gray-500 mb-2">No Homework for Today!</h4>
                <p class="text-gray-400">
                    @if(!$studentDetails)
                        Unable to load homework. Please contact administration.
                    @else
                        Enjoy your day! No homework has been assigned for today.
                    @endif
                </p>
                @if($studentDetails)
                <a href="{{ route('student.school-diary.upcoming') }}" 
                   class="inline-block mt-4 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Check Upcoming Homework
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