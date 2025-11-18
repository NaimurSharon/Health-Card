@extends('layouts.student')

@section('title', 'Video Consultations')
@section('subtitle', 'Schedule and join video calls with doctors')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">Video Consultations</h3>
                    <p class="text-blue-100">Schedule and join video calls with doctors</p>
                </div>
                <a href="{{ route('student.video-consultation.create') }}" 
                   class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                    <i class="fas fa-video me-2"></i>New Consultation
                </a>
            </div>
        </div>
    </div>

    <!-- Upcoming Consultations -->
    @if($upcomingConsultations->count() > 0)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-clock text-orange-600 me-2"></i>Upcoming Consultations
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingConsultations as $consultation)
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h5 class="font-semibold text-gray-900">Dr. {{ $consultation->doctor->name }}</h5>
                        <p class="text-sm text-gray-600">{{ $consultation->scheduled_for->format('M j, Y \\a\\t g:i A') }}</p>
                    </div>
                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium capitalize">
                        {{ $consultation->type }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($consultation->symptoms, 80) }}</p>
                <div class="flex space-x-2">
                    @if($consultation->canStartCall())
                    <a href="{{ route('student.video-consultation.join', $consultation->id) }}" 
                       class="flex-1 bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                        Join Call
                    </a>
                    @endif
                    <a href="{{ route('student.video-consultation.show', $consultation->id) }}" 
                       class="flex-1 bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- All Consultations -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-history text-gray-600 me-2"></i>Consultation History
        </h4>
        
        @if($consultations->count() > 0)
            <div class="space-y-4">
                @foreach($consultations as $consultation)
                <div class="p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-3 gap-3">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h5 class="font-semibold text-gray-900">Dr. {{ $consultation->doctor->name }}</h5>
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $consultation->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($consultation->status == 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                       ($consultation->status == 'scheduled' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($consultation->status) }}
                                </span>
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium capitalize">
                                    {{ $consultation->type }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ Str::limit($consultation->symptoms, 100) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">
                                @if($consultation->scheduled_for)
                                    {{ $consultation->scheduled_for->format('M j, Y \\a\\t g:i A') }}
                                @else
                                    Not scheduled
                                @endif
                            </p>
                            @if($consultation->duration)
                                <p class="text-xs text-gray-500">Duration: {{ gmdate('H:i:s', $consultation->duration) }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                ৳ {{ number_format($consultation->consultation_fee, 2) }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-wallet me-1"></i>
                                {{ ucfirst($consultation->payment_status) }}
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('student.video-consultation.show', $consultation->id) }}" 
                               class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                View Details
                            </a>
                            @if($consultation->isActive())
                            <a href="{{ route('student.consultations.video-call', $consultation->id) }}" 
                               class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                Join Call now!!
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $consultations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-video-slash text-4xl mb-4 text-gray-300"></i>
                <h4 class="text-xl font-semibold text-gray-500 mb-2">No Video Consultations</h4>
                <p class="text-gray-400">You haven't scheduled any video consultations yet.</p>
                <a href="{{ route('student.video-consultation.create') }}" 
                   class="inline-block mt-4 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Schedule Your First Consultation
                </a>
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection