@extends('layouts.global')

@section('title', 'Registration Status')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Registration Status</h1>
                <p class="text-lg text-gray-600">Track your scholarship registration status</p>
            </div>

            <!-- Status Card -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $registration->exam->title }}</h3>
                        <p class="text-gray-600">Registration #: {{ $registration->registration_number }}</p>
                    </div>
                    <div class="text-right">
                        @if($registration->status === 'approved')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                Approved ✅
                            </span>
                        @elseif($registration->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                Pending Review ⏳
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                Rejected ❌
                            </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Submitted on:</span>
                        <span class="font-medium">{{ $registration->created_at->format('F j, Y g:i A') }}</span>
                    </div>
                    @if($registration->approved_at)
                    <div class="flex justify-between">
                        <span>Approved on:</span>
                        <span class="font-medium">{{ $registration->approved_at->format('F j, Y g:i A') }}</span>
                    </div>
                    @endif
                    @if($registration->approver)
                    <div class="flex justify-between">
                        <span>Approved by:</span>
                        <span class="font-medium">{{ $registration->approver->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Messages -->
            @if($registration->status === 'approved')
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-green-800 mb-2">Registration Approved!</h3>
                <p class="text-green-700 mb-4">
                    Congratulations! Your scholarship registration has been approved. You can now access available exams.
                </p>
                <a href="{{ route('student.scholarship') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    View Available Exams
                </a>
            </div>
            @elseif($registration->status === 'pending')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center mb-6">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-yellow-800 mb-2">Under Review</h3>
                <p class="text-yellow-700 mb-4">
                    Your registration is currently being reviewed by the administration. 
                    Please check back later for updates.
                </p>
                <p class="text-sm text-yellow-600">
                    Typically takes 1-3 business days
                </p>
            </div>
            @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-red-800 mb-2">Registration Rejected</h3>
                <p class="text-red-700 mb-4">
                    Unfortunately, your registration has been rejected.
                    @if($registration->admin_notes)
                    <br><strong>Reason:</strong> {{ $registration->admin_notes }}
                    @endif
                </p>
                <a href="{{ route('student.scholarship.register') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Reapply
                </a>
            </div>
            @endif

            <!-- Registration Details -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Your Registration Details</h4>
                <div class="space-y-4">
                    <div>
                        <h5 class="font-medium text-gray-700 mb-2">Academic Background</h5>
                        <p class="text-gray-600 text-sm">{{ $registration->academic_background }}</p>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-700 mb-2">Extracurricular Activities</h5>
                        <p class="text-gray-600 text-sm">{{ $registration->extracurricular_activities }}</p>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-700 mb-2">Achievements</h5>
                        <p class="text-gray-600 text-sm">{{ $registration->achievements }}</p>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-700 mb-2">Reason for Applying</h5>
                        <p class="text-gray-600 text-sm">{{ $registration->reason_for_applying }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection