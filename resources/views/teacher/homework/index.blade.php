<<<<<<< HEAD
@extends('layouts.app')
=======
@extends('layouts.teacher')
>>>>>>> c356163 (video call ui setup)

@section('title', 'Homework Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Homework Management</h3>
            <div class="flex space-x-3">
                <a href="{{ route('teacher.homework.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Homework
                </a>
                <a href="{{ route('teacher.dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Filter by Date</label>
                <input type="date" name="date" id="date" value="{{ $filterDate }}"
                       class="w-full px-4 py-2 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                    Filter
                </button>
                <a href="{{ route('teacher.homework.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors ml-2">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Homework List -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <h4 class="text-lg font-semibold">Homework Assignments</h4>
        </div>

        @if($homeworks->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($homeworks as $homework)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h5 class="text-lg font-semibold text-gray-900">{{ $homework->homework_title }}</h5>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $homework->status == 'active' ? 'bg-green-100 text-green-800' : 
                                       ($homework->status == 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($homework->status) }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 mb-3">{{ $homework->homework_description }}</p>
                            
                            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ $homework->class->name }} - {{ $homework->section->name }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-book mr-2"></i>
                                    {{ $homework->subject->name }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    Assigned: {{ $homework->entry_date->format('M j, Y') }}
                                </div>
                                @if($homework->due_date)
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    Due: {{ $homework->due_date->format('M j, Y') }}
                                </div>
                                @endif
                            </div>

                            @if($homework->attachments && count($homework->attachments) > 0)
                            <div class="mt-3">
                                <span class="text-sm font-medium text-gray-700">Attachments:</span>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($homework->attachments as $attachment)
                                    <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700 hover:bg-gray-200">
                                        <i class="fas fa-paperclip mr-1"></i>
                                        {{ $attachment['name'] }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('teacher.homework.edit', $homework->id) }}" 
                               class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                               title="Edit Homework">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('teacher.homework.destroy', $homework->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this homework?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                        title="Delete Homework">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $homeworks->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Homework Found</h3>
                <p class="text-gray-600 mb-6">No homework assignments found for the selected date.</p>
                <a href="{{ route('teacher.homework.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Your First Homework
                </a>
            </div>
        @endif
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
@endsection