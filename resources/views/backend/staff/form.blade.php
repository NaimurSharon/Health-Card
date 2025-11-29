@extends('layouts.backend')

@section('title', isset($staff) ? 'Edit Staff' : 'Add Staff')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ isset($staff) ? 'Edit Staff Member' : 'Add New Staff Member' }}
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ isset($staff) ? 'Update staff member information' : 'Add a new staff member to your team' }}
                </p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('admin.staff.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Staff
                </a>
            </div>
        </div>
    </div>

    <form action="{{ isset($staff) ? route('admin.staff.update', $staff) : route('admin.staff.store') }}" method="POST" class="p-4 sm:p-6">
        @csrf
        @if(isset($staff))
            @method('PUT')
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Account Information -->
            <div class="space-y-4">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                        Account Information
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username *</label>
                            <input type="text" name="username" id="username" 
                                   value="{{ old('username', $staff->username ?? '') }}" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('username') border-red-500 @enderror" 
                                   required>
                            @error('username')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $staff->email ?? '') }}" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror" 
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ isset($staff) ? 'New Password (leave blank to keep current)' : 'Password *' }}
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-500 @enderror"
                                   {{ isset($staff) ? '' : 'required' }}>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        @if(!isset($staff))
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="space-y-4">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-id-card mr-2 text-green-500"></i>
                        Personal Information
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">First Name *</label>
                                <input type="text" name="first_name" id="first_name" 
                                       value="{{ old('first_name', $staff->first_name ?? '') }}" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('first_name') border-red-500 @enderror" 
                                       required>
                                @error('first_name')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Last Name *</label>
                                <input type="text" name="last_name" id="last_name" 
                                       value="{{ old('last_name', $staff->last_name ?? '') }}" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('last_name') border-red-500 @enderror" 
                                       required>
                                @error('last_name')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone *</label>
                            <input type="text" name="phone" id="phone" 
                                   value="{{ old('phone', $staff->phone ?? '') }}" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('phone') border-red-500 @enderror" 
                                   required>
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role *</label>
                            <select name="role" id="role" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('role') border-red-500 @enderror" required>
                                <option value="staff" {{ old('role', $staff->role ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="accountant" {{ old('role', $staff->role ?? '') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                                <option value="manager" {{ old('role', $staff->role ?? '') == 'manager' ? 'selected' : '' }}>Manager</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                    Address Information
                </h4>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address *</label>
                    <textarea name="address" id="address" rows="3" 
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('address') border-red-500 @enderror" 
                              required>{{ old('address', $staff->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2 text-yellow-500"></i>
                    Employment Details
                </h4>
                <div class="space-y-4">
                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Monthly Salary *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">{{ system('currency') }}</span>
                            </div>
                            <input type="number" step="0.01" name="salary" id="salary" 
                                   value="{{ old('salary', $staff->salary ?? '') }}" 
                                   class="pl-12 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('salary') border-red-500 @enderror" 
                                   placeholder="0.00" required>
                        </div>
                        @error('salary')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hire Date *</label>
                        <input type="date" name="hire_date" id="hire_date" 
                               value="{{ old('hire_date', isset($staff) && $staff->hire_date ? $staff->hire_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('hire_date') border-red-500 @enderror" 
                               required>
                        @error('hire_date')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                        <select name="status" id="status" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror" required>
                            <option value="active" {{ old('status', $staff->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $staff->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 pt-6 border-t dark:border-gray-700 mt-6">
            <a href="{{ route('admin.staff.index') }}" 
               class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-{{ isset($staff) ? 'save' : 'plus' }} mr-2"></i>
                {{ isset($staff) ? 'Update Staff' : 'Create Staff' }}
            </button>
        </div>
    </form>
</div>
@endsection