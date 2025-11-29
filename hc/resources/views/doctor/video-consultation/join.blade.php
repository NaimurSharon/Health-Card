@extends('layouts.doctor')

@section('title', 'Video Consultation - ' . $consultation->student->user->name)

@section('content')
<div class="h-screen flex flex-col bg-gray-900">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-3 py-2 sm:px-4 sm:py-3">
        <!-- Same header structure as student -->
    </div>

    <!-- Main Content with React -->
    <div class="flex-1 flex flex-col lg:flex-row">
        <!-- Video Container with React -->
        <div class="flex-1 relative bg-gray-900 order-2 lg:order-1">
            <div id="react-video-call" class="h-full w-full"></div>
        </div>

        <!-- Patient Info Sidebar (Keep as Blade) -->
        <div class="w-full lg:w-80 xl:w-96 bg-white border-b lg:border-b-0 lg:border-l border-gray-200 flex flex-col order-1 lg:order-2">
            <!-- Your existing sidebar content -->
            @include('doctor.video-consultation.partials.sidebar')
        </div>
    </div>
</div>

<?php
    $manifestPath = public_path('build/manifest.json');
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (isset($manifest['resources/js/video-call.jsx'])) {
            $jsFile = $manifest['resources/js/video-call.jsx']['file'];
            $assetPrimary = asset('build/' . $jsFile);
            $assetAlternate = asset('public/build/' . $jsFile);
            echo "<script>
                (function(){
                    var urls = [\"{$assetPrimary}\", \"{$assetAlternate}\"];
                    var i = 0;
                    function loadNext(){
                        if(i>=urls.length){ console.error('video-call bundle not found'); return; }
                        var s = document.createElement('script');
                        s.type = 'module';
                        s.src = urls[i];
                        s.onerror = function(){ i++; loadNext(); };
                        document.head.appendChild(s);
                    }
                    loadNext();
                })();
            </script>";
        } else {
            echo app('Illuminate\Foundation\Vite')(['resources/js/video-call.jsx']);
        }
    } else {
        echo app('Illuminate\\Foundation\\Vite')(['resources/js/video-call.jsx']);
    }
?>

<script>
// Configuration
const streamConfig = @json($streamConfig);
const consultationId = {{ $consultation->id }};
const consultationData = @json($consultation);

let cleanupFunction;

// Save notes function (communicates with Laravel)
async function saveNotes() {
    const notes = document.getElementById('doctor-notes').value;
    try {
        const response = await fetch(`/doctor/video-consultations/${consultationId}/notes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ notes: notes })
        });
        
        if (response.ok) {
            showNotification('Notes saved successfully');
        }
    } catch (error) {
        console.error('Failed to save notes:', error);
    }
}

// End call function
async function endCall() {
    if (!confirm('Are you sure you want to end the consultation?')) {
        return;
    }

    try {
        // Save notes before ending
        await saveNotes();

        // Notify server
        await fetch(`/doctor/video-consultations/${consultationId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                status: 'completed',
                duration: 0
            })
        });

        // Cleanup React
        if (cleanupFunction) {
            cleanupFunction();
        }

        // Redirect
        window.location.href = '{{ route("doctor.video-consultation.show", $consultation->id) }}';

    } catch (error) {
        console.error('Error ending call:', error);
        window.location.href = '{{ route("doctor.video-consultation.show", $consultation->id) }}';
    }
}

// Initialize React
document.addEventListener('DOMContentLoaded', () => {
    console.log('🎬 Initializing React video call for doctor...');
    
    streamConfig.callId = '{{ $consultation->call_id }}';
    
    cleanupFunction = window.initializeVideoCallApp(
        'react-video-call',
        streamConfig,
        consultationData,
        'doctor',
        {
            onCallEnd: endCall
        }
    );
});

// Your existing helper functions (saveNotes, downloadNotes, etc.)
</script>

<style>
/* Your existing CSS + React component styles */
</style>
@endsection