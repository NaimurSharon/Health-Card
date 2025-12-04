@extends('layouts.teacher')

@section('title', 'Homework Management')

@section('content')
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <div class="content-card rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-2xl font-extrabold text-gray-900">Homework Management</h3>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <a href="{{ route('teacher.homework.create') }}"
                        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center justify-center rounded-lg shadow-md">
                        <i class="fas fa-plus mr-2"></i>Add Homework
                    </a>
                    <a href="{{ route('teacher.dashboard') }}"
                        class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center justify-center rounded-lg shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <div class="content-card rounded-xl p-4 sm:p-6 shadow-lg">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Filter Assignments</h4>
            <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Filter by Due Date</label>
                    <input type="date" name="date" id="date" value="{{ $filterDate }}"
                        class="w-full px-4 py-2 bg-white/90 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                </div>
                <div class="flex space-x-2 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-md">
                        <i class="fas fa-filter mr-1 md:mr-0"></i> <span class="md:hidden lg:inline">Filter</span>
                    </button>
                    <a href="{{ route('teacher.homework.index') }}"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-md flex items-center justify-center">
                        <i class="fas fa-redo-alt mr-1 md:mr-0"></i> <span class="md:hidden lg:inline">Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <div class="content-card rounded-xl shadow-lg overflow-hidden">
            <div class="table-header px-4 sm:px-6 py-4">
                <h4 class="text-xl font-bold text-gray-200">All Homework Assignments</h4>
            </div>

            @if($homeworks->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($homeworks as $homework)
                        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col lg:flex-row items-start lg:justify-between">
                                <div class="flex-1 min-w-0 mb-4 lg:mb-0 pr-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2">
                                        <h5 class="text-lg font-bold text-gray-900 truncate"
                                            title="{{ $homework->homework_title }}">{{ $homework->homework_title }}</h5>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 sm:mt-0 
                                                                                    {{ $homework->status == 'active' ? 'bg-green-100 text-green-800' :
                        ($homework->status == 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($homework->status) }}
                                        </span>
                                    </div>

                                    <p class="text-gray-600 mb-3 text-sm">{{ Str::limit($homework->homework_description, 150) }}</p>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-2 gap-x-4 text-xs text-gray-500 mt-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-users w-4 mr-2"></i>
                                            <span class="truncate">{{ $homework->class->name }} -
                                                {{ $homework->section->name }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-book w-4 mr-2"></i>
                                            <span class="truncate">{{ $homework->subject->name }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-day w-4 mr-2"></i>
                                            Assigned: <span
                                                class="font-medium ml-1">{{ $homework->entry_date->format('M j, Y') }}</span>
                                        </div>
                                        @if($homework->due_date)
                                            <div class="flex items-center">
                                                <i class="fas fa-clock w-4 mr-2"></i>
                                                Due: <span
                                                    class="font-medium ml-1 text-red-600">{{ $homework->due_date->format('M j, Y') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($homework->attachments && count($homework->attachments) > 0)
                                        <div class="mt-4 border-t pt-3 border-gray-100">
                                            <span class="text-sm font-medium text-gray-700 block mb-1">Attachments:</span>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($homework->attachments as $attachment)
                                                    <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank"
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 transition duration-150 ease-in-out shadow-sm">
                                                        <i class="fas fa-paperclip mr-1"></i>
                                                        {{ $attachment['name'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex space-x-2 w-full lg:w-auto justify-end lg:justify-start">
                                    <a href="{{ route('teacher.homework.edit', $homework->id) }}"
                                        class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50 transition-colors flex items-center justify-center"
                                        title="Edit Homework">
                                        <i class="fas fa-edit text-lg"></i>
                                    </a>
                                    <form action="{{ route('teacher.homework.destroy', $homework->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this homework? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors flex items-center justify-center"
                                            title="Delete Homework">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                    {{ $homeworks->links() }}
                </div>
            @else
                <div class="text-center py-12 px-4">
                    <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Homework Found</h3>
                    <p class="text-gray-600 mb-6">There are no homework assignments for the selected filters.</p>
                    <a href="{{ route('teacher.homework.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors inline-flex items-center shadow-md">
                        <i class="fas fa-plus mr-2"></i>Add Your First Homework
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .table-header {
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
        }
    </style>
@endsection