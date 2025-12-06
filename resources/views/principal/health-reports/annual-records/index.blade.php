@extends('layouts.principal')

@section('title', 'Annual Health Records')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div
                class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">Annual Health Records</h3>
                    <p class="text-gray-200 mt-1">Track student growth and development by age</p>
                </div>
                <a href="{{ route('principal.health.annual-records.create') }}"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>Add Record
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <form action="{{ route('principal.health.annual-records.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <select name="class_id"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                <input type="number" name="age" value="{{ request('age') }}" placeholder="Age" min="3" max="18"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="md:col-span-4 flex justify-end gap-3">
                    <button type="submit"
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('principal.health.annual-records.index') }}"
                        class="w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Annual Records List -->
        <div class="space-y-4">
            @forelse($records as $record)
                <div class="content-card rounded-lg p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <!-- Record Info -->
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ $record->student->user->name ?? 'N/A' }}
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <i class="fas fa-door-open mr-1"></i>
                                            {{ $record->student->class->name ?? 'N/A' }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-child mr-1"></i>
                                            Age: {{ $record->age }} years
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar-day mr-1"></i>
                                            {{ $record->created_at->format('M j, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                                {{ $record->general_health == 'good' ? 'bg-green-100 text-green-800' :
                ($record->general_health == 'fair' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($record->general_health) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Measurements -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                        <i class="fas fa-weight text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $record->weight }} kg</div>
                                        <div class="text-xs text-gray-500">Weight</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                                        <i class="fas fa-ruler-vertical text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $record->height }} cm</div>
                                        <div class="text-xs text-gray-500">Height</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                        <i class="fas fa-brain text-purple-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $record->head_circumference ?? 'N/A' }} cm
                                        </div>
                                        <div class="text-xs text-gray-500">Head Circumference</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="space-y-2 text-sm">
                                @if($record->development_notes)
                                    <div class="text-gray-700">
                                        <span class="font-medium">Development:</span>
                                        {{ Str::limit($record->development_notes, 100) }}
                                    </div>
                                @endif
                                @if($record->vaccination_status)
                                    <div class="text-gray-700">
                                        <span class="font-medium">Vaccination:</span>
                                        {{ Str::limit($record->vaccination_status, 100) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex items-center justify-end lg:justify-start space-x-2 lg:flex-col lg:space-x-0 lg:space-y-2">
                            <a href="{{ route('principal.health.annual-records.edit', $record) }}"
                                class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                                <span class="hidden lg:inline ml-2">Edit</span>
                            </a>
                            <form action="{{ route('principal.health.annual-records.destroy', $record) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this annual record?')">
                                    <i class="fas fa-trash text-sm"></i>
                                    <span class="hidden lg:inline ml-2">Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="content-card rounded-lg p-8 text-center">
                    <i class="fas fa-heartbeat text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No annual health records found</p>
                    <a href="{{ route('principal.health.annual-records.create') }}"
                        class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-2"></i>Add your first record
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($records->hasPages())
            <div class="content-card rounded-lg p-4">
                {{ $records->links() }}
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