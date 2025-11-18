@extends('layouts.app')

@section('title', 'Patients Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Patients Management</h3>
            <div class="text-sm text-gray-600">
                <span class="font-medium">{{ $students->total() }}</span> total patients
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form action="{{ route('doctor.patients.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-3">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Patients</label>
                    <input type="text" name="search" id="search" 
                           value="{{ $search }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Search by student name, ID, or email...">
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('doctor.patients.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Patients Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $student)
            <div class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ $student->user->name }}</h4>
                        <p class="text-sm text-gray-600">ID: {{ $student->student_id }}</p>
                    </div>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Class:</span>
                        <span class="text-sm font-medium">{{ $student->class->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Section:</span>
                        <span class="text-sm font-medium">{{ $student->section->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Blood Group:</span>
                        <span class="text-sm font-medium {{ $student->blood_group ? 'text-red-600' : 'text-gray-500' }}">
                            {{ $student->blood_group ?? 'Not set' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Medical Records:</span>
                        <span class="text-sm font-medium">{{ $student->medical_records_count ?? 0 }}</span>
                    </div>
                </div>

                <!-- Allergies & Conditions -->
                @if($student->allergies || $student->medical_conditions)
                <div class="mb-4">
                    @if($student->allergies)
                    <div class="flex items-center space-x-2 mb-2">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-xs"></i>
                        <span class="text-xs text-gray-600 line-clamp-1">{{ $student->allergies }}</span>
                    </div>
                    @endif
                    @if($student->medical_conditions)
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-heartbeat text-red-500 text-xs"></i>
                        <span class="text-xs text-gray-600 line-clamp-1">{{ $student->medical_conditions }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-xs text-gray-500">
                        Admitted {{ $student->admission_date ? $student->admission_date->format('M Y') : 'N/A' }}
                    </span>
                    <a href="{{ route('doctor.patients.show', $student) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-colors flex items-center">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 content-card rounded-lg p-8 text-center">
                <i class="fas fa-user-injured text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg text-gray-500">No patients found</p>
                <p class="text-sm mt-2">No patients match your search criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="content-card rounded-lg p-6">
            {{ $students->links() }}
        </div>
    @endif
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

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth interactions
        const inputs = document.querySelectorAll('input');
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