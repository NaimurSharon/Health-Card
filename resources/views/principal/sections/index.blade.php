@extends('layouts.principal')

@section('title', 'Manage Sections')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Section Management</h3>
                <p class="text-gray-600 mt-1">Manage all sections in your school</p>
            </div>
            <a href="{{ route('principal.sections.create') }}" 
               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i>Add New Section
            </a>
        </div>
    </div>

    <!-- Sections Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sections as $section)
        <div class="content-card rounded-lg p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-th-large text-indigo-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $section->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $section->class->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $section->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($section->status) }}
                </span>
            </div>
            
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Room:</span>
                    <span class="font-medium text-gray-900">{{ $section->room_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Students:</span>
                    <span class="font-medium text-gray-900">{{ $section->students_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Capacity:</span>
                    <span class="font-medium text-gray-900">{{ $section->capacity }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Class Teacher:</span>
                    <span class="font-medium text-gray-900">{{ $section->teacher->name ?? 'Not Assigned' }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <span class="text-sm text-gray-500">
                    {{ $section->students_count ?? 0 }}/{{ $section->capacity }}
                </span>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('principal.sections.edit', $section->id) }}" 
                       class="text-green-600 hover:text-green-900 p-1">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('principal.sections.destroy', $section->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 p-1" 
                                onclick="return confirm('Are you sure you want to delete this section?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full content-card rounded-lg p-8 text-center">
            <i class="fas fa-th-large text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No sections found</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($sections->hasPages())
    <div class="content-card rounded-lg p-4">
        {{ $sections->links() }}
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
</style>
@endsection