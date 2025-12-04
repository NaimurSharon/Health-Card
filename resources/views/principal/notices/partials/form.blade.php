@csrf

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="title" class="form-label">Notice Title *</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                value="{{ old('title', $notice->title ?? '') }}" placeholder="Enter notice title" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content *</label>
            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8"
                placeholder="Enter notice content" required>{{ old('content', $notice->content ?? '') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                        required>
                        <option value="draft" {{ (old('status', $notice->status ?? 'draft') == 'draft') ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ (old('status', $notice->status ?? '') == 'published') ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Priority -->
                <div class="mb-3">
                    <label for="priority" class="form-label">Priority *</label>
                    <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority"
                        required>
                        <option value="low" {{ (old('priority', $notice->priority ?? '') == 'low') ? 'selected' : '' }}>
                            Low</option>
                        <option value="medium" {{ (old('priority', $notice->priority ?? 'medium') == 'medium') ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ (old('priority', $notice->priority ?? '') == 'high') ? 'selected' : '' }}>
                            High</option>
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Target Roles -->
                <div class="mb-3">
                    <label class="form-label">Target Roles *</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_roles[]" value="student"
                            id="role_student" {{ (is_array(old('target_roles', isset($notice) ? $notice->target_roles : [])) && in_array('student', old('target_roles', isset($notice) ? $notice->target_roles : []))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="role_student">Students</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_roles[]" value="teacher"
                            id="role_teacher" {{ (is_array(old('target_roles', isset($notice) ? $notice->target_roles : [])) && in_array('teacher', old('target_roles', isset($notice) ? $notice->target_roles : []))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="role_teacher">Teachers</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_roles[]" value="parent"
                            id="role_parent" {{ (is_array(old('target_roles', isset($notice) ? $notice->target_roles : [])) && in_array('parent', old('target_roles', isset($notice) ? $notice->target_roles : []))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="role_parent">Parents</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_roles[]" value="staff"
                            id="role_staff" {{ (is_array(old('target_roles', isset($notice) ? $notice->target_roles : [])) && in_array('staff', old('target_roles', isset($notice) ? $notice->target_roles : []))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="role_staff">Staff</label>
                    </div>
                    @error('target_roles')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Expiry Date -->
                <div class="mb-3">
                    <label for="expiry_date" class="form-label">Expiry Date *</label>
                    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date"
                        name="expiry_date"
                        value="{{ old('expiry_date', isset($notice) && $notice->expiry_date ? $notice->expiry_date->format('Y-m-d') : '') }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    <small class="text-muted">Notices will expire after this date</small>
                    @error('expiry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        @if(isset($notice) && $notice->exists)
                            <i class="fas fa-save me-1"></i> Update Notice
                        @else
                            <i class="fas fa-plus me-1"></i> Create Notice
                        @endif
                    </button>
                    <a href="{{ route('principal.notices.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-check {
        margin-bottom: 0.5rem;
        padding: 0.25rem 0;
    }

    .form-check-input {
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .card {
            margin-top: 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Set minimum date for expiry date
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minDate = tomorrow.toISOString().split('T')[0];

        const expiryDateInput = document.getElementById('expiry_date');
        if (!expiryDateInput.value) {
            expiryDateInput.min = minDate;
        }

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            const targetRoles = document.querySelectorAll('input[name="target_roles[]"]:checked');
            if (targetRoles.length === 0) {
                e.preventDefault();
                alert('Please select at least one target role.');
                return false;
            }

            const status = document.getElementById('status').value;
            if (status === 'published') {
                if (!confirm('Are you sure you want to publish this notice?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
</script>