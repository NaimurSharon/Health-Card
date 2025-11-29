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
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->doctorDetail->experience ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">License Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->doctorDetail->license_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Department</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->doctorDetail->department ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Designation</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $doctor->doctorDetail->designation ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Hospital</label>
                        @if($doctor->hospital)
                            <a href="{{ route('admin.hospitals.show', $doctor->hospital) }}" class="mt-1 text-bold text-gray-900 hover:text-blue-600">{{ $doctor->hospital->name }}</a>
                            <p class="mt-1 text-sm text-gray-500">{{ $doctor->hospital->address }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $doctor->hospital->phone }}</p>
                        @else
                            <p class="mt-1 text-sm text-gray-900">N/A</p>
                        @endif
                    </div>
                </div>
                
                <!-- Bio -->
                @if($doctor->doctorDetail && $doctor->doctorDetail->bio)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500">Bio</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $doctor->doctorDetail->bio }}</p>
                </div>
                @endif

                <!-- Specializations -->
                @if($doctor->doctorDetail && $doctor->doctorDetail->specializations)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500">Specializations</label>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($doctor->doctorDetail->specializations as $specialization)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $specialization }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Languages -->
                @if($doctor->doctorDetail && $doctor->doctorDetail->languages)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500">Languages</label>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($doctor->doctorDetail->languages as $language)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $language }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Fees & Availability Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Fees & Availability</h4>
                
                <!-- Fees -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <label class="block text-sm font-medium text-blue-600">Consultation Fee</label>
                        <p class="mt-1 text-2xl font-bold text-blue-900">
                            {{ $doctor->doctorDetail ? $doctor->doctorDetail->formatted_consultation_fee : '৳0.00' }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <label class="block text-sm font-medium text-green-600">Follow-up Fee</label>
                        <p class="mt-1 text-2xl font-bold text-green-900">
                            {{ $doctor->doctorDetail && $doctor->doctorDetail->follow_up_fee ? $doctor->doctorDetail->formatted_follow_up_fee : 'N/A' }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <label class="block text-sm font-medium text-purple-600">Emergency Fee</label>
                        <p class="mt-1 text-2xl font-bold text-purple-900">
                            {{ $doctor->doctorDetail && $doctor->doctorDetail->emergency_fee ? $doctor->doctorDetail->formatted_emergency_fee : 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Availability Status -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Availability</label>
                        <p class="text-sm text-gray-600">Doctor is currently 
                            <span class="font-semibold {{ $doctor->doctorDetail && $doctor->doctorDetail->is_available ? 'text-green-600' : 'text-red-600' }}">
                                {{ $doctor->doctorDetail && $doctor->doctorDetail->is_available ? 'Available' : 'Not Available' }}
                            </span>
                            for appointments
                        </p>
                    </div>
                    <form action="{{ route('admin.doctors.toggle-availability', $doctor) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                                    {{ $doctor->doctorDetail && $doctor->doctorDetail->is_available 
                                        ? 'bg-red-600 hover:bg-red-700 text-white' 
                                        : 'bg-green-600 hover:bg-green-700 text-white' }}">
                            {{ $doctor->doctorDetail && $doctor->doctorDetail->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                        </button>
                    </form>
                </div>

                <!-- Weekly Schedule -->
                @if($doctor->doctorAvailabilities && $doctor->doctorAvailabilities->count() > 0)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Weekly Schedule</label>
                    <div class="space-y-3">
                        @php
                            $daysOrder = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                            $availabilities = $doctor->doctorAvailabilities->keyBy('day_of_week');
                        @endphp
                        
                        @foreach($daysOrder as $day)
                            @php $availability = $availabilities[$day] ?? null; @endphp
                            <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <span class="w-24 text-sm font-medium text-gray-700 capitalize">{{ $day }}</span>
                                    @if($availability && $availability->is_available)
                                        <span class="text-sm text-green-600 ml-4">{{ $availability->bangladeshi_time }}</span>
                                        <span class="text-xs text-gray-500 ml-4">({{ $availability->slot_duration }} min slots)</span>
                                    @else
                                        <span class="text-sm text-red-600 ml-4">Not Available</span>
                                    @endif
                                </div>
                                @if($availability && $availability->is_available)
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        Max: {{ $availability->max_appointments }} patients
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Upcoming Leave Dates -->
            @if($doctor->doctorLeaveDates && $doctor->doctorLeaveDates->where('leave_date', '>=', now())->count() > 0)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Upcoming Leave Dates</h4>
                <div class="space-y-3">
                    @foreach($doctor->doctorLeaveDates->where('leave_date', '>=', now())->sortBy('leave_date') as $leave)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $leave->leave_date->format('M d, Y') }}
                                </span>
                                <span class="text-sm text-gray-600 ml-4">{{ $leave->reason }}</span>
                                <span class="text-xs text-gray-500 ml-4">({{ $leave->formatted_time }})</span>
                            </div>
                            <form action="{{ route('admin.doctors.leave-dates.destroy', [$doctor, $leave]) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this leave date?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Summary Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="text-center">
                    <div class="mx-auto h-24 w-24 rounded-full flex items-center justify-center mb-4 overflow-hidden border-2 border-gray-200">
                    @if($doctor->profile_image)
                        <img src="{{ asset('public/storage/' . $doctor->profile_image) }}" 
                             alt="{{ $doctor->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user-md text-blue-600 text-3xl"></i>
                        </div>
                    @endif
                </div>

                    <h3 class="text-lg font-semibold text-gray-900">{{ $doctor->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $doctor->specialization }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $doctor->qualifications }}</p>
                    
                    <!-- Region Badge -->
                    @if($doctor->hospital)
                        <div class="mt-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $doctor->bangladeshi_region }}
                            </span>
                        </div>
                    @endif

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Role:</span>
                            <span class="font-medium text-gray-900 capitalize">{{ $doctor->role }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Max Patients/Day:</span>
                            <span class="font-medium text-gray-900">{{ $doctor->doctorDetail->max_patients_per_day ?? 20 }}</span>
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
                    <a href="{{ route('admin.doctors.leave-dates', $doctor) }}" 
                       class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-calendar-times mr-2"></i>Manage Leave
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
                          onsubmit="return confirm('Are you sure you want to delete this doctor? This will also delete all associated data.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>Delete Doctor
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Statistics</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Appointments</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $doctor->appointmentsAsDoctor->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Scheduled</span>
                        <span class="text-sm font-semibold text-blue-600">
                            {{ $doctor->appointmentsAsDoctor->where('status', 'scheduled')->count() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Completed</span>
                        <span class="text-sm font-semibold text-green-600">
                            {{ $doctor->appointmentsAsDoctor->where('status', 'completed')->count() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Upcoming This Week</span>
                        <span class="text-sm font-semibold text-orange-600">
                            {{ $doctor->appointmentsAsDoctor->where('status', 'scheduled')->whereBetween('appointment_date', [now(), now()->addWeek()])->count() }}
                        </span>
                    </div>
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