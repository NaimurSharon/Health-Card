@extends('layouts.app')

@section('title', 'Doctors Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Doctors Management</h3>
            <a href="{{ route('admin.doctors.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>Add New Doctor
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form action="{{ route('admin.doctors.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Search by name, email, specialization...">
                </div>

                <!-- Hospital Filter -->
                <div>
                    <label for="hospital_id" class="block text-sm font-medium text-gray-700 mb-2">Hospital</label>
                    <select name="hospital_id" id="hospital_id" 
                            class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Hospitals</option>
                        @foreach($hospitals as $hospital)
                            <option value="{{ $hospital->id }}" {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                {{ $hospital->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.doctors.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Doctors Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Specialization</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Hospital</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($doctors as $doctor)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-md text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $doctor->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $doctor->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $doctor->phone }}</div>
                                <div class="text-sm text-gray-500">{{ $doctor->gender }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $doctor->specialization }}</div>
                                <div class="text-sm text-gray-500">{{ $doctor->qualifications }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $doctor->hospital->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $doctor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($doctor->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.doctors.show', $doctor) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}" 
                                       class="text-green-600 hover:text-green-900 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this doctor?');">
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
                                <i class="fas fa-user-md text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No doctors found</p>
                                <p class="text-sm mt-2">Get started by creating a new doctor.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($doctors->hasPages())
            <div class="px-6 py-4 border-t border-gray-200/60">
                {{ $doctors->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .content-card {
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