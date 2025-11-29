@extends('layouts.principal')

@section('title', 'Manage Subjects')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <!-- Page Title -->
            <div class="flex-1">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-200">Subject Management</h3>
                <p class="text-gray-200 mt-1 text-sm sm:text-base">Manage all subjects in your school</p>
            </div>
    
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <a href="{{ route('principal.subjects.create') }}" 
                   class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>Add New Subject
                </a>
                <a href="{{ route('principal.subjects.assign-teachers') }}" 
                   class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>Assign Teacher
                </a>
            </div>
        </div>
    </div>


    <!-- Search and Filters -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" placeholder="Search subjects by name or code..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Types</option>
                <option value="compulsory">Compulsory</option>
                <option value="optional">Optional</option>
                <option value="extra-curricular">Extra Curricular</option>
            </select>
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ $subject->description ?? 'No description' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-mono">{{ $subject->code }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $subject->type == 'compulsory' ? 'bg-blue-100 text-blue-800' : 
                                   ($subject->type == 'optional' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ ucfirst(str_replace('-', ' ', $subject->type)) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $subject->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($subject->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('principal.subjects.edit', $subject->id) }}" 
                                   class="text-green-600 hover:text-green-900 p-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('principal.subjects.destroy', $subject->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1" 
                                            onclick="return confirm('Are you sure you want to delete this subject?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                            <p>No subjects found</p>
                            <a href="{{ route('principal.subjects.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-4">
                                <i class="fas fa-plus mr-2"></i>Add First Subject
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($subjects->hasPages())
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $subjects->links() }}
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

    @media (max-width: 640px) {
        table {
            font-size: 0.875rem;
        }
        
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .max-w-xs {
            max-width: 12rem;
        }
    }

    @media (max-width: 480px) {
        .max-w-xs {
            max-width: 8rem;
        }
    }
</style>
@endsection