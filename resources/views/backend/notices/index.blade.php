@extends('layouts.app')

@section('title', 'Notices')

@section('content')
<div class="space-y-6">
    <!-- Filters Section -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.notices.index') }}">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                
                <!-- Priority Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Priorities</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                
                <!-- Target Roles Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                    <select name="target_role" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Audiences</option>
                        <option value="students" {{ request('target_role') == 'students' ? 'selected' : '' }}>Students</option>
                        <option value="teachers" {{ request('target_role') == 'teachers' ? 'selected' : '' }}>Teachers</option>
                        <option value="parents" {{ request('target_role') == 'parents' ? 'selected' : '' }}>Parents</option>
                        <option value="medical_staff" {{ request('target_role') == 'medical_staff' ? 'selected' : '' }}>Medical Staff</option>
                    </select>
                </div>
                
                <!-- Expiry Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ request('expiry_date') }}" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                </div>
                
                <!-- Go Button -->
                <div class="self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Go
                    </button>
                    <a href="{{ route('admin.notices.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Notices Table -->
    <div class="content-card overflow-hidden">
        <!-- Table Header -->
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Notices</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.notices.diary') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-book mr-2"></i>View Diary
                </a>
                <a href="{{ route('admin.notices.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Notice
                </a>
            </div>
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
                            Priority
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Target Audience
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Published By
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Expiry Date
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
                    @forelse($notices as $notice)
                    <tr class="hover:bg-white/60 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:from-blue-200 group-hover:to-purple-200 transition-all duration-200">
                                        <i class="fas fa-bullhorn text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $notice->title }}
                                    </div>
                                    <div class="text-sm text-gray-500 max-w-md truncate">
                                        {{ Str::limit(strip_tags($notice->content), 80) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $priorityColors = [
                                    'high' => 'bg-red-100 text-red-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'low' => 'bg-green-100 text-green-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $priorityColors[$notice->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($notice->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($notice->target_roles as $role)
                                    @php
                                        $roleColors = [
                                            'students' => 'bg-blue-100 text-blue-800',
                                            'teachers' => 'bg-purple-100 text-purple-800',
                                            'parents' => 'bg-orange-100 text-orange-800',
                                            'medical_staff' => 'bg-green-100 text-green-800'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleColors[$role] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $notice->publishedBy->name ?? 'System' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 {{ $notice->expiry_date->isPast() ? 'text-red-600 font-semibold' : '' }}">
                                {{ $notice->expiry_date->format('M d, Y') }}
                                @if($notice->expiry_date->isPast())
                                    <span class="text-xs text-red-500">(Expired)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $notice->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($notice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.notices.show', $notice) }}" 
                                   class="text-blue-600 hover:text-blue-800 bg-white/70 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.notices.edit', $notice) }}" 
                                   class="text-green-600 hover:text-green-800 bg-white/70 hover:bg-green-50 p-2 rounded-lg transition-all duration-200"
                                   title="Edit Notice">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 bg-white/70 hover:bg-red-50 p-2 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this notice?')"
                                            title="Delete Notice">
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
                                <i class="fas fa-bullhorn text-4xl mb-4 opacity-50"></i>
                                <p>No notices found.</p>
                                <a href="{{ route('admin.notices.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Create your first notice
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($notices->hasPages())
        <div class="table-header px-6 py-4 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $notices->firstItem() }} to {{ $notices->lastItem() }} of {{ $notices->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $notices->links() }}
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