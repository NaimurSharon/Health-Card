@extends('layouts.principal')

@section('title', 'Edit School Information')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div class="table-header px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold">
                            Edit School Information
                        </h3>
                        <p class="text-gray-200 mt-1">
                            Update your school details and profile information
                        </p>
                    </div>
                    <a href="{{ route('principal.dashboard') }}" 
                       class="text-gray-100 hover:text-blue-800 text-sm font-medium flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- School Information -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-6">School Details</h4>

            <form method="POST" action="{{ route('principal.school.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Logo Section -->
                <div class="flex flex-col items-center mb-6">
                    @if($school->logo_url)
                        <img src="{{ $school->logo_url }}" 
                             alt="School Logo" 
                             class="w-32 h-32 object-contain mb-4 rounded-lg">
                    @else
                        <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-school text-gray-400 text-4xl"></i>
                        </div>
                    @endif

                    <div class="text-center">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Update Logo
                        </label>
                        <input type="file" id="logo" name="logo" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-2">Max 2MB. JPG, PNG, GIF, WebP</p>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">School Name *</label>
                        <input type="text" name="name" required 
                               value="{{ old('name', $school->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">School Code *</label>
                        <input type="text" name="code" required 
                               value="{{ old('code', $school->code) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">School Type *</label>
                        <select name="type" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Type</option>
                            <option value="government" {{ old('type', $school->type) == 'government' ? 'selected' : '' }}>Government</option>
                            <option value="private" {{ old('type', $school->type) == 'private' ? 'selected' : '' }}>Private</option>
                            <option value="madrasa" {{ old('type', $school->type) == 'madrasa' ? 'selected' : '' }}>Madrasa</option>
                            <option value="international" {{ old('type', $school->type) == 'international' ? 'selected' : '' }}>International</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Established Year</label>
                        <input type="number" name="established_year"
                               value="{{ old('established_year', $school->established_year) }}"
                               min="1900" max="{{ date('Y') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('established_year')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $school->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" name="city"
                               value="{{ old('city', $school->city) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('status', $school->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $school->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone"
                               value="{{ old('phone', $school->phone) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email"
                               value="{{ old('email', $school->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" name="website"
                               value="{{ old('website', $school->website) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('website')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assigned Doctor</label>
                        <div class="px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p class="text-gray-900">{{ $school->assignedDoctor ? 'Dr. ' . $school->assignedDoctor->name : 'No doctor assigned' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Contact admin to change assigned doctor</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Students</label>
                        <input type="number" name="total_students"
                               value="{{ old('total_students', $school->total_students) }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('total_students')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Teachers</label>
                        <input type="number" name="total_teachers"
                               value="{{ old('total_teachers', $school->total_teachers) }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('total_teachers')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('principal.dashboard') }}" 
                       class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Update School Information
                    </button>
                </div>
            </form>
        </div>

        <!-- Principal Information -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-6">Principal Information</h4>

            <form method="POST" action="{{ route('principal.school.update-principal-info') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Profile Photo -->
                <div class="flex flex-col items-center mb-6">
                    @if(auth()->user()->profile_image)
                        <img src="{{asset('public/storage/' . auth()->user()->profile_image)}}" 
                             alt="Profile Photo" 
                             class="w-32 h-32 object-cover mb-4 rounded-full">
                    @else
                        <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-user text-gray-400 text-4xl"></i>
                        </div>
                    @endif

                    <div class="text-center">
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Update Profile Photo
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" name="name" required 
                               value="{{ auth()->user()->name }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" required 
                               value="{{ auth()->user()->email }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone"
                               value="{{ auth()->user()->phone }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">School</label>
                        <div class="px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p class="text-gray-900">{{ $school->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">Code: {{ $school->code }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ auth()->user()->address }}</textarea>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                    <button type="submit" 
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- School Quick Stats -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-6">School Quick Stats</h4>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $school->total_students }}</p>
                    <p class="text-sm text-gray-600 mt-1">Students</p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $school->total_teachers }}</p>
                    <p class="text-sm text-gray-600 mt-1">Teachers</p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <p class="text-3xl font-bold text-purple-600">{{ $school->established_year }}</p>
                    <p class="text-sm text-gray-600 mt-1">Established</p>
                </div>

                <div class="bg-orange-50 p-4 rounded-lg text-center">
                    <p class="text-3xl font-bold text-orange-600">{{ $school->code }}</p>
                    <p class="text-sm text-gray-600 mt-1">School Code</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-1">School Type</p>
                    <p class="font-medium text-gray-900">{{ ucfirst($school->type) }}</p>
                </div>

                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $school->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($school->status) }}
                    </span>
                </div>

                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-1">Location</p>
                    <p class="font-medium text-gray-900">{{ $school->city ?? 'Not specified' }}</p>
                </div>

                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-1">Assigned Doctor</p>
                    @if($school->assignedDoctor)
                        <p class="font-medium text-gray-900">Dr. {{ $school->assignedDoctor->name }}</p>
                        <p class="text-xs text-gray-500">{{ $school->assignedDoctor->email }}</p>
                    @else
                        <p class="font-medium text-gray-900 text-red-600">No doctor assigned</p>
                    @endif
                </div>
            </div>
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
        // Add file input preview for logo
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.content-card:nth-child(2) img') || 
                                  document.querySelector('.content-card:nth-child(2) .bg-gray-100');
                    if (preview) {
                        if (preview.tagName === 'IMG') {
                            preview.src = e.target.result;
                        } else {
                            preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="w-32 h-32 object-contain rounded-lg">`;
                            preview.classList.remove('bg-gray-100', 'flex', 'items-center', 'justify-center');
                        }
                    }
                }
                reader.readAsDataURL(file);
            }
        });

        // Add file input preview for profile photo
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.content-card:nth-child(3) img') || 
                                  document.querySelector('.content-card:nth-child(3) .bg-gray-100');
                    if (preview) {
                        if (preview.tagName === 'IMG') {
                            preview.src = e.target.result;
                        } else {
                            preview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="w-32 h-32 object-cover rounded-full">`;
                            preview.classList.remove('bg-gray-100', 'flex', 'items-center', 'justify-center');
                        }
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection