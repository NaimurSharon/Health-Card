@extends('layouts.principal')

@section('title', 'Notices Management')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div
                class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">Notices Management</h3>
                    <p class="text-gray-600 mt-1">Manage all school notices and announcements</p>
                </div>
                <a href="{{ route('principal.notices.create') }}"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>Create Notice
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" id="searchInput" placeholder="Search notices..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <select id="statusFilter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
                <select id="priorityFilter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Priorities</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
        </div>

        <!-- Notices List -->
        <div class="space-y-4">
            @forelse($notices as $notice)
                <div class="content-card rounded-lg p-4 sm:p-6 notice-card" data-status="{{ $notice->status }}"
                    data-priority="{{ $notice->priority }}"
                    data-search="{{ strtolower($notice->title . ' ' . $notice->content) }}">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <!-- Notice Info -->
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $notice->title }}</h4>
                                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <i class="fas fa-flag mr-1"></i>
                                            @if($notice->priority === 'high')
                                                <span class="text-red-600 font-medium">High Priority</span>
                                            @elseif($notice->priority === 'medium')
                                                <span class="text-yellow-600 font-medium">Medium Priority</span>
                                            @else
                                                <span class="text-green-600 font-medium">Low Priority</span>
                                            @endif
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $notice->created_at->format('M j, Y') }}
                                        </span>
                                        @if($notice->published_at)
                                            <span class="flex items-center">
                                                <i class="fas fa-paper-plane mr-1"></i>
                                                Published: {{ $notice->published_at->format('M j, Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                                {{ $notice->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($notice->status) }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4">{{ Str::limit($notice->content, 200) }}</p>

                            <!-- Notice Meta -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                <div class="flex flex-wrap items-center gap-2 text-gray-600">
                                    <i class="fas fa-users mr-2 w-4"></i>
                                    <div class="flex flex-wrap gap-1">
                                        @if($notice->target_roles)
                                            @foreach($notice->target_roles as $role)
                                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full text-xs">
                                                    {{ ucfirst($role) }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-user-tie mr-2 w-4"></i>
                                    <span>By: {{ $notice->publishedBy->name ?? 'System' }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar-times mr-2 w-4"></i>
                                    <span
                                        class="{{ $notice->expiry_date && $notice->expiry_date < now() ? 'text-red-600' : '' }}">
                                        {{ $notice->expiry_date ? 'Expires: ' . $notice->expiry_date->format('M j, Y') : 'No expiry date' }}
                                        @if($notice->expiry_date && $notice->expiry_date < now())
                                            <span class="ml-1 text-red-500">(Expired)</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex items-center justify-end lg:justify-start space-x-2 lg:flex-col lg:space-x-0 lg:space-y-2">
                            @if($notice->status === 'draft')
                                <form action="{{ route('principal.notices.publish', $notice->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 rounded-lg transition-colors"
                                        onclick="return confirm('Publish this notice?')">
                                        <i class="fas fa-paper-plane text-sm"></i>
                                        <span class="hidden lg:inline ml-2">Publish</span>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('principal.notices.edit', $notice->id) }}"
                                class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                                <span class="hidden lg:inline ml-2">Edit</span>
                            </a>
                            <form action="{{ route('principal.notices.destroy', $notice->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this notice?')">
                                    <i class="fas fa-trash text-sm"></i>
                                    <span class="hidden lg:inline ml-2">Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="content-card rounded-lg p-8 text-center">
                    <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No notices found</p>
                    <a href="{{ route('principal.notices.create') }}"
                        class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-2"></i>Create your first notice
                    </a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusFilter = document.getElementById('statusFilter');
            const priorityFilter = document.getElementById('priorityFilter');
            const searchInput = document.getElementById('searchInput');
            const noticeCards = document.querySelectorAll('.notice-card');

            function filterNotices() {
                const statusValue = statusFilter.value;
                const priorityValue = priorityFilter.value;
                const searchValue = searchInput.value.toLowerCase();

                noticeCards.forEach(card => {
                    const matchesStatus = !statusValue || card.dataset.status === statusValue;
                    const matchesPriority = !priorityValue || card.dataset.priority === priorityValue;
                    const matchesSearch = !searchValue || card.dataset.search.includes(searchValue);

                    if (matchesStatus && matchesPriority && matchesSearch) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            statusFilter.addEventListener('change', filterNotices);
            priorityFilter.addEventListener('change', filterNotices);
            searchInput.addEventListener('input', filterNotices);
        });
    </script>
@endsection