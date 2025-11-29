@extends('layouts.principal')

@section('title', 'Homework Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Homework Management</h3>
                <p class="text-gray-600 mt-1">Manage all homework assignments</p>
            </div>
            <a href="{{ route('principal.homework.create') }}" 
               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i>Add Homework
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <form action="{{ route('principal.homework.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search homework..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <select name="class_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>

            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>


    <!-- Homework List -->
    <div class="space-y-4">
        @forelse($homeworks as $homework)
        <div class="content-card rounded-lg p-4 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <!-- Homework Info -->
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $homework->homework_title }}</h4>
                            <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-door-open mr-1"></i>
                                    {{ $homework->class->name ?? 'N/A' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-th-large mr-1"></i>
                                    Section {{ $homework->section->name ?? 'N/A' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-book mr-1"></i>
                                    {{ $homework->subject->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $homework->status == 'active' ? 'bg-green-100 text-green-800' : 
                                   ($homework->status == 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($homework->status) }}
                            </span>
                        </div>
                    </div>

                    <p class="text-gray-700 mb-4">{{ $homework->homework_description }}</p>

                    <!-- Homework Meta -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-user-tie mr-2 w-4"></i>
                            <span>By: {{ $homework->teacher->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar-day mr-2 w-4"></i>
                            <span>Assigned: {{ $homework->entry_date->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock mr-2 w-4"></i>
                            <span>Due: {{ $homework->due_date ? $homework->due_date->format('M j, Y') : 'No due date' }}</span>
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($homework->attachments)
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Attachments:</h5>
                        <div class="flex flex-wrap gap-2">
                            @foreach($homework->attachments as $index => $attachment)
                            <a href="{{ Storage::url($attachment) }}" target="_blank" 
                               class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-full text-xs text-gray-700 hover:bg-gray-200">
                                <i class="fas fa-paperclip mr-1"></i>
                                File {{ $index + 1 }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end lg:justify-start space-x-2 lg:flex-col lg:space-x-0 lg:space-y-2">
                    <a href="{{ route('principal.homework.edit', $homework->id) }}" 
                       class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-edit text-sm"></i>
                        <span class="hidden lg:inline ml-2">Edit</span>
                    </a>
                    <form action="{{ route('principal.homework.destroy', $homework->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                onclick="return confirm('Are you sure you want to delete this homework?')">
                            <i class="fas fa-trash text-sm"></i>
                            <span class="hidden lg:inline ml-2">Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="content-card rounded-lg p-8 text-center">
            <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No homework assignments found</p>
            <a href="{{ route('principal.homework.create') }}" 
               class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-800">
                <i class="fas fa-plus mr-2"></i>Create your first homework
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($homeworks->hasPages())
    <div class="content-card rounded-lg p-4">
        {{ $homeworks->links() }}
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