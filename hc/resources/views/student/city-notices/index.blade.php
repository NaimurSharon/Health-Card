@extends('layouts.student')

@section('title', 'City Notices')
@section('subtitle', 'Important announcements from City Corporation')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">City Corporation Notices</h3>
                    <p class="text-blue-100">Important announcements and updates from the City Corporation</p>
                </div>
                <div class="text-right text-white">
                    <p class="text-sm">{{ $cityNotices->total() }} notices found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Total Notices</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $cityNotices->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-city text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600">Active Notices</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $cityNotices->where('expiry_date', '>=', now())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">This Month</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $cityNotices->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notices List -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">All City Notices</h4>
        
        @if($cityNotices->count() > 0)
            <div class="space-y-4">
                @foreach($cityNotices as $notice)
                <div class="p-6 bg-white border border-blue-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ $notice->title }}</h5>
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ $notice->created_at->format('M j, Y \\a\\t g:i A') }}
                                </span>
                                @if($notice->expiry_date)
                                <span class="flex items-center">
                                    <i class="fas fa-clock me-2"></i>
                                    Expires: {{ $notice->expiry_date->format('M j, Y') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            City Notice
                        </span>
                    </div>

                    <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($notice->content, 200) }}</p>

                    <div class="flex items-center justify-between pt-4 border-t border-blue-200">
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <i class="fas fa-building"></i>
                            <span>Published by: City Corporation</span>
                        </div>
                        <a href="{{ route('student.city-notices.show', $notice->id) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            Read Full Notice
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $cityNotices->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-city text-5xl mb-4 text-gray-300"></i>
                <h4 class="text-xl font-semibold text-gray-500 mb-2">No City Notices Available</h4>
                <p class="text-gray-400">There are no city notices at the moment. Check back later for updates.</p>
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
</style>
@endsection