@extends('layouts.app')

@section('title', 'Classes')

@section('content')
<div class="space-y-6">
    <!-- Filters Section -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.classes.index') }}">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Shift Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                    <select name="shift" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Shifts</option>
                        <option value="morning" {{ request('shift') == 'morning' ? 'selected' : '' }}>Morning</option>
                        <option value="day" {{ request('shift') == 'day' ? 'selected' : '' }}>Day</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <!-- Go Button -->
                <div class="self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Go
                    </button>
                    <a href="{{ route('admin.classes.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Classes Table -->
    <div class="content-card overflow-hidden">
        <!-- Table Header -->
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Class List</h3>
            <a href="{{ route('admin.classes.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Class
            </a>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full transparent-table">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Class Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Numeric Value
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Shift
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Capacity
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Sections
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Students
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/40">
                    @forelse($classes as $class)
                    <tr class="hover:bg-white/60 transition-all duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:from-blue-200 group-hover:to-purple-200 transition-all duration-200">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($class->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $class->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Class {{ $class->numeric_value }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full text-center">
                                {{ $class->numeric_value }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full text-center capitalize">
                                {{ $class->shift }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full text-center">
                                {{ $class->capacity }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full text-center">
                                {{ $class->sections_count }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full text-center">
                                {{ $class->students_count ?? 0 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $class->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($class->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.classes.edit', $class) }}" 
                                   class="text-green-600 hover:text-green-800 bg-white/70 hover:bg-green-50 p-2 rounded-lg transition-all duration-200"
                                   title="Edit Class">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <a href="{{ route('admin.classes.show', $class) }}" 
                                   class="text-blue-600 hover:text-blue-800 bg-white/70 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 bg-white/70 hover:bg-red-50 p-2 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this class?')"
                                            title="Delete Class">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center">
                            <div class="text-gray-500 text-lg">
                                <i class="fas fa-chalkboard-teacher text-4xl mb-4 opacity-50"></i>
                                <p>No classes found.</p>
                                <a href="{{ route('admin.classes.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Add your first class
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($classes->hasPages())
        <div class="table-header px-6 py-4 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $classes->firstItem() }} to {{ $classes->lastItem() }} of {{ $classes->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $classes->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .transparent-table {
        background: transparent;
        backdrop-filter: blur(10px);
    }
    
    .transparent-table thead tr {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        backdrop-filter: blur(10px);
    }
    
    .transparent-table tbody tr {
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
    }
    
    .transparent-table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .content-card {
        background: transparent;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection