@extends('layouts.app')

@section('title', 'Doctors Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 md:px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl md:text-2xl font-bold">Doctors Management</h3>
            <a href="{{ route('admin.doctors.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-5 py-2.5 md:py-3 text-sm font-medium transition-colors flex items-center rounded-lg">
                <i class="fas fa-plus mr-2"></i>Add New Doctor
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-4 md:p-6 shadow-sm">
        <form action="{{ route('admin.doctors.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 md:px-4 py-2.5 md:py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                           placeholder="Search by name, email, specialization...">
                </div>

                <!-- Hospital Filter -->
                <div>
                    <label for="hospital_id" class="block text-sm font-medium text-gray-700 mb-2">Hospital</label>
                    <select name="hospital_id" id="hospital_id" 
                            class="w-full px-3 md:px-4 py-2.5 md:py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
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
                            class="w-full px-3 md:px-4 py-2.5 md:py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.doctors.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>

            </div>
        </form>
    </div>

    <!-- Desktop + Laptop Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="border-b border-gray-200/60 bg-gray-50/50">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase">Specialization</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase">Hospital</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-900 uppercase">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200/60">
                    @foreach($doctors as $doctor)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($doctor->profile_image)
                                        <img src="{{ asset('public/storage/' . $doctor->profile_image) }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-md text-blue-600"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <p class="font-medium text-gray-900">{{ $doctor->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $doctor->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $doctor->phone }}</p>
                                <p class="text-sm text-gray-500">{{ $doctor->gender }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $doctor->specialization }}</p>
                                <p class="text-sm text-gray-500">{{ $doctor->qualifications }}</p>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $doctor->hospital->name ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $doctor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($doctor->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-3 text-lg">
                                    <a href="{{ route('admin.doctors.show', $doctor) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4 p-4">
            @foreach($doctors as $doctor)
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow">

                    <div class="flex items-center space-x-3">
                        @if($doctor->profile_image)
                            <img src="{{ asset('public/storage/' . $doctor->profile_image) }}" class="h-12 w-12 rounded-full object-cover">
                        @else
                            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-md text-blue-600 text-xl"></i>
                            </div>
                        @endif

                        <div>
                            <p class="font-semibold text-gray-900 text-lg">{{ $doctor->name }}</p>
                            <p class="text-sm text-gray-500">{{ $doctor->email }}</p>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1 text-sm">
                        <p><strong>Phone:</strong> {{ $doctor->phone }}</p>
                        <p><strong>Gender:</strong> {{ $doctor->gender }}</p>
                        <p><strong>Specialization:</strong> {{ $doctor->specialization }}</p>
                        <p><strong>Hospital:</strong> {{ $doctor->hospital->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mt-3 flex justify-between items-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                            {{ $doctor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($doctor->status) }}
                        </span>

                        <div class="flex space-x-3 text-lg">
                            <a href="{{ route('admin.doctors.show', $doctor) }}" class="text-blue-600">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="text-green-600">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST"
                                  onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($doctors->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex justify-center">
                    {{ $doctors->links() }}
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    .content-card {
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
</style>
@endsection
