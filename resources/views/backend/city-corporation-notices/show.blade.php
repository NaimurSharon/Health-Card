@extends('layouts.app')

@section('title', 'Notice Details - ' . $cityCorporationNotice->title)

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-city text-blue-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-xl sm:text-2xl font-bold">Notice Details</h3>
                        <p class="text-blue-100 text-sm">City Corporation Notice Information</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <a href="{{ route('admin.city-corporation-notices.edit', $cityCorporationNotice) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 sm:px-5 sm:py-3 text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Edit Notice
                    </a>
                    <a href="{{ route('admin.city-corporation-notices.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 sm:px-5 sm:py-3 text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Notice Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Notice Header -->
            <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                    <div class="flex-1">
                        <h4 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">{{ $cityCorporationNotice->title }}</h4>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 text-xs sm:text-sm text-gray-600 mb-3 space-y-1 sm:space-y-0">
                            <span class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                Published by: {{ $cityCorporationNotice->publishedBy->name ?? 'City Corporation' }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Created: {{ $cityCorporationNotice->created_at->format('M j, Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium 
                            {{ $cityCorporationNotice->priority == 'high' ? 'bg-red-100 text-red-800' : 
                               ($cityCorporationNotice->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($cityCorporationNotice->priority) }} Priority
                        </span>
                        <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium 
                            {{ $cityCorporationNotice->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($cityCorporationNotice->status) }}
                        </span>
                    </div>
                </div>

                <!-- Notice Content -->
                <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                    <p class="text-gray-700 whitespace-pre-line text-sm sm:text-base">{{ $cityCorporationNotice->content }}</p>
                </div>
            </div>

            <!-- Target Information -->
            <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
                <h5 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Target Information</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <h6 class="font-medium text-gray-700 text-sm sm:text-base mb-2">Target Type</h6>
                        <div class="flex items-center">
                            <span class="px-2 py-1 sm:px-3 sm:py-2 bg-blue-100 text-blue-800 rounded-lg text-xs sm:text-sm font-medium capitalize">
                                {{ $cityCorporationNotice->target_type }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <h6 class="font-medium text-gray-700 text-sm sm:text-base mb-2">Target Audience</h6>
                        <div class="flex flex-wrap gap-1 sm:gap-2">
                            @foreach($cityCorporationNotice->target_roles as $role)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $role == 'student' ? 'bg-green-100 text-green-800' : 
                                       ($role == 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                    {{ ucfirst($role) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($cityCorporationNotice->target_type === 'specific_schools' && $cityCorporationNotice->target_schools)
                    <div class="mt-3 sm:mt-4">
                        <h6 class="font-medium text-gray-700 text-sm sm:text-base mb-2">Target Schools</h6>
                        <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($schools as $school)
                                    <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-700">
                                        <i class="fas fa-school text-gray-400 text-xs"></i>
                                        <span>{{ $school->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Timeline & Status -->
            <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
                <h5 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Timeline</h5>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs sm:text-sm text-gray-600">Created</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900">{{ $cityCorporationNotice->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs sm:text-sm text-gray-600">Last Updated</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900">{{ $cityCorporationNotice->updated_at->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs sm:text-sm text-gray-600">Expiry Date</span>
                        <span class="text-xs sm:text-sm font-medium {{ $cityCorporationNotice->expiry_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $cityCorporationNotice->expiry_date->format('M j, Y') }}
                        </span>
                    </div>
                    <div class="pt-3 sm:pt-4 border-t border-gray-200">
                        <span class="text-xs sm:text-sm text-gray-600">Status:</span>
                        <span class="ml-2 text-xs sm:text-sm font-medium 
                            {{ $cityCorporationNotice->status == 'published' ? 'text-green-600' : 'text-gray-600' }}">
                            {{ ucfirst($cityCorporationNotice->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
                <h5 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Quick Actions</h5>
                <div class="space-y-2 sm:space-y-3">
                    <a href="{{ route('admin.city-corporation-notices.edit', $cityCorporationNotice) }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Edit Notice
                    </a>
                    <form action="{{ route('admin.city-corporation-notices.destroy', $cityCorporationNotice) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this notice?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>Delete Notice
                        </button>
                    </form>
                    <a href="{{ route('admin.city-corporation-notices.create') }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Create New
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
                <h5 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Impact</h5>
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs sm:text-sm text-gray-600">Target Schools</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900">
                            {{ $cityCorporationNotice->target_type === 'all_schools' ? 'All Schools' : count($cityCorporationNotice->target_schools ?? []) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs sm:text-sm text-gray-600">Target Roles</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-900">{{ count($cityCorporationNotice->target_roles) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs sm:text-sm text-gray-600">Days Remaining</span>
                        <span class="text-xs sm:text-sm font-medium {{ $cityCorporationNotice->expiry_date->isPast() ? 'text-red-600' : 'text-green-600' }}">
                            {{ max(0, $cityCorporationNotice->expiry_date->diffInDays(now())) }} days
                        </span>
                    </div>
                </div>
            </div>
        </div>
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
</style>
@endsection