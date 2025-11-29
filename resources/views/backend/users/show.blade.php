@extends('layouts.app')

@section('title', $user->name . ' - User Details')

@section('content')
<div class="space-y-6">
    <!-- User Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    @if($user->profile_image)
                        <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('public/storage/' . $user->profile_image) }}" alt="{{ $user->name }}">
                    @else
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    @endif
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                    <p class="text-gray-600">{{ ucfirst($user->role) }} • {{ $user->school->name ?? 'No School' }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- User Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column - Basic Information -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Basic Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Basic Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Email Address</p>
                            <p class="text-lg text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Phone Number</p>
                            <p class="text-lg text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Date of Birth</p>
                            <p class="text-lg text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Gender</p>
                            <p class="text-lg text-gray-900">{{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">School</p>
                            <p class="text-lg text-gray-900">{{ $user->school->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Member Since</p>
                            <p class="text-lg text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Address Information</h4>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $user->address ?? 'No address provided' }}</p>
                </div>
            </div>

            <!-- Professional Information -->
            @if($user->specialization || $user->qualifications)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Professional Information</h4>
                
                <div class="space-y-4">
                    @if($user->specialization)
                    <div>
                        <p class="text-sm font-medium text-gray-600">Specialization</p>
                        <p class="text-lg text-gray-900">{{ $user->specialization }}</p>
                    </div>
                    @endif
                    
                    @if($user->qualifications)
                    <div>
                        <p class="text-sm font-medium text-gray-600">Qualifications</p>
                        <div class="prose max-w-none mt-2">
                            <p class="text-gray-700 whitespace-pre-line">{{ $user->qualifications }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Statistics & Actions -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Statistics</h4>
                
                <div class="space-y-4">
                    @if($user->role === 'teacher')
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Sections Assigned</p>
                                <p class="text-2xl font-bold text-blue-700">{{ $statistics['sections_count'] ?? 0 }}</p>
                            </div>
                            <i class="fas fa-chalkboard text-blue-400 text-xl"></i>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-green-600">Subjects Teaching</p>
                                <p class="text-2xl font-bold text-green-700">{{ $statistics['subjects_count'] ?? 0 }}</p>
                            </div>
                            <i class="fas fa-book text-green-400 text-xl"></i>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-purple-600">Exams Created</p>
                                <p class="text-2xl font-bold text-purple-700">{{ $statistics['exams_created'] ?? 0 }}</p>
                            </div>
                            <i class="fas fa-file-alt text-purple-400 text-xl"></i>
                        </div>
                    
                    @elseif($user->role === 'student')
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Medical Records</p>
                                <p class="text-2xl font-bold text-blue-700">{{ $statistics['medical_records_count'] ?? 0 }}</p>
                            </div>
                            <i class="fas fa-file-medical text-blue-400 text-xl"></i>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-green-600">Exam Attempts</p>
                                <p class="text-2xl font-bold text-green-700">{{ $statistics['exam_attempts_count'] ?? 0 }}</p>
                            </div>
                            <i class="fas fa-graduation-cap text-green-400 text-xl"></i>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-purple-600">Health Card</p>
                                <p class="text-2xl font-bold text-purple-700">{{ $statistics['health_card'] ?? 'None' }}</p>
                            </div>
                            <i class="fas fa-id-card text-purple-400 text-xl"></i>
                        </div>
                    
                    @elseif($user->role === 'doctor')
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Medical Records</p>
                                <p class="text-2xl font-bold text-blue-700">{{ $statistics['medical_records_count'] ?? 0 }}</p>
                            </div>
                            <i class="fas fa-file-medical text-blue-400 text-xl"></i>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-green-600">Health Tips</p>
                                <p class="text-2xl font-bold text-green-700">{{ $statistics['health_tips_count'] ?? 0 }}</p>
                            </div>
                            <i class="fas fa-heart text-green-400 text-xl"></i>
                        </div>
                    
                    @else
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Notices Published</p>
                                <p class="text-2xl font-bold text-blue-700">{{ $statistics['notices_published'] ?? 0 }}</p>
                            </div>
                            <i class="fas fa-bullhorn text-blue-400 text-xl"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Edit User</span>
                    </a>
                    
                    @if($user->role === 'student')
                    <a href="#" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-medical text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">View Medical Records</span>
                    </a>
                    @endif
                    
                    @if($user->role === 'teacher')
                    <a href="#" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard text-purple-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">View Classes</span>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Status Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Status</h4>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Current Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                
                @if($user->status == 'active')
                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-sm text-green-700">This user is active</span>
                    </div>
                </div>
                @else
                <div class="mt-3 p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-sm text-red-700">This user is currently inactive</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Danger Zone -->
            <div class="content-card rounded-lg p-6 shadow-sm border border-red-200">
                <h4 class="text-xl font-semibold text-red-900 border-b border-red-200 pb-3 mb-4">Danger Zone</h4>
                
                <div class="space-y-3">
                    <p class="text-sm text-gray-600">
                        Once you delete a user, there is no going back. Please be certain.
                    </p>
                    
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center space-x-3 p-3 hover:bg-red-50 rounded-lg transition-colors duration-200 w-full text-left text-red-600"
                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trash text-red-600 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium">Delete User</span>
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

    .prose {
        color: #374151;
        line-height: 1.75;
    }
</style>
@endsection