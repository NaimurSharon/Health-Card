@extends('layouts.app')

@section('title', 'ID Card Templates')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">ID Card Templates</h3>
            <a href="{{ route('admin.id-card-templates.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>Create Template
            </a>
        </div>
    </div>

    <!-- Templates Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Template</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Type & Dimensions</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Orientation</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($templates as $template)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($template->background_image)
                                        <div class="flex-shrink-0 h-12 w-16 bg-gray-100 rounded border flex items-center justify-center overflow-hidden">
                                            <img src="{{ $template->background_image_url }}" 
                                                 alt="{{ $template->name }}"
                                                 class="h-full w-full object-cover">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-12 w-16 bg-gray-100 rounded border flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $template->name }}</div>
                                        <div class="text-sm text-gray-500 line-clamp-2 max-w-xs">{{ $template->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $template->type == 'student' ? 'bg-blue-100 text-blue-800' : 
                                           ($template->type == 'teacher' ? 'bg-green-100 text-green-800' : 
                                           ($template->type == 'staff' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800')) }}">
                                        {{ ucfirst($template->type) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">{{ $template->dimensions }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-{{ $template->orientation == 'portrait' ? 'mobile-alt' : 'desktop' }} mr-1"></i>
                                        {{ ucfirst($template->orientation) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-{{ $template->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $template->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $template->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    @if($template->background_image)
                                    <a href="{{ $template->background_image_url }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors" 
                                       title="Preview Image" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('admin.id-card-templates.edit', $template) }}" 
                                       class="text-green-600 hover:text-green-900 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.id-card-templates.destroy', $template) }}" 
                                          method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this template? This will affect all ID cards using this template.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-palette text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No templates found</p>
                                <p class="text-sm mt-2">Get started by creating your first ID card template.</p>
                                <a href="{{ route('admin.id-card-templates.create') }}" 
                                   class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Create Template
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($templates->hasPages())
            <div class="px-6 py-4 border-t border-gray-200/60">
                {{ $templates->links() }}
            </div>
        @endif
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
        const links = document.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.classList.add('transition-colors', 'duration-200');
            });
        });
    });
</script>
@endsection