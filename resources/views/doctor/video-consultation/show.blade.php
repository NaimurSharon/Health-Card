@extends('layouts.doctor')

@section('title', 'Consultation Details - ' . $consultation->student->user->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">Consultation Details</h3>
                    <p class="text-blue-100">Video consultation with {{ $consultation->student->user->name }}</p>
                </div>
                <div class="flex space-x-3">
                    @if($consultation->isActive())
                    <a href="{{ route('doctor.video-consultation.join', $consultation->id) }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-video me-2"></i>Join Call
                    </a>
                    @endif
                    <a href="{{ route('doctor.video-consultation.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Patient Information -->
        <div class="content-card rounded-lg p-6 shadow-sm lg:col-span-1">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Patient Information</h4>
            
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900">{{ $consultation->student->user->name }}</h5>
                        <p class="text-sm text-gray-600">Student ID: {{ $consultation->student->student_id }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Class</p>
                        <p class="font-medium text-gray-900">{{ $consultation->student->class->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Section</p>
                        <p class="font-medium text-gray-900">{{ $consultation->student->section->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Roll No</p>
                        <p class="font-medium text-gray-900">{{ $consultation->student->roll_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Blood Group</p>
                        <p class="font-medium text-gray-900">{{ $consultation->student->blood_group ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <h6 class="font-semibold text-gray-900 mb-2">Medical Information</h6>
                    <div class="space-y-1 text-sm">
                        <p><span class="text-gray-600">Allergies:</span> 
                           <span class="font-medium">{{ $consultation->student->allergies ?? 'None reported' }}</span></p>
                        <p><span class="text-gray-600">Conditions:</span> 
                           <span class="font-medium">{{ $consultation->student->medical_conditions ?? 'None reported' }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultation Details -->
        <div class="content-card rounded-lg p-6 shadow-sm lg:col-span-2">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Consultation Details</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h5 class="font-semibold text-gray-900 mb-3">Call Information</h5>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Call ID:</span>
                            <span class="font-mono text-gray-900">{{ $consultation->call_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $consultation->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($consultation->status == 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                   ($consultation->status == 'scheduled' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($consultation->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium capitalize">{{ $consultation->type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Scheduled:</span>
                            <span class="font-medium">{{ $consultation->scheduled_for->format('M j, Y g:i A') }}</span>
                        </div>
                        @if($consultation->started_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Started:</span>
                            <span class="font-medium">{{ $consultation->started_at->format('M j, Y g:i A') }}</span>
                        </div>
                        @endif
                        @if($consultation->ended_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ended:</span>
                            <span class="font-medium">{{ $consultation->ended_at->format('M j, Y g:i A') }}</span>
                        </div>
                        @endif
                        @if($consultation->duration)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-medium">{{ gmdate('H:i:s', $consultation->duration) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h5 class="font-semibold text-gray-900 mb-3">Payment Information</h5>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Consultation Fee:</span>
                            <span class="font-medium text-green-600">৳ {{ number_format($consultation->consultation_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Status:</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $consultation->payment_status == 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($consultation->payment_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($consultation->payment_status) }}
                            </span>
                        </div>
                        @if($consultation->payment)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-medium capitalize">{{ $consultation->payment->payment_method ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Transaction ID:</span>
                            <span class="font-mono text-gray-900">{{ $consultation->payment->transaction_id ?? 'N/A' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Symptoms -->
            <div class="mb-6">
                <h5 class="font-semibold text-gray-900 mb-3">Reported Symptoms</h5>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-gray-700">{{ $consultation->symptoms }}</p>
                </div>
            </div>

            <!-- Prescription Form -->
            @if($consultation->status == 'completed')
            <div class="border-t border-gray-200 pt-6">
                <h5 class="text-xl font-semibold text-gray-900 mb-4">Prescription & Notes</h5>
                
                <form action="{{ route('doctor.video-consultation.prescription', $consultation->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prescription *</label>
                            <textarea name="prescription" rows="6" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200"
                                      placeholder="Write the prescription details, medications, dosage, instructions...">{{ old('prescription', $consultation->prescription) }}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Doctor's Notes</label>
                            <textarea name="doctor_notes" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200"
                                      placeholder="Additional notes, observations, recommendations...">{{ old('doctor_notes', $consultation->doctor_notes) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Medication</label>
                                <input type="text" name="medication" 
                                       value="{{ old('medication', $consultation->call_metadata['medication'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200"
                                       placeholder="Prescribed medications">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                                <input type="date" name="follow_up_date" 
                                       value="{{ old('follow_up_date', $consultation->call_metadata['follow_up_date'] ?? '') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <i class="fas fa-save me-2"></i>Save Prescription
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @elseif($consultation->prescription)
            <div class="border-t border-gray-200 pt-6">
                <h5 class="text-xl font-semibold text-gray-900 mb-4">Prescription</h5>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="prose max-w-none">
                        <h6 class="font-semibold text-gray-900 mb-2">Prescription Details:</h6>
                        <p class="text-gray-700 whitespace-pre-line">{{ $consultation->prescription }}</p>
                        
                        @if($consultation->doctor_notes)
                        <h6 class="font-semibold text-gray-900 mt-4 mb-2">Doctor's Notes:</h6>
                        <p class="text-gray-700 whitespace-pre-line">{{ $consultation->doctor_notes }}</p>
                        @endif
                        
                        @if(isset($consultation->call_metadata['medication']))
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm"><strong>Medication:</strong> {{ $consultation->call_metadata['medication'] }}</p>
                            @if(isset($consultation->call_metadata['follow_up_date']))
                            <p class="text-sm mt-1"><strong>Follow-up:</strong> {{ \Carbon\Carbon::parse($consultation->call_metadata['follow_up_date'])->format('M j, Y') }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="border-t border-gray-200 pt-6">
                <div class="text-center py-8">
                    <i class="fas fa-file-medical text-4xl mb-3 text-gray-300"></i>
                    <p class="text-gray-500">Prescription will be available after consultation completion</p>
                </div>
            </div>
            @endif
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
.prose h6 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}
</style>
@endsection