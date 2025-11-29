@extends('layouts.app')

@section('title', 'Notice Diary')

@section('content')
<div class="space-y-6">
    <!-- Diary Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-book text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Notice Diary</h3>
                    <p class="text-gray-200">All notices in chronological order</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.notices.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Notice
                </a>
                <a href="{{ route('admin.notices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-list mr-2"></i>List View
                </a>
            </div>
        </div>
    </div>

    <!-- Notices Diary -->
    <div class="space-y-6">
        @forelse($notices as $notice)
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        @php
                            $priorityIcons = [
                                'high' => 'fas fa-exclamation-circle text-red-500',
                                'medium' => 'fas fa-info-circle text-yellow-500',
                                'low' => 'fas fa-check-circle text-green-500'
                            ];
                        @endphp
                        <i class="{{ $priorityIcons[$notice->priority] ?? 'fas fa-bullhorn text-blue-500' }} text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold text-gray-900">{{ $notice->title }}</h4>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                {{ $notice->publishedBy->name ?? 'System' }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($notice->created_at)->format('M d, Y') }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($notice->created_at)->format('h:i A') }}
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
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $notice->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($notice->status) }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="prose max-w-none mb-4">
                {!! Str::limit(strip_tags($notice->content), 300) !!}
                @if(strlen(strip_tags($notice->content)) > 300)
                    <a href="{{ route('admin.notices.show', $notice) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Read more...
                    </a>
                @endif
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-600">Target:</span>
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
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-600">Expires:</span>
                        <span class="text-sm text-gray-500 {{ $notice->expiry_date->isPast() ? 'text-red-600 font-semibold' : '' }}">
                            {{ $notice->expiry_date->format('M d, Y') }}
                            @if($notice->expiry_date->isPast())
                                <span class="text-xs text-red-500">(Expired)</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.notices.show', $notice) }}" 
                       class="text-blue-600 hover:text-blue-800 bg-white/70 hover:bg-blue-50 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <a href="{{ route('admin.notices.edit', $notice) }}" 
                       class="text-green-600 hover:text-green-800 bg-white/70 hover:bg-green-50 px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="content-card rounded-lg p-8 text-center">
            <i class="fas fa-book text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Notices Found</h3>
            <p class="text-gray-500 mb-4">There are no notices to display in the diary.</p>
            <a href="{{ route('admin.notices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Create First Notice
            </a>
        </div>
        @endforelse
    </div>

    <!-- Simple Pagination for Diary View -->
    @if($notices->hasPages())
    <div class="content-card rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $notices->firstItem() }} to {{ $notices->lastItem() }} of {{ $notices->total() }} notices
            </div>
            <div class="flex space-x-2">
                {{ $notices->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .content-card {
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