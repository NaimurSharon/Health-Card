@extends('layouts.principal')

@section('title', 'Manage Classes')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Class Management</h3>
                <p class="text-gray-600 mt-1">Manage all classes in your school</p>
            </div>
            <a href="{{ route('principal.classes.create') }}" 
               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i>Add New Class
            </a>
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $class)
        <div class="content-card rounded-lg p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-purple-600 font-semibold">{{ $class->numeric_value }}</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $class->name }}</h4>
                        <p class="text-sm text-gray-500">{{ ucfirst($class->shift) }} Shift</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $class->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($class->status) }}
                </span>
            </div>
            
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Sections:</span>
                    <span class="font-medium text-gray-900">{{ $class->sections_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Students:</span>
                    <span class="font-medium text-gray-900">{{ $class->students_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Capacity:</span>
                    <span class="font-medium text-gray-900">{{ $class->capacity }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <a href="{{ route('principal.classes.sections', $class->id) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View Sections
                </a>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('principal.classes.edit', $class->id) }}" 
                       class="text-green-600 hover:text-green-900 p-1">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('principal.classes.destroy', $class->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 p-1" 
                                onclick="return confirm('Are you sure you want to delete this class?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full content-card rounded-lg p-8 text-center">
            <i class="fas fa-door-open text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No classes found</p>
            <a href="{{ route('principal.classes.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-4">
                <i class="fas fa-plus mr-2"></i>Create First Class
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($classes->hasPages())
    <div class="content-card rounded-lg p-4">
        {{ $classes->links() }}
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
        .p-6 {
            padding: 1rem;
        }
        
        .space-y-3 > * + * {
            margin-top: 0.5rem;
        }
    }
</style>
@endsection