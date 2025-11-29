@extends('layouts.app')

@section('title', 'Scholarship Registration Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Scholarship Registration Details</h3>
            <div class="flex space-x-3">
                @if($registration->status === 'pending')
                <form action="{{ route('admin.scholarship.registrations.approve', $registration) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-check mr-2"></i>Approve Registration
                    </button>
                </form>
                <button onclick="showRejectModal()" class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>Reject Registration
                </button>
                @else
                <form action="{{ route('admin.scholarship.registrations.pending', $registration) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-clock mr-2"></i>Set Pending
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.scholarship.registrations') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Registration Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Student Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->student->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->student->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Student ID</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->student->student_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Class & Section</label>
                        <p class="mt-1 text-sm text-gray-900">
                            Class {{ $registration->student->class->numeric_value ?? 'N/A' }} - 
                            {{ $registration->student->section->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Roll Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->student->roll_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Admission Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $registration->student->admission_date?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Application Details Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Application Details</h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Academic Background</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $registration->academic_background }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Extracurricular Activities</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $registration->extracurricular_activities }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Achievements & Awards</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $registration->achievements }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Reason for Applying</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $registration->reason_for_applying }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam Attempts Card -->
            @if($attempts->count() > 0)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Exam Attempts</h4>
                <div class="space-y-3">
                    @foreach($attempts as $attempt)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900">{{ $attempt->exam->title }}</p>
                                <p class="text-sm text-gray-600">
                                    Attempted: {{ $attempt->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold {{ $attempt->score >= $attempt->exam->passing_marks ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->score ?? 'N/A' }}/{{ $attempt->exam->total_marks }}
                                </p>
                                <p class="text-sm text-gray-600 capitalize">{{ $attempt->status }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Registration Summary Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="text-center">
                    <div class="mx-auto h-24 w-24 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-graduate text-purple-600 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $registration->student->user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $registration->registration_number }}</p>
                    
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Status:</span>
                            @if($registration->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Approved
                                </span>
                            @elseif($registration->status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Submitted:</span>
                            <span class="font-medium text-gray-900">{{ $registration->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        @if($registration->approved_at)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Processed:</span>
                            <span class="font-medium text-gray-900">{{ $registration->approved_at->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Processed By:</span>
                            <span class="font-medium text-gray-900">{{ $registration->approver->name ?? 'N/A' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Exam Information Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Exam Information</h4>
                <div class="space-y-2">
                    <div>
                        <p class="font-medium text-gray-900">{{ $registration->exam->title }}</p>
                        <p class="text-sm text-gray-600">{{ $registration->exam->description }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-600">Date:</span>
                            <p class="text-gray-900">{{ $registration->exam->exam_date->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Duration:</span>
                            <p class="text-gray-900">{{ $registration->exam->duration_minutes }} mins</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Marks:</span>
                            <p class="text-gray-900">{{ $registration->exam->total_marks }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Passing:</span>
                            <p class="text-gray-900">{{ $registration->exam->passing_marks }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    @if($registration->status === 'pending')
                    <form action="{{ route('admin.scholarship.registrations.approve', $registration) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i>Approve Registration
                        </button>
                    </form>
                    <button onclick="showRejectModal()" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>Reject Registration
                    </button>
                    @else
                    <form action="{{ route('admin.scholarship.registrations.pending', $registration) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-clock mr-2"></i>Set Pending
                        </button>
                    </form>
                    @endif
                    <a href="mailto:{{ $registration->student->user->email }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i>Email Student
                    </a>
                    <form action="{{ route('admin.scholarship.registrations.destroy', $registration) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this registration?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>Delete Registration
                        </button>
                    </form>
                </div>
            </div>

            <!-- Admin Notes Card -->
            @if($registration->admin_notes)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Admin Notes</h4>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-yellow-800 whitespace-pre-line">{{ $registration->admin_notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form action="{{ route('admin.scholarship.registrations.reject', $registration) }}" method="POST">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Registration</h3>
                    <p class="text-sm text-gray-600 mb-4">Please provide a reason for rejecting this registration:</p>
                    <textarea name="admin_notes" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Enter reason for rejection..." required></textarea>
                </div>
                <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                        Reject Registration
                    </button>
                </div>
            </form>
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
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target.id === 'rejectModal') {
        closeRejectModal();
    }
});
</script>
@endsection