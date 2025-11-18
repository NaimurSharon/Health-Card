@extends('layouts.app')

@section('title', isset($user) ? 'Edit User' : 'Add New User')

@section('content')
<div class="space-y-6">
    <!-- User Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ isset($user) ? 'Edit User' : 'Add New User' }}</h3>
            <button type="submit" form="user-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ isset($user) ? 'Update User' : 'Create User' }}
            </button>
        </div>
    </div>

    <!-- User Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="user-form" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Profile Image Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Profile Image</h4>
                    
                    <div class="flex items-start space-x-6">
                        <!-- Current Profile Image -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                @if(isset($user) && $user->profile_image)
                                    <img src="{{ asset('public/storage/' . $user->profile_image) }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                    <div class="absolute -top-2 -right-2">
                                        <input type="checkbox" name="remove_profile_image" value="1" id="remove_profile_image" class="hidden">
                                        <label for="remove_profile_image" 
                                               class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center cursor-pointer transition-colors"
                                               onclick="toggleRemoveImage(this)">
                                            <i class="fas fa-times text-xs"></i>
                                        </label>
                                    </div>
                                @else
                                    <div class="w-32 h-32 rounded-full bg-gray-200 border-4 border-white shadow-lg flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Upload Area -->
                        <div class="flex-1">
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">Upload Profile Image</label>
                            <div id="uploadArea" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200">
                                <input type="file" name="profile_image" id="profile_image" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                       class="hidden"
                                       onchange="handleFileSelect(this)">
                                <label for="profile_image" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-medium text-blue-600">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, WEBP (Max: 5MB)
                                    </p>
                                </label>
                            </div>
                            
                            <!-- Selected File Info -->
                            <div id="fileInfo" class="mt-3 hidden">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        <div>
                                            <p class="text-sm font-medium text-green-800" id="fileName">File selected</p>
                                            <p class="text-xs text-green-600" id="fileSize"></p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="clearFileSelection()" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div id="imagePreview" class="mt-4 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                <img id="preview" class="w-32 h-32 rounded-full object-cover border-2 border-blue-500">
                            </div>
                            @error('profile_image')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $user->name ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter full name">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter email address">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ isset($user) ? 'New Password' : 'Password *' }}
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="{{ isset($user) ? 'Leave blank to keep current' : 'Enter password' }}">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ isset($user) ? 'Confirm New Password' : 'Confirm Password *' }}
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Confirm password">
                        </div>
                    </div>
                </div>

                <!-- Personal Details Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Personal Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" id="phone" 
                                   value="{{ old('phone', $user->phone ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter phone number">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   value="{{ old('date_of_birth', $user->date_of_birth ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('date_of_birth')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select name="gender" id="gender" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $user->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter complete address">{{ old('address', $user->address ?? '') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Professional Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                            <select name="role" id="role" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="teacher" {{ old('role', $user->role ?? '') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="student" {{ old('role', $user->role ?? '') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="doctor" {{ old('role', $user->role ?? '') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- School -->
                        <div>
                            <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">School</label>
                            <select name="school_id" id="school_id" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select School</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id', $user->school_id ?? '') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Specialization -->
                        <div id="specializationField">
                            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                            <input type="text" name="specialization" id="specialization" 
                                   value="{{ old('specialization', $user->specialization ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter specialization">
                            @error('specialization')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Qualifications -->
                    <div id="qualificationsField">
                        <label for="qualifications" class="block text-sm font-medium text-gray-700 mb-2">Qualifications</label>
                        <textarea name="qualifications" id="qualifications" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter qualifications">{{ old('qualifications', $user->qualifications ?? '') }}</textarea>
                        @error('qualifications')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ isset($user) ? 'Update User' : 'Create User' }}
                </button>
            </div>
        </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth interactions
        const inputs = document.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });

        // Show/hide professional fields based on role
        const roleSelect = document.getElementById('role');
        const specializationField = document.getElementById('specializationField');
        const qualificationsField = document.getElementById('qualificationsField');

        function toggleProfessionalFields() {
            const role = roleSelect.value;
            if (role === 'teacher' || role === 'doctor') {
                specializationField.style.display = 'block';
                qualificationsField.style.display = 'block';
            } else {
                specializationField.style.display = 'none';
                qualificationsField.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', toggleProfessionalFields);
        toggleProfessionalFields(); // Initial call

        // Drag and drop functionality
        const dropArea = document.getElementById('uploadArea');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('border-blue-400', 'bg-blue-50');
        }

        function unhighlight() {
            dropArea.classList.remove('border-blue-400', 'bg-blue-50');
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                const fileInput = document.getElementById('profile_image');
                fileInput.files = files;
                handleFileSelect(fileInput);
            }
        }
    });

    // Handle file selection with better feedback - FIXED VERSION
    function handleFileSelect(input) {
        const uploadArea = document.getElementById('uploadArea');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Update upload area appearance - DON'T replace the content, just update styles
            uploadArea.classList.remove('border-gray-300', 'hover:border-blue-400');
            uploadArea.classList.add('border-green-400', 'bg-green-50');
            
            // Hide the original content and show success message
            const originalContent = uploadArea.querySelector('label');
            if (originalContent) {
                originalContent.style.display = 'none';
            }
            
            // Add success message without removing the file input
            let successMessage = uploadArea.querySelector('.success-message');
            if (!successMessage) {
                successMessage = document.createElement('div');
                successMessage.className = 'success-message text-center';
                successMessage.innerHTML = `
                    <i class="fas fa-check-circle text-3xl text-green-500 mb-3"></i>
                    <p class="text-sm font-medium text-green-700 mb-1">File Selected</p>
                    <p class="text-xs text-green-600">Ready to upload</p>
                    <button type="button" onclick="clearFileSelection()" class="mt-2 text-green-600 hover:text-green-800 text-sm">
                        <i class="fas fa-sync-alt mr-1"></i>Change file
                    </button>
                `;
                uploadArea.appendChild(successMessage);
            }
            
            // Show file info
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('hidden');
            
            // Show image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
            
        } else {
            clearFileSelection();
        }
    }

    // Clear file selection - FIXED VERSION
    function clearFileSelection() {
        const input = document.getElementById('profile_image');
        const uploadArea = document.getElementById('uploadArea');
        const fileInfo = document.getElementById('fileInfo');
        const imagePreview = document.getElementById('imagePreview');
        
        // Reset file input
        input.value = '';
        
        // Reset upload area
        uploadArea.classList.remove('border-green-400', 'bg-green-50');
        uploadArea.classList.add('border-gray-300', 'hover:border-blue-400');
        
        // Remove success message and show original content
        const successMessage = uploadArea.querySelector('.success-message');
        if (successMessage) {
            successMessage.remove();
        }
        
        const originalContent = uploadArea.querySelector('label');
        if (originalContent) {
            originalContent.style.display = 'block';
        }
        
        // Hide file info and preview
        fileInfo.classList.add('hidden');
        imagePreview.classList.add('hidden');
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Toggle remove image checkbox
    function toggleRemoveImage(button) {
        const checkbox = document.getElementById('remove_profile_image');
        if (checkbox.checked) {
            checkbox.checked = false;
            button.classList.remove('bg-red-600');
            button.classList.add('bg-red-500');
        } else {
            checkbox.checked = true;
            button.classList.remove('bg-red-500');
            button.classList.add('bg-red-600');
        }
    }
</script>
@endsection