@extends('layouts.student')

@section('title', $notice->title)
@section('subtitle', 'Notice Details')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">Notice Details</h3>
                    <p class="text-blue-100 text-sm sm:text-base">Important information from school administration</p>
                </div>
                <a href="{{ route('student.school-notices') }}" 
                   class="bg-white text-green-600 px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-gray-100 transition-colors text-sm sm:text-base text-center w-full sm:w-auto">
                    <i class="fas fa-arrow-left me-2"></i>Back to Notices
                </a>
            </div>
        </div>
    </div>

    <!-- Notice Content -->
    <div class="content-card rounded-lg p-4 sm:p-6 lg:p-8 shadow-sm">
        <!-- Notice Header -->
        <div class="text-center mb-6 sm:mb-8">
            @if($notice->priority == 'high')
                <span class="inline-flex items-center px-3 py-1 sm:px-4 sm:py-2 bg-red-100 text-red-800 rounded-full text-xs sm:text-sm font-medium mb-3 sm:mb-4">
                    <i class="fas fa-exclamation-triangle me-1 sm:me-2 text-xs"></i>High Priority Notice
                </span>
            @elseif($notice->priority == 'medium')
                <span class="inline-flex items-center px-3 py-1 sm:px-4 sm:py-2 bg-yellow-100 text-yellow-800 rounded-full text-xs sm:text-sm font-medium mb-3 sm:mb-4">
                    <i class="fas fa-info-circle me-1 sm:me-2 text-xs"></i>Medium Priority Notice
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 sm:px-4 sm:py-2 bg-gray-100 text-gray-800 rounded-full text-xs sm:text-sm font-medium mb-3 sm:mb-4">
                    <i class="fas fa-bell me-1 sm:me-2 text-xs"></i>General Notice
                </span>
            @endif
            
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-3 sm:mb-4">{{ $notice->title }}</h1>
            
            <div class="flex flex-col sm:flex-row sm:flex-wrap justify-center items-center gap-2 sm:gap-3 lg:gap-4 text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
                <span class="flex items-center">
                    <i class="fas fa-calendar me-1 sm:me-2 text-xs"></i>
                    Published: {{ $notice->created_at->format('M j, Y \\a\\t g:i A') }}
                </span>
                @if($notice->expiry_date)
                <span class="flex items-center">
                    <i class="fas fa-clock me-1 sm:me-2 text-xs"></i>
                    Expires: {{ $notice->expiry_date->format('M j, Y') }}
                </span>
                @endif
                <span class="flex items-center">
                    <i class="fas fa-user me-1 sm:me-2 text-xs"></i>
                    By: {{ $notice->publishedBy->name ?? 'School Administration' }}
                </span>
            </div>
        </div>

        <!-- Notice Body -->
        <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none">
            <div class="bg-gray-50 rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
                <div class="whitespace-pre-line text-gray-700 leading-relaxed text-sm sm:text-base">
                    {{ $notice->content }}
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="border-t border-gray-200 pt-4 sm:pt-6 mt-6 sm:mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 text-xs sm:text-sm text-gray-500">
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">Notice Information</h5>
                    <p>Status: <span class="font-medium capitalize">{{ $notice->status }}</span></p>
                    <p>Priority: <span class="font-medium capitalize">{{ $notice->priority }}</span></p>
                </div>
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">Audience</h5>
                    <p>Target: <span class="font-medium">Students</span></p>
                    <p>Published: <span class="font-medium">{{ $notice->created_at->diffForHumans() }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 justify-center">
            <button onclick="window.print()" 
                    class="bg-blue-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-print me-2"></i>Print Notice
            </button>
            <button onclick="shareNotice()" 
                    class="bg-green-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg hover:bg-green-700 transition-colors text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-share me-2"></i>Share Notice
            </button>
            <a href="{{ route('student.school-notices') }}" 
               class="bg-gray-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg hover:bg-gray-700 transition-colors text-sm sm:text-base w-full sm:w-auto text-center">
                <i class="fas fa-list me-2"></i>View All Notices
            </a>
        </div>
    </div>

    <!-- Related Notices -->
    @if($relatedNotices->count() > 0)
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <h4 class="text-lg sm:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Related Notices</h4>
        
        <div class="space-y-2 sm:space-y-3">
            @foreach($relatedNotices as $related)
            <a href="{{ route('student.school-notices.show', $related->id) }}" 
               class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xs sm:text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors text-sm sm:text-base truncate">
                            {{ Str::limit($related->title, 50) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $related->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors text-xs sm:text-sm ml-2"></i>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.content-card {
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    background: #fff;
}

.table-header {
    background: #06AC73;
    border-bottom: 1px solid rgba(229, 231, 235, 0.6);
}

.prose {
    color: inherit;
}
.prose p {
    margin-bottom: 1rem;
}

@media (max-width: 640px) {
    .prose {
        font-size: 0.875rem;
        line-height: 1.5;
    }
}
</style>

<script>
function shareNotice() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $notice->title }}',
            text: '{{ Str::limit($notice->content, 100) }}',
            url: window.location.href
        })
        .then(() => console.log('Notice shared successfully'))
        .catch((error) => console.log('Error sharing notice:', error));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Notice link copied to clipboard!');
        });
    }
}
</script>
@endsection