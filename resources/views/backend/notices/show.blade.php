@extends('layouts.app')

@section('title', $notice->title . ' - Notice')

@section('content')
<div class="space-y-6">
    <!-- Notice Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ $notice->title }}</h3>
                    <p class="text-gray-600">Notice</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.notices.edit', $notice) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Notice
                </a>
                <a href="{{ route('admin.notices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Notice Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column - Content -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Content Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Content</h4>
                
                <div class="prose max-w-none">
                    {!! $notice->content !!}
                </div>
            </div>
        </div>

        <!-- Right Column - Meta Information -->
        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Priority</span>
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
                    
                    <div>
                        <span class="text-sm font-medium text-gray-600 mb-2 block">Target Audience</span>
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
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $notice->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($notice->status) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Published By</span>
                        <span class="text-sm text-gray-900">{{ $notice->publishedBy->name ?? 'System' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Created Date</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($notice->created_at)->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Expiry Date</span>
                        <span class="text-sm text-gray-900 {{ $notice->expiry_date->isPast() ? 'text-red-600 font-semibold' : '' }}">
                            {{ $notice->expiry_date->format('M d, Y') }}
                            @if($notice->expiry_date->isPast())
                                <span class="text-xs text-red-500">(Expired)</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($notice->updated_at)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.notices.edit', $notice) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Edit Notice</span>
                    </a>
                    
                    <a href="#" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-share text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Share Notice</span>
                    </a>
                    
                    <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center space-x-3 p-3 hover:bg-red-50 rounded-lg transition-colors duration-200 w-full text-left"
                                onclick="return confirm('Are you sure you want to delete this notice?')">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trash text-red-600 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Delete Notice</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Preview -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Preview</h4>
                
                <div class="text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">
                    @if($notice->status == 'published')
                        @if($notice->expiry_date->isPast())
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600">This notice is published but has expired.</p>
                        @else
                            <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600">This notice is currently active and visible to the target audience.</p>
                        @endif
                    @else
                        <i class="fas fa-eye-slash text-yellow-500 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">This notice is in draft mode and not visible to the target audience.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
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

    .prose {
        color: #374151;
        line-height: 1.75;
    }

    .prose h1, .prose h2, .prose h3, .prose h4 {
        color: #111827;
        font-weight: 600;
        margin-top: 1.5em;
        margin-bottom: 0.5em;
    }

    .prose p {
        margin-bottom: 1em;
    }

    .prose ul, .prose ol {
        margin-bottom: 1em;
        padding-left: 1.5em;
    }

    .prose li {
        margin-bottom: 0.5em;
    }

    .prose strong {
        font-weight: 600;
        color: #111827;
    }

    .prose em {
        font-style: italic;
    }
</style>
@endsection