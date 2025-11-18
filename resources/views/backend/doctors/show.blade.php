@extends('layouts.app')

@section('title', 'Doctor Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Doctor Details</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.doctors.edit', $doctor) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Doctor
                </a>
                <a href="{{ route('admin.doctors.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Doctor Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Personal Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Gender</label>
                        <p class="mt-1 text-sm text-gray-900 capitalize">{{ $doctor->gender }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $doctor->date_of_birth ? $doctor->date_of_birth->format('M d, Y') : 'N/A' }}
                            @if($doctor->date_of_birth)
                                <span class="text-gray-500">({{ $doctor->age }} years old)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $doctor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($doctor->status) }}
                        </span>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500">Address</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $doctor->address }}</p>
                </div>
            </div>

            <!-- Professional Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Professional Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Specialization</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->specialization }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Qualifications</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->qualifications }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Experience</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->experience ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">License Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->license_number ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Hospital</label>
                        <a href="{{ route('admin.hospitals.show', $doctor->hospital) }}" class="mt-1 text-bold text-gray-900">{{ $doctor->hospital->name ?? 'N/A' }}</a>
                        @if($doctor->hospital)
                            <p class="mt-1 text-sm text-gray-500">{{ $doctor->hospital->address }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $doctor->hospital->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Summary Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="text-center">
                    <div class="mx-auto h-24 w-24 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-md text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $doctor->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $doctor->specialization }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $doctor->qualifications }}</p>
                    
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Role:</span>
                            <span class="font-medium text-gray-900 capitalize">{{ $doctor->role }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Member Since:</span>
                            <span class="font-medium text-gray-900">{{ $doctor->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Last Updated:</span>
                            <span class="font-medium text-gray-900">{{ $doctor->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('admin.doctors.edit', $doctor) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Edit Doctor
                    </a>
                    <a href="mailto:{{ $doctor->email }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i>Send Email
                    </a>
                    <a href="tel:{{ $doctor->phone }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-phone mr-2"></i>Call Doctor
                    </a>
                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this doctor?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>Delete Doctor
                        </button>
                    </form>
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