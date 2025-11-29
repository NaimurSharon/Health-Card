@extends('layouts.principal')

@section('title', $teacher ? 'Edit Teacher' : 'Add New Teacher')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">
                        {{ $teacher ? 'Edit Teacher' : 'Add New Teacher' }}
                    </h3>
                    <p class="text-gray-600 mt-1">
                        {{ $teacher ? 'Update teacher details' : 'Create a new teacher account' }}
                    </p>
                </div>

                <a href="{{ route('principal.teachers.index') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Teachers
                </a>
            </div>
        </div>
    </div>

    <!-- Teacher Form -->
    <div class="content-card rounded-lg p-4 sm:p-6">

        <form action="{{ $teacher 
                ? route('principal.teachers.update', $teacher->id) 
                : route('principal.teachers.store') }}"
              method="POST" class="space-y-6">

            @csrf
            @if($teacher)
                @method('PUT')
            @endif
            

            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" required
                           value="{{ old('name', $teacher->name ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" required
                           value="{{ old('email', $teacher->email ?? '') }}"
                           @if($teacher) disabled @endif
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone"
                           value="{{ old('phone', $teacher->phone ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>

                    @php
                        $dob = isset($teacher->date_of_birth)
                            ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('Y-m-d')
                            : '';
                    @endphp

                    <input type="date" name="date_of_birth" required
                           value="{{ old('date_of_birth', $dob) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date_of_birth')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                    <select name="gender" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $teacher->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $teacher->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                <textarea name="address" required rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $teacher->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
            </div>


            <!-- Professional Information -->
            <div class="border-t pt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Specialization *</label>
                        <input type="text" name="specialization" required
                               value="{{ old('specialization', $teacher->specialization ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('specialization')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Qualifications *</label>
                        <input type="text" name="qualifications" required
                               value="{{ old('qualifications', $teacher->qualifications ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('qualifications')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    </div>
                </div>
            </div>


            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                <a href="{{ route('principal.teachers.index') }}" 
                   class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                    Cancel
                </a>

                <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    {{ $teacher ? 'Update Teacher' : 'Create Teacher' }}
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

@endsection
