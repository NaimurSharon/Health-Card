@extends('layouts.app')

@section('title', 'Treatment Request Details')

@section('content')
<div class="space-y-6">
    <!-- Request Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-medical text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Treatment Request</h3>
                    <p class="text-gray-600">Requested on {{ \Carbon\Carbon::parse($treatmentRequest->created_at)->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.treatment-requests.edit', $treatmentRequest) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Request
                </a>
                <a href="{{ route('admin.treatment-requests.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Request Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Basic Information -->
        <div class="space-y-6">
            <!-- Student Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Student Information</h4>
                
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-lg font-medium text-gray-700">
                            {{ substr($treatmentRequest->student->user->name, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h5 class="text-lg font-semibold text-gray-900">{{ $treatmentRequest->student->user->name }}</h5>
                        <p class="text-sm text-gray-600">{{ $treatmentRequest->student->student_id }}</p>
                        <p class="text-sm text-gray-500">{{ $treatmentRequest->student->class->name ?? 'N/A' }} - {{ $treatmentRequest->student->section->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Request Details</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Priority</span>
                        @php
                            $priorityColors = [
                                'low' => 'bg-green-100 text-green-800',
                                'medium' => 'bg-yellow-100 text-yellow-800',
                                'high' => 'bg-orange-100 text-orange-800',
                                'emergency' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $priorityColors[$treatmentRequest->priority] }}">
                            {{ ucfirst($treatmentRequest->priority) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Request Date</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($treatmentRequest->requested_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Requested By</span>
                        <span class="text-sm text-gray-900">{{ $treatmentRequest->requestedBy->name ?? 'System' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Created</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($treatmentRequest->created_at)->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Column - Medical Information -->
        <div class="space-y-6">
            <!-- Symptoms -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Symptoms</h4>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $treatmentRequest->symptoms }}</p>
                </div>
            </div>

            <!-- Doctor Notes -->
            @if($treatmentRequest->doctor_notes)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Doctor Notes</h4>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $treatmentRequest->doctor_notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Status & Actions -->
        <div class="space-y-6">
            <!-- Status Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Status Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Current Status</span>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-blue-100 text-blue-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'completed' => 'bg-green-100 text-green-800'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$treatmentRequest->status] }}">
                            {{ ucfirst($treatmentRequest->status) }}
                        </span>
                    </div>
                    
                    @if($treatmentRequest->doctor)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Assigned Doctor</span>
                        <span class="text-sm text-gray-900">{{ $treatmentRequest->doctor->name }}</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($treatmentRequest->updated_at)->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.medical.records.create') }}?student_id={{ $treatmentRequest->student_id }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-medical text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Create Medical Record</span>
                    </a>
                    
                    <a href="{{ route('admin.students.show', $treatmentRequest->student) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-graduate text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">View Student Profile</span>
                    </a>
                    
                    @if($treatmentRequest->doctor)
                    <a href="{{ route('admin.doctors.show', $treatmentRequest->doctor) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-md text-purple-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">View Doctor Profile</span>
                    </a>
                    @endif
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