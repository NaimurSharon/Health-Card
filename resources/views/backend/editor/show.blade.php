@extends('layouts.app')

@section('title', $editor->title . ' - Content')

@section('content')
<div class="space-y-6">
    <!-- Content Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-edit text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ $editor->title }}</h3>
                    <p class="text-gray-600">{{ ucfirst($editor->type) }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.editor.edit', $editor) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Content
                </a>
                <a href="{{ route('admin.editor.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Content Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column - Content -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Content Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Content</h4>
                
                <div class="prose max-w-none">
                    {!! $editor->content !!}
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
                        <span class="text-sm font-medium text-gray-600">Type</span>
                        @php
                            $typeColors = [
                                'page' => 'bg-blue-100 text-blue-800',
                                'post' => 'bg-green-100 text-green-800',
                                'notice' => 'bg-orange-100 text-orange-800'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $typeColors[$editor->type] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($editor->type) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $editor->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($editor->status) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Author</span>
                        <span class="text-sm text-gray-900">{{ $editor->createdBy->name ?? 'System' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Created Date</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($editor->created_at)->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($editor->updated_at)->format('M d, Y') }}</span>
                    </div>

                    @if($editor->published_at)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Published Date</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($editor->published_at)->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Excerpt -->
            @if($editor->excerpt)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Excerpt</h4>
                <p class="text-sm text-gray-700">{{ $editor->excerpt }}</p>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.editor.edit', $editor) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Edit Content</span>
                    </a>
                    
                    <a href="#" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Preview</span>
                    </a>
                    
                    <form action="{{ route('admin.editor.destroy', $editor) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center space-x-3 p-3 hover:bg-red-50 rounded-lg transition-colors duration-200 w-full text-left"
                                onclick="return confirm('Are you sure you want to delete this content?')">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trash text-red-600 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Delete Content</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- SEO Information -->
            @if($editor->meta_title || $editor->meta_description)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">SEO Information</h4>
                
                <div class="space-y-3">
                    @if($editor->meta_title)
                    <div>
                        <span class="text-sm font-medium text-gray-600">Meta Title</span>
                        <p class="text-sm text-gray-700 mt-1">{{ $editor->meta_title }}</p>
                    </div>
                    @endif
                    
                    @if($editor->meta_description)
                    <div>
                        <span class="text-sm font-medium text-gray-600">Meta Description</span>
                        <p class="text-sm text-gray-700 mt-1">{{ $editor->meta_description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
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