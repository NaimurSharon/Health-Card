@extends('layouts.principal')

@section('title', 'Manage Teachers')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div
                class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">Teacher Management</h3>
                    <p class="text-gray-200 mt-1">Manage all teachers in your school</p>
                </div>
                <a href="{{ route('principal.teachers.create') }}"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>Add New Teacher
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" placeholder="Search teachers by name or email..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <select
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Specializations</option>
                    <option value="math">Mathematics</option>
                    <option value="science">Science</option>
                    <option value="english">English</option>
                </select>
                <select
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Teachers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($teachers as $teacher)
                <div class="content-card rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $teacher->user->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $teacher->specialization ?? 'Teacher' }}</p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($teacher->status) }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope mr-2 w-4"></i>
                            <span class="truncate">{{ $teacher->user->email }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone mr-2 w-4"></i>
                            <span>{{ $teacher->user->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-graduation-cap mr-2 w-4"></i>
                            <span class="truncate">{{ $teacher->qualifications ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <span class="text-sm text-gray-500">
                            {{ $teacher->assignedClasses->count() ?? 0 }} Classes
                        </span>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('principal.teachers.show', $teacher->id) }}"
                                class="text-blue-600 hover:text-blue-900 p-1">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('principal.teachers.edit', $teacher->id) }}"
                                class="text-green-600 hover:text-green-900 p-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('principal.teachers.destroy', $teacher->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1"
                                    onclick="return confirm('Are you sure you want to delete this teacher?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full content-card rounded-lg p-8 text-center">
                    <i class="fas fa-chalkboard-teacher text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No teachers found</p>
                    <a href="{{ route('principal.teachers.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-4">
                        <i class="fas fa-plus mr-2"></i>Add First Teacher
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($teachers->hasPages())
            <div class="content-card rounded-lg p-4">
                {{ $teachers->links() }}
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

        @media (max-width: 640px) {
            .text-sm {
                font-size: 0.75rem;
            }

            .p-6 {
                padding: 1rem;
            }
        }
    </style>
@endsection