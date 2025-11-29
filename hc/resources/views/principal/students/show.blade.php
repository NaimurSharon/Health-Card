@extends('layouts.principal')

@section('title', 'Student Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">Student Details</h3>
                    <p class="text-gray-200 mt-1">Complete information about the student</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <a href="{{ route('principal.students.edit', $student->id) }}" 
                       class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Edit Student
                    </a>
                    <a href="{{ route('principal.students.index') }}" 
                       class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Students
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="content-card rounded-lg p-4 sm:p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                    Personal Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Student ID</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->student_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $student->user->date_of_birth ? $student->user->date_of_birth->format('M j, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Gender</label>
                        <p class="mt-1 text-sm text-gray-900 capitalize">{{ $student->user->gender ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->user->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="content-card rounded-lg p-4 sm:p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-graduation-cap mr-2 text-green-600"></i>
                    Academic Information
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Class</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->class->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Section</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->section->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Roll Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->roll_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Admission Date</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $student->admission_date ? $student->admission_date->format('M j, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Parent Information -->
            <div class="content-card rounded-lg p-4 sm:p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-users mr-2 text-purple-600"></i>
                    Parent Information
                </h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Father's Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->father_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Mother's Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->mother_name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="content-card rounded-lg p-4 sm:p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-heartbeat mr-2 text-red-600"></i>
                    Medical Information
                </h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Blood Group</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->blood_group ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Allergies</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->allergies ?? 'None' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Medical Conditions</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->medical_conditions ?? 'None' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Emergency Contact</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $student->emergency_contact ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-4 sm:p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('principal.students.health-records', $student->id) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-heartbeat mr-2"></i>Health Records
                    </a>
                    <a href="{{ route('principal.id-cards.generate-student', $student->id) }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-id-card mr-2"></i>Generate ID Card
                    </a>
                    <button onclick="confirmDelete()"
                       class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>Delete Student
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-4 border w-11/12 md:max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Delete Student</h3>
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete {{ $student->user->name }}? This action cannot be undone.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 px-4 py-3">
                <button onclick="closeModal()"
                    class="w-full px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <form action="{{ route('principal.students.destroy', $student->id) }}" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

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

    @media (max-width: 640px) {
        .text-xl {
            font-size: 1.25rem;
        }
        
        .text-lg {
            font-size: 1.125rem;
        }
    }
</style>
@endsection