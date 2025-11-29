@extends('layouts.app')

@section('title', isset($template) ? 'Edit ID Card Template' : 'Create ID Card Template')

@section('content')
<div class="space-y-6">
    <!-- Template Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ isset($template) ? 'Edit ID Card Template' : 'Create ID Card Template' }}</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.id-card-templates.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
                <button type="submit" form="template-form" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ isset($template) ? 'Update Template' : 'Create Template' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Template Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="template-form" 
              action="{{ isset($template) ? route('admin.id-card-templates.update', $template) : route('admin.id-card-templates.store') }}"
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @if(isset($template))
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Template Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name *</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $template->name ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter template name"
                                   required>
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Card Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Card Type *</label>
                            <select name="type" id="type" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    required>
                                <option value="">Select Type</option>
                                <option value="student" {{ old('type', $template->type ?? '') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ old('type', $template->type ?? '') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="staff" {{ old('type', $template->type ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="medical" {{ old('type', $template->type ?? '') == 'medical' ? 'selected' : '' }}>Medical</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dimensions & Layout Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Dimensions & Layout</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Width -->
                        <div>
                            <label for="width" class="block text-sm font-medium text-gray-700 mb-2">Width (mm) *</label>
                            <input type="number" name="width" id="width" 
                                   value="{{ old('width', $template->width ?? 100) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   min="50" max="200" step="0.01" required>
                            @error('width')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 space-y-1">
                                <button type="button" class="preset-btn text-xs text-blue-600 hover:text-blue-700 mr-3" data-width="85.6" data-height="53.98">
                                    💳 Credit Card
                                </button>
                                <button type="button" class="preset-btn text-xs text-green-600 hover:text-green-700 mr-3" data-width="100" data-height="65">
                                    📄 Business Card
                                </button>
                                <button type="button" class="preset-btn text-xs text-purple-600 hover:text-purple-700" data-width="88.9" data-height="50.8">
                                    🇺🇸 US Standard
                                </button>
                            </div>
                        </div>
                    
                        <!-- Height -->
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-2">Height (mm) *</label>
                            <input type="number" name="height" id="height" 
                                   value="{{ old('height', $template->height ?? 65) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   min="50" max="200" step="0.01" required>
                            @error('height')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <!-- Orientation -->
                        <div>
                            <label for="orientation" class="block text-sm font-medium text-gray-700 mb-2">Orientation *</label>
                            <select name="orientation" id="orientation" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    required>
                                <option value="landscape" {{ old('orientation', $template->orientation ?? 'landscape') == 'landscape' ? 'selected' : '' }}>Landscape</option>
                                <option value="portrait" {{ old('orientation', $template->orientation ?? '') == 'portrait' ? 'selected' : '' }}>Portrait</option>
                            </select>
                            @error('orientation')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <!-- Enhanced Dimensions Preview -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h5 class="text-lg font-bold text-gray-900">Live Card Preview</h5>
                                <p class="text-sm text-gray-600">Real-time visualization of your ID card dimensions</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-700" id="dimensions-preview">
                                    {{ old('width', $template->width ?? 100) }} × {{ old('height', $template->height ?? 65) }} mm
                                </div>
                                <div class="text-sm text-gray-500" id="pixel-equivalent">
                                    <!-- Pixel equivalent will be calculated by JS -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col lg:flex-row gap-8 items-center justify-center">
                            <!-- Visual Card Preview -->
                            <div class="flex-1 max-w-md">
                                <div class="bg-white rounded-2xl p-6 border-2 border-blue-300 shadow-lg">
                                    <div class="text-center mb-4">
                                        <div class="text-sm font-semibold text-gray-700 mb-2">Card Visualization</div>
                                        <div class="text-xs text-gray-500">Scale: 1mm ≈ 3.78px</div>
                                    </div>
                                    
                                    <div class="relative mx-auto bg-gradient-to-br from-white to-gray-50 border-2 border-gray-300 rounded-xl shadow-inner overflow-hidden"
                                         id="dimensions-visual">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="text-center p-4">
                                                <div id="orientation-icon" class="mb-2">
                                                    <i class="fas fa-id-card text-3xl text-blue-500"></i>
                                                </div>
                                                <div id="dimensions-text" class="text-sm font-bold text-gray-700">
                                                    {{ old('width', $template->width ?? 100) }} × {{ old('height', $template->height ?? 65) }} mm
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1" id="aspect-ratio">
                                                    <!-- Aspect ratio will be calculated by JS -->
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Measurement markers -->
                                        <div class="absolute top-2 left-1/2 transform -translate-x-1/2">
                                            <div class="flex items-center text-xs text-gray-600">
                                                <div class="w-4 border-t border-gray-400 mr-1"></div>
                                                <span id="width-marker">Width</span>
                                                <div class="w-4 border-t border-gray-400 ml-1"></div>
                                            </div>
                                        </div>
                                        <div class="absolute left-2 top-1/2 transform -translate-y-1/2 -rotate-90">
                                            <div class="flex items-center text-xs text-gray-600">
                                                <div class="w-4 border-t border-gray-400 mr-1"></div>
                                                <span id="height-marker">Height</span>
                                                <div class="w-4 border-t border-gray-400 ml-1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Size Information -->
                            <div class="flex-1 max-w-md">
                                <div class="space-y-4">
                                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                                        <h6 class="font-semibold text-gray-900 mb-3">Size Information</h6>
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Physical Size:</span>
                                                <span class="font-semibold text-gray-900" id="physical-size">
                                                    {{ old('width', $template->width ?? 100) }}mm × {{ old('height', $template->height ?? 65) }}mm
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Aspect Ratio:</span>
                                                <span class="font-semibold text-gray-900" id="ratio-display">
                                                    <!-- Calculated by JS -->
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">Print Resolution:</span>
                                                <span class="font-semibold text-green-600" id="print-resolution">
                                                    <!-- Calculated by JS -->
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                                        <h6 class="font-semibold text-yellow-800 mb-2">💡 Pro Tips</h6>
                                        <ul class="text-xs text-yellow-700 space-y-1">
                                            <li>• Standard credit card: 85.6 × 53.98 mm</li>
                                            <li>• Business cards: 85 × 55 mm or 90 × 50 mm</li>
                                            <li>• Use landscape for most ID cards</li>
                                            <li>• Ensure background image matches card dimensions</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Background Image Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Background Image</h4>
                    
                    <div class="space-y-4">
                        <!-- File Upload -->
                        <div>
                            <label for="background_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Background Image *
                                @if(isset($template) && $template->background_image)
                                    <span class="text-sm font-normal text-gray-500">(Current image will be replaced)</span>
                                @endif
                            </label>
                            <div class="flex items-center space-x-4">
                                <label for="background_image" 
                                       class="cursor-pointer bg-white border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-all duration-200 w-full group">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3 group-hover:text-blue-500 transition-colors"></i>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium text-blue-600">Click to upload</span> or drag and drop
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        PNG, JPG, GIF, WEBP up to 5MB • Recommended: <span id="recommended-size">Match card dimensions</span>
                                    </p>
                                    <input type="file" name="background_image" id="background_image" 
                                           class="hidden"
                                           {{ isset($template) ? '' : 'required' }}
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                </label>
                            </div>
                            @error('background_image')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image Preview -->
                        @if(isset($template) && $template->background_image)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Current Image</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ $template->background_image_url }}" 
                                         alt="{{ $template->name }}" 
                                         class="h-24 w-32 object-cover rounded-lg border shadow-sm">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600">
                                        This is the current background image for the template.
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Upload a new image to replace it.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Settings Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Additional Settings</h4>
                    
                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    placeholder="Enter template description...">{{ old('description', $template->description ?? '') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        @if(isset($template))
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Template Status</label>
                                <p class="text-sm text-gray-500">
                                    Inactive templates cannot be selected for new ID cards.
                                </p>
                            </div>
                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" id="is_active" 
                                           class="sr-only peer"
                                           {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">
                                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </label>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.id-card-templates.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ isset($template) ? 'Update Template' : 'Create Template' }}
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
    
    .preset-btn {
        transition: all 0.2s ease;
    }
    
    .preset-btn:hover {
        transform: translateY(-1px);
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

        // Calculate pixel equivalent (1mm ≈ 3.78px for print)
        function calculatePixelEquivalent(mm) {
            return Math.round(mm * 3.78);
        }

        // Calculate aspect ratio
        function calculateAspectRatio(width, height) {
            const gcd = (a, b) => b === 0 ? a : gcd(b, a % b);
            const divisor = gcd(width, height);
            return `${width/divisor}:${height/divisor}`;
        }

        // Update dimensions preview in real-time
        function updateDimensionsPreview() {
            const width = parseFloat(document.getElementById('width').value) || 100;
            const height = parseFloat(document.getElementById('height').value) || 65;
            const orientation = document.getElementById('orientation').value;
            
            // Calculate values
            const pixelWidth = calculatePixelEquivalent(width);
            const pixelHeight = calculatePixelEquivalent(height);
            const aspectRatio = calculateAspectRatio(width, height);
            const ratioValue = (width / height).toFixed(2);
            
            // Update text previews
            document.getElementById('dimensions-preview').textContent = `${width} × ${height} mm`;
            document.getElementById('dimensions-text').textContent = `${width} × ${height} mm`;
            document.getElementById('physical-size').textContent = `${width}mm × ${height}mm`;
            document.getElementById('ratio-display').textContent = `${aspectRatio} (${ratioValue}:1)`;
            document.getElementById('pixel-equivalent').textContent = `≈ ${pixelWidth} × ${pixelHeight} pixels`;
            document.getElementById('print-resolution').textContent = `${pixelWidth}px × ${pixelHeight}px`;
            
            // Update recommended image size
            document.getElementById('recommended-size').textContent = `${pixelWidth} × ${pixelHeight} pixels`;
            
            // Update aspect ratio display
            document.getElementById('aspect-ratio').textContent = `Aspect Ratio: ${aspectRatio}`;
            
            // Update visual preview
            const visual = document.getElementById('dimensions-visual');
            const maxWidth = 300; // Maximum visual width in pixels
            
            if (orientation === 'landscape') {
                const visualWidth = Math.min(maxWidth, pixelWidth);
                const visualHeight = (visualWidth / pixelWidth) * pixelHeight;
                visual.style.width = visualWidth + 'px';
                visual.style.height = visualHeight + 'px';
                document.getElementById('width-marker').textContent = `${width}mm`;
                document.getElementById('height-marker').textContent = `${height}mm`;
            } else {
                const visualHeight = Math.min(maxWidth, pixelHeight);
                const visualWidth = (visualHeight / pixelHeight) * pixelWidth;
                visual.style.width = visualWidth + 'px';
                visual.style.height = visualHeight + 'px';
                document.getElementById('width-marker').textContent = `${height}mm`;
                document.getElementById('height-marker').textContent = `${width}mm`;
            }
        }

        // Add event listeners for dimension inputs
        document.getElementById('width').addEventListener('input', updateDimensionsPreview);
        document.getElementById('height').addEventListener('input', updateDimensionsPreview);
        document.getElementById('orientation').addEventListener('change', updateDimensionsPreview);

        // Preset size buttons
        document.querySelectorAll('.preset-btn').forEach(button => {
            button.addEventListener('click', function() {
                const width = this.dataset.width;
                const height = this.dataset.height;
                
                document.getElementById('width').value = width;
                document.getElementById('height').value = height;
                
                updateDimensionsPreview();
                
                // Add visual feedback
                this.classList.add('animate-pulse');
                setTimeout(() => {
                    this.classList.remove('animate-pulse');
                }, 500);
            });
        });

        // File upload preview
        document.getElementById('background_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const label = this.closest('label');
                const icon = label.querySelector('i');
                const text = label.querySelector('div');
                
                icon.className = 'fas fa-check text-4xl text-green-500 mb-3';
                text.innerHTML = `
                    <div class="font-medium text-green-600 text-sm">${file.name}</div>
                    <p class="text-xs text-gray-500 mt-2">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                `;
                
                // Preview image dimensions if possible
                const img = new Image();
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    img.onload = function() {
                        const sizeInfo = document.createElement('p');
                        sizeInfo.className = 'text-xs text-blue-600 mt-1';
                        sizeInfo.textContent = `Image: ${this.width} × ${this.height}px`;
                        label.querySelector('div').appendChild(sizeInfo);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Toggle switch label update
        const toggleSwitch = document.getElementById('is_active');
        if (toggleSwitch) {
            const toggleLabel = toggleSwitch.nextElementSibling;
            toggleSwitch.addEventListener('change', function() {
                toggleLabel.textContent = this.checked ? 'Active' : 'Inactive';
            });
        }

        // Initialize preview
        updateDimensionsPreview();
        
        // Add keyboard shortcuts for quick adjustments
        document.addEventListener('keydown', function(e) {
            const widthInput = document.getElementById('width');
            const heightInput = document.getElementById('height');
            
            if (e.altKey) {
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    widthInput.stepUp();
                    updateDimensionsPreview();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    widthInput.stepDown();
                    updateDimensionsPreview();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    heightInput.stepUp();
                    updateDimensionsPreview();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    heightInput.stepDown();
                    updateDimensionsPreview();
                }
            }
        });
    });
</script>
@endsection