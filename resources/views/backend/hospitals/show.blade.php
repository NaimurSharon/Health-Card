@extends('layouts.app')

@section('title', 'Hospital Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Hospital Details</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.hospitals.edit', $hospital) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Hospital
                </a>
                <a href="{{ route('admin.hospitals.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Hospital Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Hospital Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Hospital Type</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->type_label }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $hospital->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($hospital->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Total Doctors</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->doctors_count }} doctors</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Contact Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Emergency Contact</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->emergency_contact }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Website</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($hospital->website)
                                <a href="{{ $hospital->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ $hospital->website }}
                                </a>
                            @else
                                <span class="text-gray-400">Not provided</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500">Address</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $hospital->address }}</p>
                </div>
            </div>

            <!-- Services & Facilities Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Services & Facilities</h4>
                
                <!-- Services -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-500 mb-3">Medical Services</label>
                    @if($hospital->services && count($hospital->services) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($hospital->services as $service)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-stethoscope mr-1"></i>
                                    {{ trim($service) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No services listed</p>
                    @endif
                </div>

                <!-- Facilities -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-3">Facilities</label>
                    @if($hospital->facilities)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $hospital->facilities }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No facilities information provided</p>
                    @endif
                </div>
            </div>

            <!-- Associated Doctors Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Associated Doctors</h4>
                @if($hospital->doctors_count > 0)
                    <div class="space-y-4">
                        @foreach($hospital->doctors as $doctor)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-md text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('admin.doctors.show', $doctor) }}" class="text-sm font-medium text-gray-900">{{ $doctor->name }}</a>
                                        <div class="text-sm text-gray-500">{{ $doctor->specialization }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $doctor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($doctor->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-user-md text-4xl mb-4 text-gray-300"></i>
                        <p class="text-gray-500">No doctors associated with this hospital</p>
                        <p class="text-sm text-gray-400 mt-2">Doctors can be assigned to this hospital when creating or editing their profiles.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Summary Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="text-center">
                    <div class="mx-auto h-24 w-24 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-hospital text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $hospital->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $hospital->type_label }} Hospital</p>
                    
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Status:</span>
                            <span class="font-medium text-gray-900 capitalize">{{ $hospital->status }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Total Doctors:</span>
                            <span class="font-medium text-gray-900">{{ $hospital->doctors_count }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Member Since:</span>
                            <span class="font-medium text-gray-900">{{ $hospital->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Last Updated:</span>
                            <span class="font-medium text-gray-900">{{ $hospital->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('admin.hospitals.edit', $hospital) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Edit Hospital
                    </a>
                    <a href="mailto:{{ $hospital->email }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i>Send Email
                    </a>
                    <a href="tel:{{ $hospital->phone }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-phone mr-2"></i>Call Hospital
                    </a>
                    @if($hospital->doctors_count == 0)
                        <form action="{{ route('admin.hospitals.destroy', $hospital) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this hospital?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-trash mr-2"></i>Delete Hospital
                            </button>
                        </form>
                    @else
                        <button disabled
                                class="w-full bg-gray-400 text-white px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center cursor-not-allowed"
                                title="Cannot delete hospital with associated doctors">
                            <i class="fas fa-trash mr-2"></i>Delete Hospital
                        </button>
                        <p class="text-xs text-gray-500 text-center mt-1">
                            Remove all doctors first to delete
                        </p>
                    @endif
                </div>
            </div>

            <!-- Emergency Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Emergency Contact</h4>
                <div class="text-center">
                    <div class="bg-red-50 rounded-lg p-4">
                        <i class="fas fa-ambulance text-red-500 text-2xl mb-2"></i>
                        <p class="text-lg font-semibold text-red-700">{{ $hospital->emergency_contact }}</p>
                        <p class="text-sm text-red-600 mt-1">24/7 Emergency Service</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">
                        Use this number for emergency situations only
                    </p>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add copy to clipboard functionality for emergency contact
        const emergencyContact = document.querySelector('.emergency-contact');
        if (emergencyContact) {
            emergencyContact.addEventListener('click', function() {
                const text = this.textContent || this.innerText;
                navigator.clipboard.writeText(text).then(function() {
                    // Show copied notification
                    const originalText = emergencyContact.innerHTML;
                    emergencyContact.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
                    setTimeout(() => {
                        emergencyContact.innerHTML = originalText;
                    }, 2000);
                });
            });
        }
    });
</script>
@endsection