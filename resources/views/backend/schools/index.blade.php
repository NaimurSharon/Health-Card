@extends('layouts.app')

@section('title', 'Schools')

@section('content')
<div class="space-y-6">
    <!-- Filters Section -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.schools.index') }}">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Types</option>
                        <option value="government" {{ request('type') == 'government' ? 'selected' : '' }}>Government</option>
                        <option value="private" {{ request('type') == 'private' ? 'selected' : '' }}>Private</option>
                        <option value="madrasa" {{ request('type') == 'madrasa' ? 'selected' : '' }}>Madrasa</option>
                        <option value="international" {{ request('type') == 'international' ? 'selected' : '' }}>International</option>
                    </select>
                </div>
                
                <!-- Search Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name or code..." 
                           class="w-64 border-gray-300 rounded-lg text-sm bg-white/90 px-3 py-2">
                </div>
                
                <!-- Go Button -->
                <div class="self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Go
                    </button>
                    <a href="{{ route('admin.schools.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Schools Table -->
    <div class="content-card overflow-hidden">
        <!-- Table Header -->
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Schools</h3>
            <a href="{{ route('admin.schools.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Add School
            </a>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full transparent-table">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            School
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Code & Type
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Location
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Statistics
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
                    @forelse($schools as $school)
                    <tr class="hover:bg-white/60 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    @if($school->logo)
                                        <img src="{{ asset('public/storage/' . $school->logo) }}" alt="{{ $school->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:from-blue-200 group-hover:to-purple-200 transition-all duration-200">
                                            <i class="fas fa-school text-blue-600 text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $school->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $school->principal_name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 bg-white/50 px-3 py-1 rounded-full inline-block mb-1">
                                {{ $school->code }}
                            </div>
                            <div class="text-xs text-gray-500 capitalize">
                                {{ $school->type }}
                            </div>
                            @if($school->established_year)
                            <div class="text-xs text-gray-500">
                                Est. {{ $school->established_year }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $school->phone }}</div>
                            <div class="text-sm text-gray-500">{{ $school->email }}</div>
                            @if($school->website)
                            <div class="text-xs text-blue-600 truncate max-w-xs">
                                <a href="{{ $school->website }}" target="_blank" class="hover:underline">
                                    {{ $school->website }}
                                </a>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $school->city }}</div>
                            <div class="text-sm text-gray-500">{{ $school->district }}</div>
                            <div class="text-xs text-gray-400">{{ $school->division }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-2 text-xs">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                        {{ $school->users_count ?? 0 }} Users
                                    </span>
                                    <span class="bg-green-50 text-green-700 px-2 py-1 rounded">
                                        {{ $school->total_students ?? 0 }} Students
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2 text-xs">
                                    <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded">
                                        {{ $school->total_teachers ?? 0 }} Teachers
                                    </span>
                                    <span class="bg-orange-50 text-orange-700 px-2 py-1 rounded">
                                        {{ $school->total_staff ?? 0 }} Staff
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $school->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($school->status) }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1 capitalize">
                                {{ $school->academic_system ?? 'National' }}
                            </div>
                            <div class="text-xs text-gray-500 capitalize">
                                {{ $school->medium ?? 'Bangla' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.schools.show', $school) }}" 
                                   class="text-blue-600 hover:text-blue-800 bg-white/70 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.schools.edit', $school) }}" 
                                   class="text-green-600 hover:text-green-800 bg-white/70 hover:bg-green-50 p-2 rounded-lg transition-all duration-200"
                                   title="Edit School">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admin.schools.destroy', $school) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 bg-white/70 hover:bg-red-50 p-2 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this school?')"
                                            title="Delete School">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="text-xs text-gray-500 mt-2">
                                {{ $school->created_at->format('M d, Y') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-500 text-lg">
                                <i class="fas fa-school text-4xl mb-4 opacity-50"></i>
                                <p>No schools found.</p>
                                <a href="{{ route('admin.schools.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Add your first school
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($schools->hasPages())
        <div class="table-header px-6 py-4 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $schools->firstItem() }} to {{ $schools->lastItem() }} of {{ $schools->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $schools->links() }}
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

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>
@endsection