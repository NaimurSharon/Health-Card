@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="space-y-6">
    <!-- Filters Section -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Role Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-40 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- School Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                    <select name="school_id" class="w-48 border-gray-300 rounded-lg text-sm bg-white/90">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Search Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name, email or phone..." 
                           class="w-64 border-gray-300 rounded-lg text-sm bg-white/90 px-3 py-2">
                </div>
                
                <!-- Go Button -->
                <div class="self-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Go
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="content-card overflow-hidden">
        <!-- Table Header -->
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Users</h3>
            <a href="{{ route('admin.users.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>Add User
            </a>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full transparent-table">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            School
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Joined
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/40">
                    @forelse($users as $user)
                    <tr class="hover:bg-white/60 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    @if($user->profile_image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('public/storage/' . $user->profile_image) }}" alt="{{ $user->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:from-blue-200 group-hover:to-purple-200 transition-all duration-200">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $user->phone ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $user->role == 'teacher' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $user->role == 'student' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $user->role == 'doctor' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->specialization)
                                <div class="text-xs text-gray-500 mt-1">{{ $user->specialization }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $user->school->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-blue-600 hover:text-blue-800 bg-white/70 hover:bg-blue-50 p-2 rounded-lg transition-all duration-200"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-green-600 hover:text-green-800 bg-white/70 hover:bg-green-50 p-2 rounded-lg transition-all duration-200"
                                   title="Edit User">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <!-- Health Report Link - Only for Students -->
                                @if($user->role == 'student')
                                    <a href="{{ route('admin.health-reports.student', $user) }}" 
                                       class="text-teal-600 hover:text-teal-800 bg-white/70 hover:bg-teal-50 p-2 rounded-lg transition-all duration-200"
                                       title="Health Report">
                                        <i class="fas fa-file-medical-alt text-sm"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 bg-white/70 hover:bg-red-50 p-2 rounded-lg transition-all duration-200"
                                            onclick="return confirm('Are you sure you want to delete this user?')"
                                            title="Delete User">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="text-gray-500 text-lg">
                                <i class="fas fa-users text-4xl mb-4 opacity-50"></i>
                                <p>No users found.</p>
                                <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                    Add your first user
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="table-header px-6 py-4 border-t border-gray-200/60">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .transparent-table {
        background: transparent;
        backdrop-filter: blur(10px);
    }
    
    .transparent-table thead tr {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        backdrop-filter: blur(10px);
    }
    
    .transparent-table tbody tr {
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
    }
    
    .transparent-table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .content-card {
        background: transparent;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection