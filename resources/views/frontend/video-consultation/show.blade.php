@extends('layouts.global')

@section('title', 'Consultation Details')

@section('content')
<div class="max-w-md mx-auto lg:max-w-4xl">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('video-consultation.index') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 mr-4 shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Consultation Details</h1>
    </div>

    <!-- Main Card (Matching the "Psychotherapy" card style) -->
    <div class="bg-[#E8DEF8] p-6 rounded-[2rem] mb-6 relative overflow-hidden">
        <!-- Decorative circle -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-purple-200/50 rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <span class="px-4 py-1.5 bg-white/60 backdrop-blur-sm rounded-full text-sm font-semibold text-purple-900">
                    {{ $consultation->status_display }}
                </span>
                <span class="text-purple-900 font-medium">
                    {{ $consultation->scheduled_for->format('M d, Y') }}
                </span>
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mb-2">Video Consultation</h2>
            <p class="text-purple-800 mb-8 opacity-80">
                {{ $consultation->type == 'instant' ? 'Instant Consultation' : 'Scheduled Appointment' }}
            </p>

            <div class="bg-white/40 backdrop-blur-md rounded-2xl p-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold overflow-hidden border-2 border-white">
                        @if($consultation->doctor->profile_photo_url)
                            <img src="{{ $consultation->doctor->profile_photo_url }}" alt="{{ $consultation->doctor->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($consultation->doctor->name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-purple-900 uppercase tracking-wider font-semibold opacity-70">Doctor</p>
                        <p class="text-lg font-bold text-gray-900">Dr. {{ $consultation->doctor->name }}</p>
                    </div>
                </div>
                
                @if($consultation->isAvailable())
                    <a href="{{ route('video-consultation.join', $consultation->id) }}" class="bg-black text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition-colors shadow-lg transform hover:scale-105 transition-transform flex items-center gap-2">
                        <i class="fas fa-video"></i>
                        Join Call
                    </a>
                @elseif($consultation->status == 'completed')
                    <button disabled class="bg-green-100 text-green-700 px-6 py-3 rounded-xl font-bold cursor-not-allowed flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        Completed
                    </button>
                @elseif($consultation->status == 'cancelled')
                    <button disabled class="bg-red-100 text-red-700 px-6 py-3 rounded-xl font-bold cursor-not-allowed flex items-center gap-2">
                        <i class="fas fa-times-circle"></i>
                        Cancelled
                    </button>
                @else
                    <button disabled class="bg-white/50 text-gray-500 px-6 py-3 rounded-xl font-bold cursor-not-allowed flex items-center gap-2">
                        <i class="fas fa-clock"></i>
                        <span id="countdown-text">{{ $consultation->status_display }}</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Time & Duration -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 mb-4">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Time & Duration</h3>
            <p class="text-gray-500 text-sm mb-4">Scheduled timing for the session</p>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-600 text-sm">Start Time</span>
                    <span class="font-semibold text-gray-900">{{ $consultation->scheduled_for->format('h:i A') }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-600 text-sm">Duration</span>
                    <span class="font-semibold text-gray-900">{{ gmdate('H:i', $consultation->duration ?? 15) }} Min</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mb-4">
                <i class="fas fa-wallet"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Payment Details</h3>
            <p class="text-gray-500 text-sm mb-4">Fee and payment status</p>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-600 text-sm">Consultation Fee</span>
                    <span class="font-semibold text-gray-900">৳ {{ number_format($consultation->consultation_fee, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-600 text-sm">Status</span>
                    <span class="font-semibold {{ $consultation->payment_status == 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                        {{ ucfirst($consultation->payment_status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Symptoms / Notes -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Reported Symptoms</h3>
        <p class="text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-2xl">
            {{ $consultation->symptoms }}
        </p>
    </div>

    <!-- Prescription (if completed) -->
    @if($consultation->status === 'completed' && $consultation->prescription)
    <div class="bg-[#C4E7FF] p-6 rounded-3xl mb-8">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-white/50 flex items-center justify-center text-blue-700 mr-3">
                <i class="fas fa-prescription"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Doctor's Prescription</h3>
        </div>
        <div class="bg-white/60 backdrop-blur-sm p-5 rounded-2xl">
            <p class="text-gray-800 whitespace-pre-line">{{ $consultation->prescription }}</p>
            
            @if($consultation->call_metadata && isset($consultation->call_metadata['medication']))
                <div class="mt-4 pt-4 border-t border-blue-200/50">
                    <p class="text-xs font-bold text-blue-800 uppercase mb-1">Medication</p>
                    <p class="text-gray-800">{{ $consultation->call_metadata['medication'] }}</p>
                </div>
            @endif
        </div>
        <div class="mt-4 text-right">
            <button onclick="window.print()" class="text-blue-800 font-bold text-sm hover:underline">
                <i class="fas fa-print mr-1"></i> Print Prescription
            </button>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
// Auto-refresh page every 30 seconds if consultation is scheduled (not yet available)
@if($consultation->status === 'scheduled' && !$consultation->isAvailable())
    let refreshInterval = setInterval(function() {
        // Reload the page to check if consultation is now available
        window.location.reload();
    }, 30000); // 30 seconds

    // Update countdown every second
    let countdownElement = document.getElementById('countdown-text');
    if (countdownElement) {
        let scheduledTime = new Date('{{ $consultation->scheduled_for->toIso8601String() }}');
        
        setInterval(function() {
            let now = new Date();
            let diff = scheduledTime - now;
            
            // If time has passed or within 15 minutes before, reload page
            if (diff <= 15 * 60 * 1000) {
                window.location.reload();
                return;
            }
            
            // Calculate time remaining
            let minutes = Math.floor(diff / 60000);
            let hours = Math.floor(minutes / 60);
            let days = Math.floor(hours / 24);
            
            if (days > 0) {
                countdownElement.textContent = `Starts in ${days} day${days > 1 ? 's' : ''}`;
            } else if (hours > 0) {
                countdownElement.textContent = `Starts in ${hours} hour${hours > 1 ? 's' : ''}`;
            } else if (minutes > 0) {
                countdownElement.textContent = `Starts in ${minutes} minute${minutes > 1 ? 's' : ''}`;
            } else {
                countdownElement.textContent = 'Starting soon...';
            }
        }, 1000);
    }
@endif

// Show notification when consultation is available
@if($consultation->isAvailable() && $consultation->status === 'scheduled')
    // Show browser notification if supported
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('Video Consultation Ready', {
            body: 'Your consultation with Dr. {{ $consultation->doctor->name }} is ready to join!',
            icon: '/images/logo.png',
            tag: 'consultation-{{ $consultation->id }}'
        });
    }
@endif
</script>
@endpush
@endsection
