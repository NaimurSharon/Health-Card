@extends('layouts.student')

@section('title', 'Consultation Details - Dr. ' . $consultation->doctor->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">Consultation Details</h3>
                    <p class="text-blue-100">Video consultation with Dr. {{ $consultation->doctor->name }}</p>
                </div>
                <div class="flex space-x-3">
                    @if($consultation->isActive())
                    <a href="{{ route('student.video-consultation.join', $consultation->id) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-video me-2"></i>Join Call
                    </a>
                    @endif
                    <a href="{{ route('student.video-consultation.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Doctor Information -->
        <div class="content-card rounded-lg p-6 shadow-sm lg:col-span-1">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Doctor Information</h4>
            
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900">Dr. {{ $consultation->doctor->name }}</h5>
                        <p class="text-sm text-gray-600">{{ $consultation->doctor->specialization ?? 'General Physician' }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-gray-400 me-3 w-5"></i>
                        <span class="text-gray-600">{{ $consultation->doctor->qualifications ?? 'Medical Doctor' }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-400 me-3 w-5"></i>
                        <span class="text-gray-600">{{ $consultation->doctor->email }}</span>
                    </div>
                    @if($consultation->doctor->phone)
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-400 me-3 w-5"></i>
                        <span class="text-gray-600">{{ $consultation->doctor->phone }}</span>
                    </div>
                    @endif
                </div>

                @if($consultation->doctor->hospital)
                <div class="pt-4 border-t border-gray-200">
                    <h6 class="font-semibold text-gray-900 mb-2">Hospital</h6>
                    <p class="text-sm text-gray-600">{{ $consultation->doctor->hospital->name }}</p>
                    <p class="text-xs text-gray-500">{{ $consultation->doctor->hospital->type ?? 'Medical Center' }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Consultation Details -->
        <div class="content-card rounded-lg p-6 shadow-sm lg:col-span-2">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Consultation Details</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h5 class="font-semibold text-gray-900 mb-3">Call Information</h5>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Call ID</label>
                            <p class="font-mono text-gray-900 bg-gray-100 px-3 py-2 rounded">{{ $consultation->call_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                {{ $consultation->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($consultation->status == 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                   ($consultation->status == 'scheduled' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($consultation->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $consultation->type == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($consultation->type == 'instant' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ ucfirst($consultation->type) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <h5 class="font-semibold text-gray-900 mb-3">Timing & Payment</h5>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled For</label>
                            <p class="text-gray-900">{{ $consultation->scheduled_for->format('l, F j, Y \\a\\t g:i A') }}</p>
                        </div>
                        @if($consultation->started_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Started At</label>
                            <p class="text-gray-900">{{ $consultation->started_at->format('M j, Y g:i A') }}</p>
                        </div>
                        @endif
                        @if($consultation->ended_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ended At</label>
                            <p class="text-gray-900">{{ $consultation->ended_at->format('M j, Y g:i A') }}</p>
                        </div>
                        @endif
                        @if($consultation->duration)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                            <p class="text-gray-900">{{ gmdate('H:i:s', $consultation->duration) }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Fee</label>
                            <p class="text-lg font-semibold text-green-600">৳ {{ number_format($consultation->consultation_fee, 2) }}</p>
                            <p class="text-sm text-gray-500 capitalize">Payment Status: {{ $consultation->payment_status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Symptoms -->
            <div class="mb-6">
                <h5 class="font-semibold text-gray-900 mb-3">Reported Symptoms</h5>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-gray-700">{{ $consultation->symptoms }}</p>
                </div>
            </div>

            <!-- Call Actions -->
            @if($consultation->isActive())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="font-semibold text-blue-900 mb-1">Call is Active</h5>
                        <p class="text-blue-700 text-sm">You can join the video call with Dr. {{ $consultation->doctor->name }}</p>
                    </div>
                    <a href="{{ route('student.video-consultation.join', $consultation->id) }}" 
                       class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-video me-2"></i>Join Video Call
                    </a>
                </div>
            </div>
            @endif

            <!-- Prescription Section -->
            @if($consultation->status === 'completed' && $consultation->prescription)
            <div class="border-t border-gray-200 pt-6">
                <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-file-medical text-green-600 me-2"></i>Doctor's Prescription
                </h5>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="prose prose-sm max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $consultation->prescription }}</p>
                    </div>
                    @if($consultation->doctor_notes)
                    <div class="mt-4 pt-4 border-t border-green-200">
                        <h6 class="font-semibold text-gray-900 mb-2">Doctor's Notes</h6>
                        <p class="text-sm text-gray-600">{{ $consultation->doctor_notes }}</p>
                    </div>
                    @endif
                    @if($consultation->call_metadata && isset($consultation->call_metadata['medication']))
                    <div class="mt-4 pt-4 border-t border-green-200">
                        <h6 class="font-semibold text-gray-900 mb-2">Recommended Medication</h6>
                        <p class="text-sm text-gray-600">{{ $consultation->call_metadata['medication'] }}</p>
                    </div>
                    @endif
                    @if($consultation->call_metadata && isset($consultation->call_metadata['follow_up_date']))
                    <div class="mt-4 pt-4 border-t border-green-200">
                        <h6 class="font-semibold text-gray-900 mb-2">Follow-up Date</h6>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($consultation->call_metadata['follow_up_date'])->format('l, F j, Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @elseif($consultation->status === 'completed')
            <div class="border-t border-gray-200 pt-6">
                <h5 class="font-semibold text-gray-900 mb-3">Prescription</h5>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                    <i class="fas fa-file-medical-alt text-yellow-500 text-2xl mb-2"></i>
                    <p class="text-yellow-700">Prescription will be available after the doctor updates it.</p>
                </div>
            </div>
            @endif

            <!-- Call Recording (if available) -->
            @if($consultation->status === 'completed' && $consultation->call_metadata && isset($consultation->call_metadata['recording_url']))
            <div class="border-t border-gray-200 pt-6">
                <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-record-vinyl text-purple-600 me-2"></i>Call Recording
                </h5>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <p class="text-purple-700 mb-3">This consultation was recorded for medical records.</p>
                    <a href="{{ $consultation->call_metadata['recording_url'] }}" 
                       target="_blank"
                       class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center">
                        <i class="fas fa-play me-2"></i>View Recording
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <div class="flex flex-wrap gap-4 justify-center">
            @if($consultation->isActive())
            <a href="{{ route('student.video-consultation.join', $consultation->id) }}" 
               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                <i class="fas fa-video me-2"></i>Join Video Call
            </a>
            @endif
            
            @if($consultation->status === 'completed')
            <button onclick="window.print()" 
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                <i class="fas fa-print me-2"></i>Print Prescription
            </button>
            @endif

            <a href="{{ route('student.video-consultation.create') }}" 
               class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors font-semibold">
                <i class="fas fa-plus me-2"></i>New Consultation
            </a>

            <a href="{{ route('student.video-consultation.index') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                <i class="fas fa-list me-2"></i>View All Consultations
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

@media print {
    .no-print {
        display: none !important;
    }
    
    .content-card {
        box-shadow: none;
        border: 1px solid #e5e7eb;
    }
}
</style>
@endsection