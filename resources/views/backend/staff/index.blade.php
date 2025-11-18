@extends('layouts.backend')

@section('title', 'Staff Management')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-4 py-5 sm:px-6 border-b dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Staff Members</h2>
            <a href="{{ route('admin.staff.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Staff
            </a>
        </div>
    </div>

    <!-- Staff List -->
    <div class="overflow-x-auto">
        <!-- Mobile Card View -->
        <div class="block sm:hidden divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($staff as $member)
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 dark:text-white">{{ $member->getFullNameAttribute() }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $member->username }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</div>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $member->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        {{ ucfirst($member->status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400 mb-3">
                    <div class="flex items-center">
                        <i class="fas fa-briefcase mr-2 text-gray-400"></i>
                        {{ ucfirst($member->role) }}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-money-bill mr-2 text-gray-400"></i>
                        {{ system('currency') }} {{ number_format($member->salary, 2) }}
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.staff.show', $member) }}" 
                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('admin.staff.edit', $member) }}" 
                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Staff Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Contact Info</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Salary</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($staff as $member)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-4 lg:px-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $member->getFullNameAttribute() }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $member->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 lg:px-6">
                            <div class="text-sm text-gray-800 dark:text-white">{{ $member->email }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $member->phone }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap lg:px-6">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $member->role === 'manager' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : 
                                   ($member->role === 'accountant' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 
                                   'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300') }}">
                                {{ ucfirst($member->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 lg:px-6">
                            {{ setting('currency') }} {{ number_format($member->salary, 2) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap lg:px-6">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $member->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium lg:px-6">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.staff.show', $member) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 p-2 rounded hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors"
                                   title="View">
                                    <i class="fas fa-eye w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.staff.edit', $member) }}" 
                                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 p-2 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 p-2 rounded hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-4 py-4 border-t dark:border-gray-700 sm:px-6">
        <div class="flex justify-center">
            {{ $staff->links() }}
        </div>
    </div>
</div>
@endsection