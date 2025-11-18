@extends('layouts.student')

@section('title', 'School Diary - Homework')
@section('subtitle', 'View your class homework and assignments')

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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">School Diary</h3>
                    <p class="text-gray-100 text-sm">
                        @if($studentDetails && $studentDetails->class && $studentDetails->section)
                            Class: {{ $studentDetails->class->name }}, Section: {{ $studentDetails->section->name }}
                        @else
                            Class information not available
                        @endif
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <!-- Print & Download Buttons -->
                    @if($todaysHomeworks->count() > 0)
                    <div class="flex items-center justify-center sm:justify-start gap-2 w-full sm:w-auto">
                        <a href="{{ route('student.school-diary.print', ['date' => $filterDate]) }}" 
                           target="_blank"
                           class="bg-white text-blue-600 px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium flex items-center text-sm sm:text-base w-full sm:w-auto justify-center">
                            <i class="fas fa-print me-2 text-xs sm:text-sm"></i>Print
                        </a>
                        <a href="{{ route('student.school-diary.download-pdf', ['date' => $filterDate]) }}" 
                           class="bg-green-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center text-sm sm:text-base w-full sm:w-auto justify-center">
                            <i class="fas fa-download me-2 text-xs sm:text-sm"></i>Download
                        </a>
                    </div>
                    @endif
                    
                    <!-- Date Filter -->
                    <form method="GET" action="{{ route('student.school-diary') }}" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                        <label for="date" class="text-white text-sm font-medium hidden sm:block">View Date:</label>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <input type="date" 
                                   id="date" 
                                   name="date" 
                                   style='color:#000;'
                                   value="{{ $filterDate }}"
                                   class="px-3 py-2 rounded-lg text-dark border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-auto text-sm">
                            <button type="submit" 
                                    class="bg-white text-blue-600 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm sm:text-base w-16 sm:w-auto">
                                Go
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="flex justify-center sm:justify-end">
                    <a href="{{ route('student.school-diary.upcoming') }}" 
                       class="bg-white text-orange-600 px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium text-sm sm:text-base w-full sm:w-auto text-center">
                        Upcoming
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Main Content - Homework Table -->
        <div class="lg:col-span-3">
            <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 sm:mb-6">
                    <h4 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center justify-center sm:justify-start">
                        <i class="fas fa-calendar-day text-blue-600 me-2"></i>
                        @if($filterDate == today()->format('Y-m-d'))
                            Today's Homework
                        @else
                            <span class="text-center sm:text-left">
                                Homework for {{ \Carbon\Carbon::parse($filterDate)->format('F j, Y') }}
                            </span>
                        @endif
                    </h4>
                    <div class="flex items-center justify-center sm:justify-end gap-3">
                        <span class="text-xs sm:text-sm text-gray-600 bg-blue-100 px-2 py-1 sm:px-3 sm:py-1 rounded-full">
                            {{ $todaysHomeworks->count() }} assignments
                        </span>
                        @if($todaysHomeworks->count() > 0)
                        <div class="flex items-center gap-1 sm:gap-2 print:hidden">
                            <a href="{{ route('student.school-diary.download-pdf', ['date' => $filterDate]) }}" 
                               class="text-gray-600 hover:text-gray-800 p-1 sm:p-2 rounded-lg hover:bg-gray-100 transition-colors"
                               title="Download PDF">
                                <i class="fas fa-download text-sm sm:text-base"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($todaysHomeworks->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden sm:block overflow-x-auto -mx-4 sm:mx-0">
                        <div class="min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subject
                                            </th>
                                            <th class="px-3 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Homework
                                            </th>
                                            <th class="px-3 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                                Teacher
                                            </th>
                                            <th class="px-3 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($todaysHomeworks as $homework)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-3 sm:px-4 sm:py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-book text-white text-xs sm:text-sm"></i>
                                                    </div>
                                                    <div class="ml-2 sm:ml-3 min-w-0">
                                                        <div class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $homework->subject->name ?? 'General' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-3 py-3 sm:px-4 sm:py-4">
                                                <div class="max-w-xs lg:max-w-md">
                                                    <div class="text-sm font-medium text-gray-900 mb-1">
                                                        {{ $homework->homework_title }}
                                                    </div>
                                                    <div class="text-sm text-gray-600 line-clamp-2 hidden lg:block">
                                                        {{ $homework->homework_description }}
                                                    </div>
                                                    @if($homework->due_date)
                                                    <div class="flex items-center mt-1 text-xs text-orange-600">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Due: {{ $homework->due_date->format('M j, Y') }}
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                            
                                            <td class="px-3 py-3 sm:px-4 sm:py-4 hidden md:table-cell">
                                                <div class="flex items-center">
                                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-user text-green-600 text-xs"></i>
                                                    </div>
                                                    <div class="ml-2 sm:ml-3">
                                                        <div class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $homework->teacher->name ?? 'Teacher' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-3 py-3 sm:px-4 sm:py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center gap-1 sm:gap-2">
                                                    <a href="{{ route('student.school-diary.show', $homework->id) }}" 
                                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 px-2 py-1 sm:px-3 sm:py-1 rounded-lg transition-colors text-xs sm:text-sm flex items-center">
                                                        <i class="fas fa-eye me-1 text-xs"></i>
                                                        <span class="hidden xs:inline">View</span>
                                                    </a>
                                                    @if($homework->attachments && count($homework->attachments) > 0)
                                                    <span class="text-green-600 bg-green-50 px-2 py-1 rounded-lg text-xs flex items-center">
                                                        <i class="fas fa-paperclip me-1"></i>
                                                        <span class="hidden sm:inline">{{ count($homework->attachments) }}</span>
                                                        <span class="sm:hidden">{{ count($homework->attachments) }}</span>
                                                    </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Cards View -->
                    <div class="sm:hidden space-y-3">
                        @foreach($todaysHomeworks as $homework)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-book text-white text-sm"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $homework->subject->name ?? 'General' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $homework->teacher->name ?? 'Teacher' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    @if($homework->attachments && count($homework->attachments) > 0)
                                    <span class="text-green-600 bg-green-50 px-2 py-1 rounded text-xs">
                                        <i class="fas fa-paperclip"></i> {{ count($homework->attachments) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="text-sm font-medium text-gray-900 mb-1">
                                    {{ $homework->homework_title }}
                                </div>
                                <div class="text-sm text-gray-600 line-clamp-2">
                                    {{ $homework->homework_description }}
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                @if($homework->due_date)
                                <div class="text-xs text-orange-600">
                                    <i class="fas fa-clock me-1"></i>
                                    Due: {{ $homework->due_date->format('M j, Y') }}
                                </div>
                                @else
                                <div class="text-xs text-gray-500">
                                    No due date
                                </div>
                                @endif
                                
                                <a href="{{ route('student.school-diary.show', $homework->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                    View Details
                                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 sm:py-12">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <i class="fas fa-book-open text-gray-400 text-xl sm:text-2xl"></i>
                        </div>
                        <h4 class="text-lg sm:text-xl font-semibold text-gray-500 mb-2">No Homework Found</h4>
                        <p class="text-gray-400 text-sm sm:text-base mb-4 px-4">
                            @if($filterDate == today()->format('Y-m-d'))
                                No homework has been assigned for today.
                            @else
                                No homework found for the selected date.
                            @endif
                        </p>
                        @if($filterDate != today()->format('Y-m-d'))
                        <a href="{{ route('student.school-diary') }}" 
                           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
                            <i class="fas fa-calendar-day me-2"></i>
                            View Today's Homework
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar - Recent Homework -->
        <div class="lg:col-span-1">
            <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
                <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-history text-gray-600 me-2 text-sm sm:text-base"></i>
                    Recent Homework
                </h4>
                
                @if($recentHomeworks->count() > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($recentHomeworks->take(5) as $date => $homeworks)
                        <div class="border-l-4 border-blue-500 pl-3 sm:pl-4">
                            <div class="flex items-center justify-between mb-1 sm:mb-2">
                                <h5 class="text-xs sm:text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($date)->format('M j') }}
                                </h5>
                                <span class="text-xs text-gray-500 bg-gray-100 px-1 sm:px-2 py-0.5 sm:py-1 rounded">
                                    {{ $homeworks->count() }}
                                </span>
                            </div>
                            <div class="space-y-1 sm:space-y-2">
                                @foreach($homeworks->take(2) as $homework)
                                <a href="{{ route('student.school-diary.show', $homework->id) }}" 
                                   class="block text-xs sm:text-sm text-gray-600 hover:text-blue-600 transition-colors line-clamp-1">
                                    <i class="fas fa-book text-gray-400 me-1 text-xs"></i>
                                    {{ $homework->subject->name ?? 'General' }}: {{ Str::limit($homework->homework_title, 25) }}
                                </a>
                                @endforeach
                                @if($homeworks->count() > 2)
                                <div class="text-xs text-blue-600">
                                    +{{ $homeworks->count() - 2 }} more
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Quick Date Navigation -->
                    @if(isset($availableDates) && $availableDates->count() > 0)
                    <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-gray-200">
                        <h5 class="text-xs sm:text-sm font-medium text-gray-900 mb-2 sm:mb-3">Other Dates</h5>
                        <div class="space-y-1 sm:space-y-2 max-h-32 sm:max-h-40 overflow-y-auto">
                            @foreach($availableDates->take(8) as $date)
                                @if($date != $filterDate)
                                <a href="{{ route('student.school-diary', ['date' => $date]) }}" 
                                   class="block text-xs sm:text-sm text-gray-600 hover:text-blue-600 transition-colors py-1">
                                    <i class="fas fa-calendar me-2 text-xs"></i>
                                    {{ \Carbon\Carbon::parse($date)->format('M j, Y') }}
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-6 sm:py-8">
                        <i class="fas fa-history text-2xl sm:text-3xl mb-2 sm:mb-3 text-gray-300"></i>
                        <p class="text-gray-400 text-xs sm:text-sm">No recent homework found</p>
                    </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm mt-4 sm:mt-6">
                <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">This Week</h4>
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm text-gray-600">Total Assignments</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900">
                            {{ $recentHomeworks->flatten()->count() }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm text-gray-600">Subjects</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900">
                            {{ $recentHomeworks->flatten()->pluck('subject_id')->unique()->count() }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm text-gray-600">Teachers</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900">
                            {{ $recentHomeworks->flatten()->pluck('teacher_id')->unique()->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
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

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Print Styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .content-card {
        box-shadow: none !important;
        border: 1px solid #000 !important;
        background: white !important;
    }
}
</style>

<script>
// Enhanced mobile functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle touch events for better mobile interaction
    const homeworkCards = document.querySelectorAll('.hover\\:bg-gray-50');
    
    homeworkCards.forEach(card => {
        card.addEventListener('touchstart', function() {
            this.style.backgroundColor = '#f9fafb';
        });
        
        card.addEventListener('touchend', function() {
            setTimeout(() => {
                this.style.backgroundColor = '';
            }, 150);
        });
    });
    
    // Improve date input on mobile
    const dateInput = document.getElementById('date');
    if (dateInput) {
        dateInput.addEventListener('focus', function() {
            if (window.innerWidth < 640) {
                this.type = 'date';
            }
        });
    }
});
</script>
@endsection