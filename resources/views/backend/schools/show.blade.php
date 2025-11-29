@extends('layouts.app')

@section('title', $school->name . ' - School Details')

@section('content')
<div class="space-y-6">
    <!-- School Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                @if($school->logo)
                    <img src="{{ asset($school->logo) }}" alt="{{ $school->name }} Logo" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-school text-blue-600 text-xl"></i>
                    </div>
                @endif
                <div>
                    <h3 class="text-2xl font-bold">{{ $school->name }}</h3>
                    <p class="text-gray-600">{{ $school->code }} • {{ ucfirst($school->type) }} School</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.schools.edit', $school) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit School
                </a>
                <a href="{{ route('admin.schools.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- School Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column - Basic Information -->
        <div class="lg:col-span-3 space-y-6">
            <!-- School Images -->
            @if($school->school_image || $school->cover_image)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">School Images</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($school->cover_image)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Cover Image</p>
                        <img src="{{ asset($school->cover_image) }}" alt="Cover Image" class="w-full h-48 object-cover rounded-lg shadow-md">
                    </div>
                    @endif
                    @if($school->school_image)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">School Image</p>
                        <img src="{{ asset($school->school_image) }}" alt="School Image" class="w-full h-48 object-cover rounded-lg shadow-md">
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Basic Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Basic Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">School Code</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $school->code }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">School Type</p>
                            <p class="text-lg text-gray-900 capitalize">{{ $school->type }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Email Address</p>
                            <p class="text-lg text-gray-900">{{ $school->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Phone Number</p>
                            <p class="text-lg text-gray-900">{{ $school->phone }}</p>
                        </div>
                        @if($school->website)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Website</p>
                            <a href="{{ $school->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-lg">
                                {{ $school->website }}
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Principal</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $school->principal_name }}</p>
                            @if($school->principal_phone)
                            <p class="text-sm text-gray-600">{{ $school->principal_phone }}</p>
                            @endif
                            @if($school->principal_email)
                            <p class="text-sm text-gray-600">{{ $school->principal_email }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Established Year</p>
                            <p class="text-lg text-gray-900">{{ $school->established_year }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Years Operating</p>
                            <p class="text-lg text-gray-900">{{ date('Y') - $school->established_year }} years</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Academic System</p>
                            <p class="text-lg text-gray-900 capitalize">{{ $school->academic_system }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Medium</p>
                            <p class="text-lg text-gray-900 capitalize">{{ $school->medium }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Location Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Division</p>
                        <p class="text-lg text-gray-900">{{ $school->division }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">District</p>
                        <p class="text-lg text-gray-900">{{ $school->district }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">City</p>
                        <p class="text-lg text-gray-900">{{ $school->city }}</p>
                    </div>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-600">Full Address</p>
                    <p class="text-gray-700 mt-1">{{ $school->address }}</p>
                </div>
            </div>

            <!-- School Identity -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">School Identity</h4>
                
                @if($school->motto)
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-600">Motto</p>
                    <p class="text-lg text-gray-900 italic">"{{ $school->motto }}"</p>
                </div>
                @endif
                
                @if($school->vision)
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-600">Vision</p>
                    <p class="text-gray-700">{{ $school->vision }}</p>
                </div>
                @endif
                
                @if($school->mission)
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-600">Mission</p>
                    <p class="text-gray-700">{{ $school->mission }}</p>
                </div>
                @endif
            </div>

            <!-- Facilities & Accreditations -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Facilities & Accreditations</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($school->facilities)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-3">Facilities</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(json_decode($school->facilities) as $facility)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $facility }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($school->accreditations)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-3">Accreditations</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(json_decode($school->accreditations) as $accreditation)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $accreditation }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Statistics & Actions -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Statistics</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Total Users</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $school->users_count }}</p>
                        </div>
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-green-600">Students</p>
                            <p class="text-2xl font-bold text-green-700">
                                {{ $school->users->where('role', 'student')->count() }}
                            </p>
                        </div>
                        <i class="fas fa-user-graduate text-green-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Teachers</p>
                            <p class="text-2xl font-bold text-purple-700">
                                {{ $school->users->where('role', 'teacher')->count() }}
                            </p>
                        </div>
                        <i class="fas fa-chalkboard-teacher text-purple-400 text-xl"></i>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-orange-600">Classes</p>
                            <p class="text-2xl font-bold text-orange-700">{{ $school->classes_count ?? 0 }}</p>
                        </div>
                        <i class="fas fa-chalkboard text-orange-400 text-xl"></i>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-red-600">Campus Area</p>
                            <p class="text-2xl font-bold text-red-700">{{ number_format($school->campus_area) }} sq ft</p>
                        </div>
                        <i class="fas fa-building text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.schools.edit', $school) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Edit School</span>
                    </a>
                    
                    <a href="{{ route('admin.users.create') }}?school_id={{ $school->id }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-plus text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Add User</span>
                    </a>
                    
                    <a href="{{ route('admin.classes.create') }}?school_id={{ $school->id }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-purple-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Add Class</span>
                    </a>

                    <a href="{{ route('admin.notices.create') }}?school_id={{ $school->id }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bullhorn text-orange-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Add Notice</span>
                    </a>
                </div>
            </div>

            <!-- Status Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Status</h4>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Current Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $school->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($school->status) }}
                    </span>
                </div>
                
                @if($school->status == 'active')
                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-sm text-green-700">This school is active and operational</span>
                    </div>
                </div>
                @else
                <div class="mt-3 p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-sm text-red-700">This school is currently inactive</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Danger Zone -->
            <div class="content-card rounded-lg p-6 shadow-sm border border-red-200">
                <h4 class="text-xl font-semibold text-red-900 border-b border-red-200 pb-3 mb-4">Danger Zone</h4>
                
                <div class="space-y-3">
                    <p class="text-sm text-gray-600">
                        Once you delete a school, there is no going back. Please be certain.
                    </p>
                    
                    <form action="{{ route('admin.schools.destroy', $school) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center space-x-3 p-3 hover:bg-red-50 rounded-lg transition-colors duration-200 w-full text-left text-red-600"
                                onclick="return confirm('Are you sure you want to delete this school? This action cannot be undone.')">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trash text-red-600 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium">Delete School</span>
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