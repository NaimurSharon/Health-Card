<!-- Health Report Form Fields (Reusable Component) -->
@foreach($categories as $category)
<div class="content-card rounded-lg p-6 shadow-sm">
    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">{{ $category->name }}</h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($category->fields as $field)
            @php
                $value = $healthReport->getFieldValue($field->field_name) ?? old($field->field_name);
            @endphp
            
            <div class="@if($field->field_type === 'textarea') md:col-span-2 lg:col-span-3 @endif">
                <label for="{{ $field->field_name }}" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $field->label }}
                    @if($field->is_required) <span class="text-red-500">*</span> @endif
                </label>
                
                @switch($field->field_type)
                    @case('text')
                    @case('number')
                        <input type="{{ $field->field_type }}" 
                               name="{{ $field->field_name }}" 
                               id="{{ $field->field_name }}"
                               value="{{ $value }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               placeholder="{{ $field->placeholder }}"
                               @if($field->is_required) required @endif>
                        @break
                    
                    @case('date')
                        <input type="date" 
                               name="{{ $field->field_name }}" 
                               id="{{ $field->field_name }}"
                               value="{{ $value }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               @if($field->is_required) required @endif>
                        @break
                    
                    @case('select')
                        <select name="{{ $field->field_name }}" 
                                id="{{ $field->field_name }}"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                @if($field->is_required) required @endif>
                            <option value="">Select {{ $field->label }}</option>
                            @if($field->options && is_array($field->options))
                                @foreach($field->options as $option)
                                    <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @break
                    
                    @case('textarea')
                        <textarea name="{{ $field->field_name }}" 
                                  id="{{ $field->field_name }}"
                                  rows="4"
                                  class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                  placeholder="{{ $field->placeholder }}"
                                  @if($field->is_required) required @endif>{{ $value }}</textarea>
                        @break
                    
                    @case('checkbox')
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="{{ $field->field_name }}" 
                                   id="{{ $field->field_name }}"
                                   value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ $value ? 'checked' : '' }}>
                            <label for="{{ $field->field_name }}" class="ml-2 block text-sm text-gray-900">
                                {{ $field->placeholder ?? 'Yes' }}
                            </label>
                        </div>
                        @break
                @endswitch
                
                @error($field->field_name)
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
</div>
@endforeach