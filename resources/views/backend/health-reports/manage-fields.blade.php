@extends('layouts.app')

@section('title', 'Manage Health Report Fields')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Manage Health Report Fields</h3>
            <div class="flex space-x-3">
                <button type="button" onclick="showCreateFieldModal()"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add New Field
                </button>
                <a href="{{ route('admin.health-reports.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Categories and Fields -->
    @foreach($categories as $category)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-900">{{ $category->name }}</h4>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                {{ $category->fields->count() }} fields
            </span>
        </div>
        
        <div class="space-y-4">
            @forelse($category->fields as $field)
            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            @switch($field->field_type)
                                @case('text')
                                    <i class="fas fa-font text-blue-600"></i>
                                    @break
                                @case('number')
                                    <i class="fas fa-hashtag text-blue-600"></i>
                                    @break
                                @case('date')
                                    <i class="fas fa-calendar text-blue-600"></i>
                                    @break
                                @case('select')
                                    <i class="fas fa-list text-blue-600"></i>
                                    @break
                                @case('checkbox')
                                    <i class="fas fa-check-square text-blue-600"></i>
                                    @break
                                @case('textarea')
                                    <i class="fas fa-align-left text-blue-600"></i>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $field->label }}</div>
                        <div class="text-sm text-gray-500">
                            <span class="capitalize">{{ $field->field_type }}</span>
                            @if($field->is_required)
                                <span class="text-red-500 ml-2">• Required</span>
                            @endif
                            @if(!$field->is_active)
                                <span class="text-orange-500 ml-2">• Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-500">Order: {{ $field->sort_order }}</span>
                    
                    <button onclick="editField({{ $field->id }}, this)"
                           class="text-green-600 hover:text-green-800 bg-white hover:bg-green-50 p-2 rounded-lg transition-all duration-200"
                           title="Edit Field">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    
                    <form action="{{ route('admin.health-report-fields.delete', $field) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-800 bg-white hover:bg-red-50 p-2 rounded-lg transition-all duration-200"
                                title="Delete Field">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-3xl mb-3 opacity-50"></i>
                <p>No fields in this category yet.</p>
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

<!-- Create/Edit Field Modal -->
<div id="fieldModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold" id="modalTitle">Add New Field</h3>
        </div>
        
        <form id="fieldForm" method="POST">
            @csrf
            <div id="formMethod" style="display: none;"></div>
            
            <div class="px-6 py-4 space-y-4">
                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Label -->
                <div>
                    <label for="label" class="block text-sm font-medium text-gray-700 mb-2">Field Label *</label>
                    <input type="text" name="label" id="label" required
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter field label">
                </div>

                <!-- Field Type -->
                <div>
                    <label for="field_type" class="block text-sm font-medium text-gray-700 mb-2">Field Type *</label>
                    <select name="field_type" id="field_type" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">Select Field Type</option>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="select">Dropdown</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="textarea">Text Area</option>
                    </select>
                </div>

                <!-- Field Name -->
                <div>
                    <label for="field_name" class="block text-sm font-medium text-gray-700 mb-2">Field Name *</label>
                    <input type="text" name="field_name" id="field_name" required
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="e.g., weight_kg, vision_left">
                    <p class="text-xs text-gray-500 mt-1">Use lowercase with underscores (no spaces)</p>
                </div>

                <!-- Options (for select fields) -->
                <div id="optionsField" style="display: none;">
                    <label for="options" class="block text-sm font-medium text-gray-700 mb-2">Options (one per line) *</label>
                    <textarea name="options" id="options" rows="4"
                              class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                              placeholder="Enter each option on a new line"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Each line will be treated as a separate option</p>
                </div>

                <!-- Placeholder -->
                <div>
                    <label for="placeholder" class="block text-sm font-medium text-gray-700 mb-2">Placeholder Text</label>
                    <input type="text" name="placeholder" id="placeholder"
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Optional placeholder text">
                </div>

                <!-- Settings -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="0" min="0"
                               class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                    </div>
                    
                    <div class="flex items-center space-x-4 pt-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_required" id="is_required" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_required" class="ml-2 block text-sm text-gray-900">Required</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="hideFieldModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" id="submitButton"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                    Save Field
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
</style>

<script>
    let currentFieldId = null;

    function showCreateFieldModal() {
        currentFieldId = null;
        document.getElementById('modalTitle').textContent = 'Add New Field';
        document.getElementById('fieldForm').action = "{{ route('admin.health-report-fields.create') }}";
        document.getElementById('formMethod').innerHTML = '';
        document.getElementById('fieldForm').reset();
        document.getElementById('optionsField').style.display = 'none';
        document.getElementById('is_active').checked = true;
        document.getElementById('fieldModal').classList.remove('hidden');
    }

    function editField(fieldId, button) {
        currentFieldId = fieldId;
        
        // Show loading state
        const originalHtml = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        // Fetch field data
        fetch(`/admin/health-report-fields/${fieldId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const field = data.field;
                document.getElementById('modalTitle').textContent = 'Edit Field';
                document.getElementById('fieldForm').action = `/admin/health-report-fields/${fieldId}`;
                document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                
                // Populate form fields
                document.getElementById('category_id').value = field.category_id;
                document.getElementById('label').value = field.label;
                document.getElementById('field_type').value = field.field_type;
                document.getElementById('field_name').value = field.field_name;
                document.getElementById('placeholder').value = field.placeholder || '';
                document.getElementById('sort_order').value = field.sort_order;
                document.getElementById('is_required').checked = field.is_required;
                document.getElementById('is_active').checked = field.is_active;
                
                // Handle options
                if (field.field_type === 'select' && field.options) {
                    document.getElementById('options').value = Array.isArray(field.options) ? 
                        field.options.join('\n') : field.options;
                    document.getElementById('optionsField').style.display = 'block';
                } else {
                    document.getElementById('optionsField').style.display = 'none';
                }
                
                document.getElementById('fieldModal').classList.remove('hidden');
            } else {
                throw new Error(data.message || 'Failed to load field data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading field data: ' + error.message, 'error');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalHtml;
            button.disabled = false;
        });
    }

    function hideFieldModal() {
        document.getElementById('fieldModal').classList.add('hidden');
        currentFieldId = null;
    }

    // Show/hide options field based on field type
    document.getElementById('field_type').addEventListener('change', function() {
        const optionsField = document.getElementById('optionsField');
        if (this.value === 'select') {
            optionsField.style.display = 'block';
        } else {
            optionsField.style.display = 'none';
            document.getElementById('options').value = '';
        }
    });

    // Handle form submission
    document.getElementById('fieldForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitButton = document.getElementById('submitButton');
        const originalText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitButton.disabled = true;
        
        // For select fields, ensure options are properly formatted
        if (document.getElementById('field_type').value === 'select') {
            const optionsText = document.getElementById('options').value;
            const optionsArray = optionsText.split('\n')
                .map(opt => opt.trim())
                .filter(opt => opt !== '');
            
            if (optionsArray.length === 0) {
                showNotification('Please provide at least one option for the select field.', 'error');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                return;
            }
        }
        
        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    location.reload(); // Reload to show changes
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to save field');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error: ' + error.message, 'error');
        })
        .finally(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });

    // Handle delete forms
    document.addEventListener('submit', function(e) {
        if (e.target.matches('form[action*="health-report-fields"]') && e.target.method === 'POST') {
            e.preventDefault();
            const form = e.target;
            const deleteButton = form.querySelector('button[type="submit"]');
            
            if (confirm('Are you sure you want to delete this field? This action cannot be undone.')) {
                const originalHtml = deleteButton.innerHTML;
                
                // Show loading state
                deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                deleteButton.disabled = true;
                
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Failed to delete field');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error: ' + error.message, 'error');
                })
                .finally(() => {
                    deleteButton.innerHTML = originalHtml;
                    deleteButton.disabled = false;
                });
            }
        }
    });

    // Close modal when clicking outside
    document.getElementById('fieldModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideFieldModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideFieldModal();
        }
    });

    // Notification function
    function showNotification(message, type) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Remove notification after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
</script>
@endsection