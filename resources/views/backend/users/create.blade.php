@extends('layouts.backend')

@section('title', 'Create User')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Create New User</h3>
    </div>
    
    <div class="p-6">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-white text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-white text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-white text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-white text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-white text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="author" {{ old('role') == 'author' ? 'selected' : '' }}>Author</option>
                        <option value="subscriber" {{ old('role') == 'subscriber' ? 'selected' : '' }}>Subscriber</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="subscription_expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subscription Expires At (optional)</label>
                    <input type="date" name="subscription_expires_at" id="subscription_expires_at" value="{{ old('subscription_expires_at') }}"
                        class="w-full px-4 py-2 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-white text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('subscription_expires_at')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end mt-8">
                <a href="{{ route('admin.users.index') }}" class="mr-3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection