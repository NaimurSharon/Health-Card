@extends('layouts.app')

@section('title', 'Notices - Home Page')

@section('content')
<div class="space-y-6">
    <!-- Homepage Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-home text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Home Page Notices</h3>
                    <p class="text-gray-200">Active notices displayed on the homepage</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.notices.diary') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-book mr-2"></i>View Diary
                </a>
                <a href="{{ route('admin.notices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-list mr-2"></i>All Notices
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="content-card rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $notices->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">High Priority</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $notices->where('priority', 'high')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">This Week</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $notices->where('created_at', '>=', now()->subWeek())->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-week text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Expiring Soon</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $notices->where('expiry_date', '<=', now()->addWeek())->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Notices -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <h4 class="text-xl font-semibold">Active Notices for Homepage</h4>
            <p class="text-sm text-gray-200 mt-1">These notices are currently visible on the homepage</p>
        </div>
        
        <div class="p-6">
            @forelse($notices as $notice)
            <div class="border border-gray-200 rounded-lg p-6 mb-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            @php
                                $priorityIcons = [
                                    'high' => 'fas fa-exclamation-circle text-red-500 text-2xl',
                                    'medium' => 'fas fa-info-circle text-yellow-500 text-2xl',
                                    'low' => 'fas fa-check-circle text-green-500 text-2xl'
                                ];
                            @endphp
                            <i class="{{ $priorityIcons[$notice->priority] ?? 'fas fa-bullhorn text-blue-500 text-2xl' }}"></i>
                        </div>
                        <div class="flex-1">
                            <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ $notice->title }}</h5>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $notice->publishedBy->name ?? 'System' }}
                                </span>
                                <span>
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($notice->created_at)->format('M d, Y') }}
                                </span>
                                <span>
                                    <i class="fas fa-clock mr-1"></i>
                                    Expires: {{ \Carbon\Carbon::parse($notice->expiry_date)->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
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
                    </div>
                </div>

                <!-- Content Preview -->
                <div class="prose max-w-none mb-4">
                    {!! Str::limit(strip_tags($notice->content), 200) !!}
                    @if(strlen(strip_tags($notice->content)) > 200)
                        <a href="{{ route('admin.notices.show', $notice) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium ml-2">
                            Read more...
                        </a>
                    @endif
                </div>

                <!-- Target Audience and Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-600">Audience:</span>
                            <div class="flex space-x-1">
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
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.notices.show', $notice) }}" 
                           class="text-blue-600 hover:text-blue-800 bg-white hover:bg-blue-50 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 border border-blue-200">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <a href="{{ route('admin.notices.edit', $notice) }}" 
                           class="text-green-600 hover:text-green-800 bg-white hover:bg-green-50 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 border border-green-200">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <i class="fas fa-bullhorn text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Active Notices</h3>
                <p class="text-gray-500 mb-6">There are no published notices to display on the homepage.</p>
                <a href="{{ route('admin.notices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create Notice
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notices->hasPages())
        <div class="table-header px-6 py-4 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $notices->firstItem() }} to {{ $notices->lastItem() }} of {{ $notices->total() }} active notices
                </div>
                <div class="flex space-x-2">
                    {{ $notices->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="content-card rounded-lg p-6 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-plus text-blue-600 text-xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Create New Notice</h4>
            <p class="text-sm text-gray-600 mb-4">Add a new notice to the homepage</p>
            <a href="{{ route('admin.notices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Create Notice
            </a>
        </div>

        <div class="content-card rounded-lg p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-book text-green-600 text-xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">View Diary</h4>
            <p class="text-sm text-gray-600 mb-4">See all notices in chronological order</p>
            <a href="{{ route('admin.notices.diary') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                View Diary
            </a>
        </div>

        <div class="content-card rounded-lg p-6 text-center">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-cog text-purple-600 text-xl"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Manage All</h4>
            <p class="text-sm text-gray-600 mb-4">View and manage all notices</p>
            <a href="{{ route('admin.notices.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Manage Notices
            </a>
        </div>
    </div>
</div>

<style>
    .content-card {
        /*background: rgba(255, 255, 255, 0.8);*/
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }

    .prose {
        color: #374151;
        line-height: 1.75;
    }

    .prose p {
        margin-bottom: 1em;
    }
</style>
@endsection