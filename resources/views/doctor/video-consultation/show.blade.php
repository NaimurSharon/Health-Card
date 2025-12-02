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
        <!-- Right Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Action Required Banner -->
            @if($consultation->status == 'completed' && !$consultation->prescription)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm animate-pulse">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">Action Required: Prescription Missing</h3>
                        <div class="mt-1 text-sm text-red-700">
                            <p>This consultation is marked as completed. Please provide the prescription and medical notes below to finalize the record.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Prescription & Notes Section (CRITICAL) -->
            <div class="content-card rounded-lg p-6 shadow-md border-t-4 border-t-blue-600">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-prescription text-blue-600 mr-2"></i>
                        Prescription & Medical Notes
                    </h4>
                    @if($consultation->status == 'completed' && !$consultation->prescription)
                        <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Required</span>
                    @endif
                </div>

                @if($consultation->status == 'completed' || $consultation->status == 'ongoing')
                    <form action="{{ route('doctor.video-consultation.prescription', $consultation->id) }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <!-- Main Prescription Area -->
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                <label class="block text-base font-semibold text-gray-800 mb-2">
                                    Rx / Prescription Details <span class="text-red-500">*</span>
                                </label>
                                <textarea name="prescription" rows="6" required 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-base shadow-sm"
                                          placeholder="• Medication Name (Dosage) - Frequency - Duration&#10;• Medication Name (Dosage) - Frequency - Duration&#10;• Special Instructions">{{ old('prescription', $consultation->prescription) }}</textarea>
                                <p class="text-xs text-gray-500 mt-2">Please list all prescribed medications, dosages, and instructions clearly.</p>
                            </div>
                            
                            <!-- Doctor's Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Clinical Notes & Observations</label>
                                <textarea name="doctor_notes" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-gray-500 focus:ring-1 focus:ring-gray-200"
                                          placeholder="Patient history, examination findings, diagnosis notes...">{{ old('doctor_notes', $consultation->doctor_notes) }}</textarea>
                            </div>

                            <!-- Quick Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Key Medication Summary</label>
                                    <input type="text" name="medication" 
                                           value="{{ old('medication', $consultation->call_metadata['medication'] ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200"
                                           placeholder="e.g., Napa 500mg, Seclo 20mg">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                                    <input type="date" name="follow_up_date" 
                                           value="{{ old('follow_up_date', $consultation->call_metadata['follow_up_date'] ?? '') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <p class="text-sm text-gray-500 italic">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    This will be saved to the patient's medical record.
                                </p>
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-all transform hover:scale-105 font-bold shadow-lg flex items-center">
                                    <i class="fas fa-save me-2"></i>
                                    {{ $consultation->prescription ? 'Update Prescription' : 'Save Prescription' }}
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                        <i class="fas fa-lock text-4xl mb-3 text-gray-300"></i>
                        <h5 class="text-lg font-medium text-gray-600">Prescription Locked</h5>
                        <p class="text-gray-500 max-w-md mx-auto mt-2">
                            The prescription form will become available once the consultation call has started or is completed.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Consultation Details (Secondary) -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">
                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>Call Details
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Call ID</span>
                                <span class="font-mono text-gray-900">{{ $consultation->call_id }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Status</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $consultation->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($consultation->status == 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                       ($consultation->status == 'scheduled' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($consultation->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Type</span>
                                <span class="font-medium capitalize">{{ $consultation->type }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Scheduled</span>
                                <span class="font-medium">{{ $consultation->scheduled_for->format('M j, Y g:i A') }}</span>
                            </div>
                            @if($consultation->duration)
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Duration</span>
                                <span class="font-medium">{{ gmdate('H:i:s', $consultation->duration) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Fee</span>
                                <span class="font-medium text-green-600">৳ {{ number_format($consultation->consultation_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Payment</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $consultation->payment_status == 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($consultation->payment_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($consultation->payment_status) }}
                                </span>
                            </div>
                            @if($consultation->payment)
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Method</span>
                                <span class="font-medium capitalize">{{ $consultation->payment->payment_method ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-600">Trx ID</span>
                                <span class="font-mono text-gray-900">{{ $consultation->payment->transaction_id ?? 'N/A' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Information -->
        <div class="content-card rounded-lg p-6 shadow-sm lg:col-span-1 h-fit">
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
                
                <div class="pt-4 border-t border-gray-200">
                    <h6 class="font-semibold text-gray-900 mb-2">Reported Symptoms</h6>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-sm text-gray-700">{{ $consultation->symptoms }}</p>
                    </div>
                </div>
            </div>
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