@extends('layouts.student')

@section('title', 'School Notices')
@section('subtitle', 'Stay updated with important announcements and information')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">School Notices</h3>
                    <p class="text-blue-100 text-sm sm:text-base">Stay updated with important announcements and information</p>
                </div>
                <div class="text-center sm:text-right text-white">
                    <p class="text-xs sm:text-sm">{{ $notices->total() }} notices found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-blue-600">Total Notices</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $notices->total() }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-blue-600 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-orange-600">High Priority</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $highPriorityCount }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-green-600">This Week</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $thisWeekCount }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-week text-green-600 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notices List -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <h4 class="text-lg sm:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">All Notices</h4>
        
        @if($notices->count() > 0)
            <div class="space-y-4 sm:space-y-6">
                @foreach($notices as $notice)
                <div class="p-4 sm:p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3 sm:mb-4 space-y-2 sm:space-y-0">
                        <div class="flex-1">
                            <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">{{ $notice->title }}</h5>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0 text-xs sm:text-sm text-gray-500 mb-2 sm:mb-3">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar me-2 text-xs"></i>
                                    {{ $notice->created_at->format('M j, Y \\a\\t g:i A') }}
                                </span>
                                @if($notice->expiry_date)
                                <span class="flex items-center">
                                    <i class="fas fa-clock me-2 text-xs"></i>
                                    Expires: {{ $notice->expiry_date->format('M j, Y') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <span class="px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium 
                            {{ $notice->priority == 'high' ? 'bg-red-100 text-red-800' : 
                               ($notice->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($notice->priority) }}
                        </span>
                    </div>

                    <p class="text-gray-600 text-sm sm:text-base mb-3 sm:mb-4 line-clamp-3">{{ Str::limit($notice->content, 200) }}</p>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-3 sm:pt-4 border-t border-gray-200 space-y-2 sm:space-y-0">
                        <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-500">
                            <i class="fas fa-user text-xs"></i>
                            <span>Published by: {{ $notice->publishedBy->name ?? 'Administration' }}</span>
                        </div>
                        <a href="{{ route('student.school-notices.show', $notice->id) }}" 
                           class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition-colors text-xs sm:text-sm text-center">
                            Read Full Notice
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4 sm:mt-6">
                {{ $notices->links() }}
            </div>
        @else
            <div class="text-center py-8 sm:py-12">
                <i class="fas fa-bell-slash text-4xl sm:text-5xl mb-3 sm:mb-4 text-gray-300"></i>
                <h4 class="text-lg sm:text-xl font-semibold text-gray-500 mb-2">No Notices Available</h4>
                <p class="text-gray-400 text-sm sm:text-base max-w-md mx-auto">There are no school notices at the moment. Check back later for updates.</p>
            </div>
        @endif
    </div>
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

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Responsive pagination styles */
.pagination {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
}

.pagination .page-item .page-link {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

@media (max-width: 640px) {
    .pagination .page-item .page-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
        min-width: 2.5rem;
    }
    
    .pagination .page-item:not(.active):not(.disabled) .page-link {
        border: 1px solid #e5e7eb;
    }
}
</style>

<script>
// Add touch-friendly improvements for mobile
document.addEventListener('DOMContentLoaded', function() {
    // Improve touch targets for mobile
    const noticeCards = document.querySelectorAll('.bg-white.border');
    noticeCards.forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on the "Read Full Notice" button
            if (!e.target.closest('a')) {
                const link = this.querySelector('a');
                if (link) {
                    window.location.href = link.href;
                }
            }
        });
    });
});
</script>
@endsection