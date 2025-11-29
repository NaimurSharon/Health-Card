@extends('layouts.student')

@section('title', $notice->title)
@section('subtitle', 'City Corporation Notice')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">City Corporation Notice</h3>
                    <p class="text-blue-100">Official announcement from City Corporation</p>
                </div>
                <a href="{{ route('student.city-notices') }}" 
                   class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left me-2"></i>Back to Notices
                </a>
            </div>
        </div>
    </div>

    <!-- Notice Content -->
    <div class="content-card rounded-lg p-8 shadow-sm">
        <!-- Notice Header -->
        <div class="text-center mb-8">
            <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-city me-2"></i>City Corporation Notice
            </span>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $notice->title }}</h1>
            
            <div class="flex flex-wrap justify-center items-center gap-4 text-sm text-gray-500 mb-6">
                <span class="flex items-center">
                    <i class="fas fa-calendar me-2"></i>
                    Published: {{ $notice->created_at->format('F j, Y \\a\\t g:i A') }}
                </span>
                @if($notice->expiry_date)
                <span class="flex items-center">
                    <i class="fas fa-clock me-2"></i>
                    Expires: {{ $notice->expiry_date->format('F j, Y') }}
                </span>
                @endif
                <span class="flex items-center">
                    <i class="fas fa-building me-2"></i>
                    By: City Corporation
                </span>
            </div>
        </div>

        <!-- Notice Body -->
        <div class="prose prose-lg max-w-none">
            <div class="bg-blue-50 rounded-lg p-6 mb-6 border border-blue-200">
                <div class="whitespace-pre-line text-gray-700 leading-relaxed">
                    {{ $notice->content }}
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="border-t border-gray-200 pt-6 mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-500">
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2">Notice Information</h5>
                    <p>Status: <span class="font-medium capitalize">{{ $notice->status }}</span></p>
                    <p>Priority: <span class="font-medium capitalize">{{ $notice->priority }}</span></p>
                </div>
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2">Audience</h5>
                    <p>Target: <span class="font-medium">City Residents & Students</span></p>
                    <p>Published: <span class="font-medium">{{ $notice->created_at->diffForHumans() }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <div class="flex flex-wrap gap-4 justify-center">
            <button onclick="window.print()" 
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-print me-2"></i>Print Notice
            </button>
            <button onclick="shareNotice()" 
                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-share me-2"></i>Share Notice
            </button>
            <a href="{{ route('student.city-notices') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-list me-2"></i>View All Notices
            </a>
        </div>
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

.prose {
    color: inherit;
}
.prose p {
    margin-bottom: 1rem;
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