@extends('layouts.app')

@section('title', 'City Corporation Notices Management')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold">City Corporation Notices Management</h3>
                    <p class="text-blue-100 text-sm">Manage notices for targeted schools</p>
                </div>
                <a href="{{ route('admin.city-corporation-notices.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 sm:px-5 sm:py-3 text-sm font-medium transition-colors flex items-center justify-center w-full sm:w-auto">
                    <i class="fas fa-plus mr-2"></i>Add New Notice
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <form action="{{ route('admin.city-corporation-notices.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 sm:px-4 sm:py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Search by title...">
                </div>

                <!-- Priority Filter -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select name="priority" id="priority" 
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Priorities</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2 sm:col-span-2 lg:col-span-1">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 sm:px-6 sm:py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.city-corporation-notices.index') }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 sm:px-6 sm:py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Notices Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200/60">
                        <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Notice</th>
                        <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Target</th>
                        <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Audience</th>
                        <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Expiry</th>
                        <th class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 sm:px-6 sm:py-4 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($notices as $notice)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-city text-blue-600 text-sm sm:text-base"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $notice->title }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500 line-clamp-1">{{ Str::limit($notice->content, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 capitalize">{{ $notice->target_type }}</div>
                                @if($notice->target_type === 'specific_schools' && $notice->target_schools)
                                    <div class="text-xs sm:text-sm text-gray-500">{{ count($notice->target_schools) }} schools</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 sm:px-6 sm:py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($notice->target_roles as $role)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $role == 'student' ? 'bg-green-100 text-green-800' : 
                                               ($role == 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                            {{ ucfirst($role) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $notice->expiry_date->format('M j, Y') }}</div>
                                <div class="text-xs sm:text-sm text-gray-500 {{ $notice->expiry_date->isPast() ? 'text-red-600' : '' }}">
                                    {{ $notice->expiry_date->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $notice->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($notice->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.city-corporation-notices.show', $notice) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors p-1" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.city-corporation-notices.edit', $notice) }}" 
                                       class="text-green-600 hover:text-green-900 transition-colors p-1" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.city-corporation-notices.destroy', $notice) }}" method="POST" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors p-1" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 sm:px-6 text-center text-gray-500">
                                <i class="fas fa-city text-3xl sm:text-4xl mb-3 sm:mb-4 text-gray-300"></i>
                                <p class="text-base sm:text-lg">No notices found</p>
                                <p class="text-xs sm:text-sm mt-2">Get started by creating a new notice.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden space-y-3 p-4">
            @forelse($notices as $notice)
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-city text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-semibold text-gray-900">{{ $notice->title }}</div>
                            <div class="text-xs text-gray-500 capitalize">{{ $notice->target_type }}</div>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        {{ $notice->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($notice->status) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($notice->content, 80) }}</p>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                    <div class="flex items-center space-x-2">
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $notice->expiry_date->format('M j, Y') }}
                        </span>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                        {{ $notice->priority == 'high' ? 'bg-red-100 text-red-800' : 
                           ($notice->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($notice->priority) }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <div class="flex flex-wrap gap-1">
                        @foreach($notice->target_roles as $role)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $role == 'student' ? 'bg-green-100 text-green-800' : 
                                   ($role == 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ ucfirst($role) }}
                            </span>
                        @endforeach
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.city-corporation-notices.show', $notice) }}" 
                           class="text-blue-600 hover:text-blue-900 p-1">
                            <i class="fas fa-eye text-sm"></i>
                        </a>
                        <a href="{{ route('admin.city-corporation-notices.edit', $notice) }}" 
                           class="text-green-600 hover:text-green-900 p-1">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <i class="fas fa-city text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg text-gray-500 mb-2">No notices found</p>
                <p class="text-sm text-gray-400">Get started by creating a new notice.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notices->hasPages())
            <div class="px-4 py-4 sm:px-6 border-t border-gray-200/60">
                {{ $notices->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .content-card {
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth interactions
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });
    });
</script>
@endsection