@extends('layouts.app')

@section('title', isset($school) ? 'Edit School' : 'Add New School')

@section('content')
<div class="space-y-6">
    <!-- School Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ ($school ?? null)?->exists ? 'Edit School' : 'Add New School' }}</h3>
            <button type="submit" form="school-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ ($school ?? null)?->exists ? 'Update School' : 'Create School' }}
            </button>
        </div>
    </div>

    <!-- School Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="school-form" 
              action="{{ ($school ?? null)?->exists ? route('admin.schools.update', ($school ?? null)) : route('admin.schools.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(($school ?? null)?->exists)
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- School Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">School Name *</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', ($school->name ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter school name">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- School Code -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">School Code *</label>
                            <input type="text" name="code" id="code" 
                                   value="{{ old('code', ($school->code ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter unique school code">
                            @error('code')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- School Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">School Type *</label>
                            <select name="type" id="type" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Type</option>
                                <option value="government" {{ old('type', ($school->type ?? '')) == 'government' ? 'selected' : '' }}>Government</option>
                                <option value="private" {{ old('type', ($school->type ?? '')) == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="madrasa" {{ old('type', ($school->type ?? '')) == 'madrasa' ? 'selected' : '' }}>Madrasa</option>
                                <option value="international" {{ old('type', ($school->type ?? '')) == 'international' ? 'selected' : '' }}>International</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Established Year -->
                        <div>
                            <label for="established_year" class="block text-sm font-medium text-gray-700 mb-2">Established Year</label>
                            <input type="number" name="established_year" id="established_year" 
                                   value="{{ old('established_year', ($school->established_year ?? '')) }}"
                                   min="1900" max="{{ date('Y') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., 1990">
                            @error('established_year')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', ($school->status ?? '')) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', ($school->status ?? '')) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', ($school->email ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter school email">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" id="phone" 
                                   value="{{ old('phone', ($school->phone ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter school phone number">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" name="website" id="website" 
                               value="{{ old('website', ($school->website ?? '')) }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               placeholder="https://example.com">
                        @error('website')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Location Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Division -->
                        <div>
                            <label for="division" class="block text-sm font-medium text-gray-700 mb-2">Division *</label>
                            <input type="text" name="division" id="division" 
                                   value="{{ old('division', ($school->division ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., Dhaka, Chattogram">
                            @error('division')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- District -->
                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-2">District *</label>
                            <input type="text" name="district" id="district" 
                                   value="{{ old('district', ($school->district ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., Dhaka, Chattogram">
                            @error('district')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" name="city" id="city" 
                                   value="{{ old('city', ($school->city ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., Dhaka, Chattogram City">
                            @error('city')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Full Address *</label>
                        <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter complete school address">{{ old('address', ($school->address ?? '')) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Administration Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Administration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Principal Name -->
                        <div>
                            <label for="principal_name" class="block text-sm font-medium text-gray-700 mb-2">Principal Name *</label>
                            <input type="text" name="principal_name" id="principal_name" 
                                   value="{{ old('principal_name', ($school->principal_name ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter principal's full name">
                            @error('principal_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Principal Phone -->
                        <div>
                            <label for="principal_phone" class="block text-sm font-medium text-gray-700 mb-2">Principal Phone</label>
                            <input type="tel" name="principal_phone" id="principal_phone" 
                                   value="{{ old('principal_phone', ($school->principal_phone ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter principal's phone">
                            @error('principal_phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Principal Email -->
                        <div>
                            <label for="principal_email" class="block text-sm font-medium text-gray-700 mb-2">Principal Email</label>
                            <input type="email" name="principal_email" id="principal_email" 
                                   value="{{ old('principal_email', ($school->principal_email ?? '')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter principal's email">
                            @error('principal_email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Academic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Academic System -->
                        <div>
                            <label for="academic_system" class="block text-sm font-medium text-gray-700 mb-2">Academic System</label>
                            <select name="academic_system" id="academic_system" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select System</option>
                                <option value="national" {{ old('academic_system', ($school->academic_system ?? '')) == 'national' ? 'selected' : '' }}>National</option>
                                <option value="cambridge" {{ old('academic_system', ($school->academic_system ?? '')) == 'cambridge' ? 'selected' : '' }}>Cambridge</option>
                                <option value="ib" {{ old('academic_system', ($school->academic_system ?? '')) == 'ib' ? 'selected' : '' }}>IB</option>
                                <option value="other" {{ old('academic_system', ($school->academic_system ?? '')) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('academic_system')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Medium -->
                        <div>
                            <label for="medium" class="block text-sm font-medium text-gray-700 mb-2">Medium</label>
                            <select name="medium" id="medium" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Medium</option>
                                <option value="bangla" {{ old('medium', ($school->medium ?? '')) == 'bangla' ? 'selected' : '' }}>Bangla</option>
                                <option value="english" {{ old('medium', ($school->medium ?? '')) == 'english' ? 'selected' : '' }}>English</option>
                                <option value="both" {{ old('medium', ($school->medium ?? '')) == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                            @error('medium')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campus Area -->
                        <div>
                            <label for="campus_area" class="block text-sm font-medium text-gray-700 mb-2">Campus Area (sq ft)</label>
                            <input type="number" name="campus_area" id="campus_area" 
                                   value="{{ old('campus_area', ($school->campus_area ?? '')) }}"
                                   step="0.01" min="0"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., 50000">
                            @error('campus_area')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Students -->
                        <div>
                            <label for="total_students" class="block text-sm font-medium text-gray-700 mb-2">Total Students</label>
                            <input type="number" name="total_students" id="total_students" 
                                   value="{{ old('total_students', ($school->total_students ?? 0)) }}"
                                   min="0"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('total_students')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total Teachers -->
                        <div>
                            <label for="total_teachers" class="block text-sm font-medium text-gray-700 mb-2">Total Teachers</label>
                            <input type="number" name="total_teachers" id="total_teachers" 
                                   value="{{ old('total_teachers', ($school->total_teachers ?? 0)) }}"
                                   min="0"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('total_teachers')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total Staff -->
                        <div>
                            <label for="total_staff" class="block text-sm font-medium text-gray-700 mb-2">Total Staff</label>
                            <input type="number" name="total_staff" id="total_staff" 
                                   value="{{ old('total_staff', ($school->total_staff ?? 0)) }}"
                                   min="0"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('total_staff')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- School Identity Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">School Identity</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Motto -->
                        <div>
                            <label for="motto" class="block text-sm font-medium text-gray-700 mb-2">School Motto</label>
                            <textarea name="motto" id="motto" rows="2"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    placeholder="Enter school motto">{{ old('motto', ($school->motto ?? '')) }}</textarea>
                            @error('motto')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vision -->
                        <div>
                            <label for="vision" class="block text-sm font-medium text-gray-700 mb-2">Vision</label>
                            <textarea name="vision" id="vision" rows="2"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    placeholder="Enter school vision">{{ old('vision', ($school->vision ?? '')) }}</textarea>
                            @error('vision')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Mission -->
                    <div>
                        <label for="mission" class="block text-sm font-medium text-gray-700 mb-2">Mission</label>
                        <textarea name="mission" id="mission" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter school mission">{{ old('mission', ($school->mission ?? '')) }}</textarea>
                        @error('mission')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Media & Files Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Media & Files</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Logo -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">School Logo</label>
                            <input type="file" name="logo" id="logo" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('logo')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            @if(isset($school) && $school->logo)
                                <p class="mt-2 text-sm text-gray-500">Current: {{ basename($school->logo) }}</p>
                            @endif
                        </div>

                        <!-- Cover Image -->
                        <div>
                            <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                            <input type="file" name="cover_image" id="cover_image" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('cover_image')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            @if(isset($school) && $school->cover_image)
                                <p class="mt-2 text-sm text-gray-500">Current: {{ basename($school->cover_image) }}</p>
                            @endif
                        </div>

                        <!-- School Image -->
                        <div>
                            <label for="school_image" class="block text-sm font-medium text-gray-700 mb-2">School Image</label>
                            <input type="file" name="school_image" id="school_image" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('school_image')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            @if(isset($school) && $school->school_image)
                                <p class="mt-2 text-sm text-gray-500">Current: {{ basename($school->school_image) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Additional Information</h4>
                    
                    <!-- Facilities -->
                    <div>
                        <label for="facilities" class="block text-sm font-medium text-gray-700 mb-2">Facilities (comma separated)</label>
                        <textarea name="facilities" id="facilities" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="e.g., library, computer_lab, science_lab, sports_ground">{{ old('facilities', ($school->facilities ? implode(', ', json_decode($school->facilities)) : '')) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter facilities as comma separated values. They will be stored as JSON array.</p>
                        @error('facilities')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Accreditations -->
                    <div>
                        <label for="accreditations" class="block text-sm font-medium text-gray-700 mb-2">Accreditations (comma separated)</label>
                        <textarea name="accreditations" id="accreditations" rows="2"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="e.g., ministry_of_education, cambridge_international">{{ old('accreditations', ($school->accreditations ? implode(', ', json_decode($school->accreditations)) : '')) }}</textarea>
                        @error('accreditations')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.schools.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ ($school ?? null)?->exists ? 'Update School' : 'Create School' }}
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

        // Auto-generate school code from name
        const nameInput = document.getElementById('name');
        const codeInput = document.getElementById('code');
        
        if (nameInput && codeInput && !codeInput.value) {
            nameInput.addEventListener('blur', function() {
                if (!codeInput.value && this.value) {
                    // Generate code from name (first 3 letters of each word, uppercase)
                    const code = this.value
                        .split(' ')
                        .map(word => word.substring(0, 3).toUpperCase())
                        .join('');
                    codeInput.value = code + '-' + Math.floor(100 + Math.random() * 900);
                }
            });
        }

        // Set current year as default for established year if empty
        const establishedYearInput = document.getElementById('established_year');
        if (establishedYearInput && !establishedYearInput.value) {
            establishedYearInput.value = new Date().getFullYear();
        }
    });
</script>
@endsection