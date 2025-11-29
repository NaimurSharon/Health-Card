@extends('layouts.app')

@section('title', 'Treatment Requests')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Treatment Requests</h3>
            <div class="text-sm text-gray-600">
                <span class="font-medium">{{ $treatmentRequests->total() }}</span> total requests
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $treatmentRequests->where('status', 'pending')->count() }}
                    </p>
                </div>
                <i class="fas fa-clock text-blue-400 text-xl"></i>
            </div>
        </div>
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $treatmentRequests->where('status', 'approved')->count() }}
                    </p>
                </div>
                <i class="fas fa-check-circle text-green-400 text-xl"></i>
            </div>
        </div>
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $treatmentRequests->where('status', 'rejected')->count() }}
                    </p>
                </div>
                <i class="fas fa-times-circle text-red-400 text-xl"></i>
            </div>
        </div>
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $treatmentRequests->where('status', 'completed')->count() }}
                    </p>
                </div>
                <i class="fas fa-stethoscope text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form action="{{ route('doctor.treatment-requests.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('doctor.treatment-requests.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Treatment Requests Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Requested By</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Symptoms</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($treatmentRequests as $request)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $request->student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $request->student->student_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->requestedBy->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $request->requestedBy->role ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 line-clamp-2">{{ $request->symptoms }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $request->priority == 'emergency' ? 'bg-red-100 text-red-800' : 
                                       ($request->priority == 'high' ? 'bg-orange-100 text-orange-800' : 
                                       ($request->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($request->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($request->status == 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($request->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->created_at->format('M j, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $request->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('doctor.treatment-requests.show', $request) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-stethoscope text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No treatment requests found</p>
                                <p class="text-sm mt-2">No treatment requests match your current filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($treatmentRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-200/60">
                {{ $treatmentRequests->links() }}
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
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });
    });
</script>
@endsection