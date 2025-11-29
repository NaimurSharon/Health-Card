@extends('layouts.app')

@section('title', 'Scholarship Registrations Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Scholarship Registrations Management</h3>
            <div class="flex items-center space-x-3">
                @php
                    $pendingCount = \App\Models\ScholarshipRegistration::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ $pendingCount }} Pending
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form action="{{ route('admin.scholarship.registrations') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Search by name, email, registration number...">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Exam Filter -->
                <div>
                    <label for="exam_id" class="block text-sm font-medium text-gray-700 mb-2">Exam</label>
                    <select name="exam_id" id="exam_id" 
                            class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Exams</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.scholarship.registrations') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Registrations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times text-red-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Exam & Registration</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Academic Info</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($registrations as $registration)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-graduate text-purple-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration->student->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $registration->exam->title }}</div>
                                <div class="text-sm text-gray-500">{{ $registration->registration_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    Class {{ $registration->student->class->numeric_value ?? 'N/A' }} - 
                                    {{ $registration->student->section->name ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">Roll: {{ $registration->student->roll_number ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($registration->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Approved
                                    </span>
                                @elseif($registration->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $registration->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $registration->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.scholarship.registrations.show', $registration) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($registration->status === 'pending')
                                    <form action="{{ route('admin.scholarship.registrations.approve', $registration) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 transition-colors" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button onclick="showRejectModal({{ $registration->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @else
                                    <form action="{{ route('admin.scholarship.registrations.pending', $registration) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Set Pending">
                                            <i class="fas fa-clock"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.scholarship.registrations.destroy', $registration) }}" method="POST" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this registration?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-user-graduate text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No scholarship registrations found</p>
                                <p class="text-sm mt-2">No students have registered for scholarships yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200/60">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form id="rejectForm" method="POST">
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
function showRejectModal(registrationId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/admin/scholarship-registrations/${registrationId}/reject`;
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target.id === 'rejectModal') {
        closeRejectModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Add smooth interactions
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
        });
    });
});
</script>
@endsection