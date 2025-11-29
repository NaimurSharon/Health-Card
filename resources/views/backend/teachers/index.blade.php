@extends('layouts.app')

@section('title', 'Teachers')

@section('content')
<div class="space-y-6">
    <!-- Teachers Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Teachers</h3>
            <a href="{{ route('admin.teachers.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>Add Teacher
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form action="{{ route('admin.teachers.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by name, email, or phone..."
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
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
                <div class="flex items-end space-x-3">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('admin.teachers.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Teachers Table -->
    <div class="content-card rounded-lg shadow-sm">
        <div class="table-header px-6 py-4">
            <h4 class="text-xl font-semibold">Teachers List</h4>
        </div>
        <div class="p-6">
            @if($teachers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200/60">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Teacher</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Contact</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Classes</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/60">
                        @foreach($teachers as $teacher)
                        <tr class="transition-colors duration-200">
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-graduate text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $teacher->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $teacher->qualification ?? 'No qualification' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm text-gray-900">{{ $teacher->email }}</p>
                                <p class="text-xs text-gray-500">{{ $teacher->phone ?? 'No phone' }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-900">{{ $teacher->classes_count }} classes</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.teachers.show', $teacher) }}" 
                                       class="text-blue-600 hover:text-blue-700 p-2 transition-colors duration-200"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                                       class="text-green-600 hover:text-green-700 p-2 transition-colors duration-200"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-700 p-2 transition-colors duration-200"
                                                title="Delete">
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

            <!-- Pagination -->
            <div class="mt-6">
                {{ $teachers->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-user-graduate text-4xl text-gray-400 mb-4"></i>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No teachers found</h4>
                <p class="text-gray-500 mb-6">Get started by adding your first teacher.</p>
                <a href="{{ route('admin.teachers.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Teacher
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .content-card {
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>
@endsection