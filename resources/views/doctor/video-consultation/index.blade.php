@extends('layouts.doctor')

@section('title', 'Video Consultations')
@section('subtitle', 'Manage your video consultations with patients')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">Video Consultations</h3>
                    <p class="text-blue-100">Manage your video consultations with patients</p>
                </div>
                <div class="text-white text-right">
                    <p class="text-lg font-semibold">{{ $todayConsultations->count() }} Today</p>
                    <p class="text-blue-100">{{ $ongoingConsultations->count() }} Ongoing</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ongoing Consultations -->
    @if($ongoingConsultations->count() > 0)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-play-circle text-green-600 me-2"></i>Ongoing Consultations
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($ongoingConsultations as $consultation)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h5 class="font-semibold text-gray-900">{{ $consultation->student->user->name }}</h5>
                        <p class="text-sm text-gray-600">Started: {{ $consultation->started_at->format('g:i A') }}</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                        Ongoing
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($consultation->symptoms, 80) }}</p>
                <div class="flex space-x-2">
                    <a href="{{ route('doctor.consultations.video-call', $consultation->id) }}" 
                       class="flex-1 bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                        <i class="fas fa-video me-1"></i>Join Call
                    </a>
                    <a href="{{ route('doctor.video-consultation.show', $consultation->id) }}" 
                       class="flex-1 bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <i class="fas fa-info me-1"></i>Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Upcoming Consultations -->
    @if($upcomingConsultations->count() > 0)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-clock text-orange-600 me-2"></i>Upcoming Consultations
        </h4>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scheduled Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Symptoms</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($upcomingConsultations as $consultation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $consultation->student->user->name }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $consultation->student->student_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $consultation->scheduled_for->format('M j, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $consultation->scheduled_for->format('g:i A') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($consultation->symptoms, 60) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $consultation->type == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($consultation->type == 'instant' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ ucfirst($consultation->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                @if($consultation->canStartCall())
                                <a href="{{ route('doctor.video-consultation.join', $consultation->id) }}" 
                                   class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                    Join
                                </a>
                                @endif
                                <a href="{{ route('doctor.video-consultation.show', $consultation->id) }}" 
                                   class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- All Consultations -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-history text-gray-600 me-2"></i>All Consultations
            </h4>
            <span class="text-sm text-gray-600">{{ $consultations->total() }} total</span>
        </div>
        
        @if($consultations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($consultations as $consultation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $consultation->student->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $consultation->student->class->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($consultation->scheduled_for)
                                <p class="text-sm font-medium text-gray-900">{{ $consultation->scheduled_for->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $consultation->scheduled_for->format('g:i A') }}</p>
                                @else
                                <p class="text-sm text-gray-500">Not scheduled</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($consultation->duration)
                                <p class="text-sm text-gray-900">{{ gmdate('H:i:s', $consultation->duration) }}</p>
                                @else
                                <p class="text-sm text-gray-500">-</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $consultation->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($consultation->status == 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                       ($consultation->status == 'scheduled' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($consultation->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">৳ {{ number_format($consultation->consultation_fee, 2) }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ $consultation->payment_status }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('doctor.video-consultation.show', $consultation->id) }}" 
                                       class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                        View
                                    </a>
                                    @if($consultation->isActive())
                                    <a href="{{ route('doctor.video-consultation.join', $consultation->id) }}" 
                                       class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                        Join
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $consultations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-video-slash text-4xl mb-4 text-gray-300"></i>
                <h4 class="text-xl font-semibold text-gray-500 mb-2">No Video Consultations</h4>
                <p class="text-gray-400">You don't have any video consultations scheduled yet.</p>
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