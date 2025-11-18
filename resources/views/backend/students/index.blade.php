@extends('layouts.app')

@section('title', 'Students')

@section('content')
<div class="space-y-6">
    <!-- Filters Section -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.students.index') }}">
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
                
                <!-- Class Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                    <select name="class_id" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Section Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                    <select name="section_id" class="w-40 border-gray-300 rounded-lg text-sm">
                        <option value="">All Sections</option>
                    </select>
                </div>
                
                <!-- Department Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option>All Departments</option>
                        <option selected>--</option>
                    </select>
                </div>
                
                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                </div>
                
                <!-- Go Button -->
                <div class="self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Go
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="content-card overflow-hidden">
        <!-- Table Header -->
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Student List</h3>
            <a href="{{ route('admin.students.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Student
            </a>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full transparent-table">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Unique Id
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Student Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Class
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Section
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Department
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Mobile
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/40">
                    @forelse($students as $student)
                    <tr class="hover:bg-white/60 transition-all duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 bg-white/50 px-3 py-1 rounded-full inline-block">
                                {{ $student->student_id }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:from-blue-200 group-hover:to-purple-200 transition-all duration-200">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($student->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $student->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $student->user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full text-center">
                                {{ $student->class->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full text-center">
                                {{ $student->section->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 bg-white/30 px-3 py-1 rounded-full text-center">
                                --
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full">
                                {{ $student->user->phone ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.students.edit', $student) }}" 
                                   class="text-green-600 hover:text-green-800 bg-white/70 hover:bg-green-50 p-2 rounded-lg transition-all duration-200"
                                   title="Edit Student">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <a href="{{ route('admin.students.show', $student) }}" 
                                   class="text-blue-600 hover:text-blue-800 bg-white/70 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 bg-white/70 hover:bg-red-50 p-2 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this student?')"
                                            title="Delete Student">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-500 text-lg">
                                <i class="fas fa-users text-4xl mb-4 opacity-50"></i>
                                <p>No students found.</p>
                                <a href="{{ route('admin.students.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Add your first student
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($students->hasPages())
        <div class="table-header px-6 py-4 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $students->links() }}
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