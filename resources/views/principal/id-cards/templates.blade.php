@extends('layouts.principal')

@section('title', 'ID Card Templates')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div class="table-header px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold">
                            ID Card Templates
                        </h3>
                        <p class="text-gray-200 mt-1">
                            Manage ID card templates for students and teachers
                        </p>
                    </div>
                    <a href="{{ route('principal.id-cards.index') }}"
                        class="text-gray-100 hover:text-blue-800 text-sm font-medium flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to ID Cards
                    </a>
                </div>
            </div>
        </div>

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($templates as $template)
                <div class="content-card rounded-lg p-4">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $template->name }}</h4>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $template->type == 'student' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($template->type) }}
                            </span>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if($template->background_image_url)
                        <div class="mb-4">
                            <img src="{{ $template->background_image_url }}" alt="{{ $template->name }}"
                                class="w-full h-40 object-cover rounded-lg">
                        </div>
                    @else
                        <div class="mb-4 h-40 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-id-card text-gray-300 text-3xl"></i>
                        </div>
                    @endif

                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">{{ $template->description }}</p>
                        <div class="text-xs text-gray-500">
                            <p>Size: {{ $template->dimensions }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('principal.id-cards.preview-template', $template->id) }}" target="_blank"
                            class="flex-1 text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fas fa-eye mr-1"></i> Preview
                        </a>
                        <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full content-card rounded-lg p-8 text-center">
                    <i class="fas fa-templates text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No templates available</p>
                    <p class="text-sm text-gray-400 mt-1">Contact admin to create templates</p>
                </div>
            @endforelse
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
    </style>
@endsection