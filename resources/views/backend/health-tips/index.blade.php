@extends('layouts.app')

@section('title', 'Health Tips')

@section('content')
<div class="space-y-6">
    <!-- Filters Section -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.health-tips.index') }}">
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
                
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Categories</option>
                        <option value="nutrition" {{ request('category') == 'nutrition' ? 'selected' : '' }}>Nutrition</option>
                        <option value="exercise" {{ request('category') == 'exercise' ? 'selected' : '' }}>Exercise</option>
                        <option value="mental_health" {{ request('category') == 'mental_health' ? 'selected' : '' }}>Mental Health</option>
                        <option value="hygiene" {{ request('category') == 'hygiene' ? 'selected' : '' }}>Hygiene</option>
                        <option value="prevention" {{ request('category') == 'prevention' ? 'selected' : '' }}>Prevention</option>
                        <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
                
                <!-- Target Audience Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                    <select name="target_audience" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Audiences</option>
                        <option value="students" {{ request('target_audience') == 'students' ? 'selected' : '' }}>Students</option>
                        <option value="teachers" {{ request('target_audience') == 'teachers' ? 'selected' : '' }}>Teachers</option>
                        <option value="parents" {{ request('target_audience') == 'parents' ? 'selected' : '' }}>Parents</option>
                        <option value="all" {{ request('target_audience') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </div>
                
                <!-- Go Button -->
                <div class="self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Go
                    </button>
                    <a href="{{ route('admin.health-tips.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Health Tips Table -->
    <div class="content-card overflow-hidden">
        <!-- Table Header -->
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Health Tips</h3>
            <a href="{{ route('admin.health-tips.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Health Tip
            </a>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full transparent-table">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Title
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Target Audience
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Created By
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Created Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/40">
                    @forelse($healthTips as $tip)
                    <tr class="hover:bg-white/60 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center group-hover:from-green-200 group-hover:to-blue-200 transition-all duration-200">
                                        <i class="fas fa-heart text-green-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $tip->title }}
                                    </div>
                                    <div class="text-sm text-gray-500 max-w-md truncate">
                                        {{ Str::limit(strip_tags($tip->content), 80) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 bg-white/50 px-3 py-1 rounded-full text-center capitalize">
                                {{ str_replace('_', ' ', $tip->category) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $audienceColors = [
                                    'students' => 'bg-blue-100 text-blue-800',
                                    'teachers' => 'bg-purple-100 text-purple-800',
                                    'parents' => 'bg-orange-100 text-orange-800',
                                    'all' => 'bg-green-100 text-green-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $audienceColors[$tip->target_audience] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($tip->target_audience) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $tip->createdBy->name ?? 'System' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $tip->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($tip->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($tip->created_at)->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.health-tips.show', $tip) }}" 
                                   class="text-blue-600 hover:text-blue-800 bg-white/70 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.health-tips.edit', $tip) }}" 
                                   class="text-green-600 hover:text-green-800 bg-white/70 hover:bg-green-50 p-2 rounded-lg transition-all duration-200"
                                   title="Edit Health Tip">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admin.health-tips.destroy', $tip) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 bg-white/70 hover:bg-red-50 p-2 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this health tip?')"
                                            title="Delete Health Tip">
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
                                <i class="fas fa-heart text-4xl mb-4 opacity-50"></i>
                                <p>No health tips found.</p>
                                <a href="{{ route('admin.health-tips.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Create your first health tip
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($healthTips->hasPages())
        <div class="table-header px-6 py-4 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $healthTips->firstItem() }} to {{ $healthTips->lastItem() }} of {{ $healthTips->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $healthTips->links() }}
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