@extends('layouts.student')

@section('title', 'Video Consultation - Dr. ' . $consultation->doctor->name)

@section('content')
<div class="h-screen flex flex-col bg-gray-900">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-3 py-2 sm:px-4 sm:py-3">
        <!-- Same header as above -->
    </div>

    <!-- Video Container -->
    <div class="flex-1 bg-gray-900 relative">
        <!-- Loading State -->
        <div id="loading" class="absolute inset-0 flex items-center justify-center bg-gray-900 z-20">
            <div class="text-center text-white">
                <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-lg font-semibold">Joining Video Call</p>
                <p class="text-gray-400 mt-2">Connecting to Dr. {{ $consultation->doctor->name }}...</p>
            </div>
        </div>

        <!-- Error State -->
        <div id="error-state" class="absolute inset-0 flex items-center justify-center bg-gray-900 z-30 hidden">
            <div class="text-center text-white max-w-sm">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2" id="error-title">Connection Failed</h3>
                <p class="text-gray-300 mb-4" id="error-message"></p>
                <button onclick="retryConnection()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-redo mr-2"></i>Retry Connection
                </button>
            </div>
        </div>

        <!-- Video Elements -->
        <div id="video-container" class="h-full w-full relative hidden">
            <!-- Remote Video -->
            <div id="remote-video-container" class="absolute inset-0 bg-gray-800">
                <video id="remote-video" autoplay playsinline class="w-full h-full object-cover"></video>
            </div>
            
            <!-- Local Video -->
            <div id="local-video-container" class="absolute bottom-4 right-4 w-48 h-64 bg-black rounded-lg overflow-hidden border-2 border-white shadow-2xl">
                <video id="local-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                <div class="absolute bottom-2 left-2 bg-black bg-opacity-60 text-white px-2 py-1 rounded text-xs">
                    You
                </div>
            </div>

            <!-- Controls -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-4 z-20">
                <button id="toggle-video" class="bg-gray-700 hover:bg-gray-600 text-white w-12 h-12 rounded-full transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-video"></i>
                </button>
                <button id="toggle-audio" class="bg-gray-700 hover:bg-gray-600 text-white w-12 h-12 rounded-full transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-microphone"></i>
                </button>
                <button onclick="endCall()" class="bg-red-600 hover:bg-red-700 text-white w-12 h-12 rounded-full transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-phone-slash"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stream Video SDK -->
<script src="https://unpkg.com/@stream-io/video-client@1.37.0/dist/index.min.js"></script>

<script>
// Configuration
const streamConfig = @json($streamConfig);
const consultationId = {{ $consultation->id }};
const callId = '{{ $consultation->call_id }}';

let client;
let call;
let localStream;

async function initializeCall() {
    try {
        console.log('🚀 Initializing video call...');

        // Initialize Stream Video Client
        client = new StreamVideoClient(
            streamConfig.apiKey,
            {
                user: streamConfig.user,
                token: streamConfig.token,
            },
            streamConfig.options
        );

        // Create call instance
        call = client.call('default', callId);
        
        // Set up event listeners
        call.on('call.session_participant_joined', (event) => {
            console.log('Participant joined:', event);
            // Handle remote participant
        });

        call.on('call.session_participant_left', (event) => {
            console.log('Participant left:', event);
        });

        // Join the call
        await call.join({ create: true });
        console.log('✅ Successfully joined call');

        // Enable camera and microphone
        await initializeMediaDevices();
        
        // Show video interface
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('video-container').classList.remove('hidden');

    } catch (error) {
        console.error('❌ Failed to initialize call:', error);
        showError('Connection Failed', error.message);
    }
}

async function initializeMediaDevices() {
    try {
        // Get user media
        const stream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        });
        
        localStream = stream;
        
        // Display local video
        const localVideo = document.getElementById('local-video');
        localVideo.srcObject = stream;
        
        // Enable camera and microphone in call
        await call.camera.enable();
        await call.microphone.enable();

        // Set up device controls
        document.getElementById('toggle-video').addEventListener('click', async () => {
            await call.camera.toggle();
        });

        document.getElementById('toggle-audio').addEventListener('click', async () => {
            await call.microphone.toggle();
        });

    } catch (error) {
        console.error('Failed to initialize media devices:', error);
        throw error;
    }
}

function showError(title, message) {
    document.getElementById('error-title').textContent = title;
    document.getElementById('error-message').textContent = message;
    document.getElementById('loading').classList.add('hidden');
    document.getElementById('error-state').classList.remove('hidden');
}

function retryConnection() {
    document.getElementById('error-state').classList.add('hidden');
    document.getElementById('loading').classList.remove('hidden');
    initializeCall();
}

async function endCall() {
    if (!confirm('Are you sure you want to end the consultation?')) {
        return;
    }

    try {
        // Leave call
        if (call) {
            await call.leave();
        }

        // Stop media streams
        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
        }

        // Notify server
        await fetch(`/student/video-consultations/${consultationId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                status: 'completed'
            })
        });

        // Redirect
        window.location.href = '{{ route("student.video-consultation.index") }}';

    } catch (error) {
        console.error('Error ending call:', error);
        window.location.href = '{{ route("student.video-consultation.index") }}';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initializeCall();
});
</script>

<style>
/* Your existing CSS */
</style>
@endsection