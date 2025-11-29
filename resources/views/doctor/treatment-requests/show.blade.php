<<<<<<< HEAD
@extends('layouts.app')

=======
@extends('layouts.doctor
>>>>>>> c356163 (video call ui setup)
@section('title', 'Treatment Request Details')

@section('content')
<div class="space-y-6">
    <!-- Treatment Request Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-stethoscope text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Treatment Request Details</h3>
                    <p class="text-gray-600">Requested on {{ $treatmentRequest->created_at->format('F j, Y') }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('doctor.treatment-requests.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Request Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Request Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student Name</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $treatmentRequest->student->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student ID</p>
                            <p class="text-lg text-gray-900">{{ $treatmentRequest->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Class & Section</p>
                            <p class="text-lg text-gray-900">
                                {{ $treatmentRequest->student->class->name ?? 'N/A' }} - {{ $treatmentRequest->student->section->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Requested By</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $treatmentRequest->requestedBy->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Request Date</p>
                            <p class="text-lg text-gray-900">{{ $treatmentRequest->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Urgency</p>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                {{ $treatmentRequest->urgency == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($treatmentRequest->urgency == 'urgent' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($treatmentRequest->urgency) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Symptoms -->
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-600 mb-2">Symptoms</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $treatmentRequest->symptoms }}</p>
                    </div>
                </div>

                <!-- Notes -->
                @if($treatmentRequest->notes)
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">Additional Notes</p>
                    <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-400">
                        <p class="text-gray-700">{{ $treatmentRequest->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Update Status Form -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Update Request Status</h4>
                
                <form action="{{ route('doctor.treatment-requests.update', $treatmentRequest) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="pending" {{ $treatmentRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $treatmentRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $treatmentRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ $treatmentRequest->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Doctor Notes</label>
                        <textarea name="notes" id="notes" rows="4"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Add any notes about this treatment request...">{{ old('notes', $treatmentRequest->notes) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Student Information & Actions -->
        <div class="space-y-6">
            <!-- Student Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Student Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $treatmentRequest->student->user->name }}</p>
                            <p class="text-sm text-gray-600">ID: {{ $treatmentRequest->student->student_id }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Class:</span>
                            <span class="text-sm font-medium">{{ $treatmentRequest->student->class->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Section:</span>
                            <span class="text-sm font-medium">{{ $treatmentRequest->student->section->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Blood Group:</span>
                            <span class="text-sm font-medium">{{ $treatmentRequest->student->blood_group ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Medical Alerts -->
                @if($treatmentRequest->student->allergies || $treatmentRequest->student->medical_conditions)
                <div class="mt-4 space-y-2">
                    @if($treatmentRequest->student->allergies)
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xs"></i>
                        <span class="text-xs text-gray-600">Allergies: {{ $treatmentRequest->student->allergies }}</span>
                    </div>
                    @endif
                    @if($treatmentRequest->student->medical_conditions)
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-heartbeat text-red-500 text-xs"></i>
                        <span class="text-xs text-gray-600">Conditions: {{ $treatmentRequest->student->medical_conditions }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Status Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Request Status</h4>
                
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Current Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                        {{ $treatmentRequest->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                           ($treatmentRequest->status == 'approved' ? 'bg-green-100 text-green-800' : 
                           ($treatmentRequest->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst($treatmentRequest->status) }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Priority</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                        {{ $treatmentRequest->priority == 'emergency' ? 'bg-red-100 text-red-800' : 
                           ($treatmentRequest->priority == 'high' ? 'bg-orange-100 text-orange-800' : 
                           ($treatmentRequest->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ ucfirst($treatmentRequest->priority) }}
                    </span>
                </div>
                
                @if($treatmentRequest->status == 'pending')
                <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        <span class="text-sm text-yellow-700">This request is awaiting your action</span>
                    </div>
                </div>
                @elseif($treatmentRequest->status == 'approved')
                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-sm text-green-700">This request has been approved</span>
                    </div>
                </div>
                @elseif($treatmentRequest->status == 'completed')
                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-double text-blue-500 mr-2"></i>
                        <span class="text-sm text-blue-700">This request has been completed</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('doctor.patients.show', $treatmentRequest->student) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-injured text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">View Patient</span>
                    </a>
                    
                    <a href="{{ route('doctor.medical-records.create') }}?student_id={{ $treatmentRequest->student_id }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-medical text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Create Medical Record</span>
                    </a>
                    
                    <a href="{{ route('doctor.appointments.index') }}?student_id={{ $treatmentRequest->student_id }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-plus text-purple-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Schedule Appointment</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>
@endsection