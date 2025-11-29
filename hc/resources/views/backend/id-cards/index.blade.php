@extends('layouts.app')

@section('title', 'ID Cards Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">ID Cards Management</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.id-cards.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create ID Card
                </a>
                <a href="{{ route('admin.id-card-templates.index') }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-palette mr-2"></i>Templates
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form action="{{ route('admin.id-cards.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" id="type" 
                            class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">All Types</option>
                        <option value="student" {{ request('type') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ request('type') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="staff" {{ request('type') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="medical" {{ request('type') == 'medical' ? 'selected' : '' }}>Medical</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.id-cards.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- ID Cards Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Card Details</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Holder Information</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($idCards as $idCard)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="ids[]" value="{{ $idCard->id }}" 
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-id-card text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $idCard->card_number }}</div>
                                        <div class="text-sm text-gray-500">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                {{ $idCard->type == 'student' ? 'bg-blue-100 text-blue-800' : 
                                                   ($idCard->type == 'teacher' ? 'bg-green-100 text-green-800' : 
                                                   ($idCard->type == 'staff' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800')) }}">
                                                {{ ucfirst($idCard->type) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $idCard->card_holder_name }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($idCard->student)
                                        Student ID: {{ $idCard->student->student_id }}
                                    @elseif($idCard->user)
                                        Role: {{ ucfirst($idCard->user->role) }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium">Issued: {{ $idCard->issue_date->format('M d, Y') }}</div>
                                    <div class="{{ $idCard->is_expired ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                        Expires: {{ $idCard->expiry_date->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $idCard->status == 'active' ? 'bg-green-100 text-green-800' : 
                                       ($idCard->status == 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($idCard->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.id-cards.show', $idCard) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.id-cards.print', $idCard) }}" 
                                       class="text-green-600 hover:text-green-900 transition-colors" title="Print" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <a href="{{ route('admin.id-cards.edit', $idCard) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.id-cards.destroy', $idCard) }}" method="POST" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this ID card?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-id-card text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No ID cards found</p>
                                <p class="text-sm mt-2">Get started by creating a new ID card.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bulk Actions & Pagination -->
        <div class="px-6 py-4 border-t border-gray-200/60 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <button type="button" id="bulk-print-btn" 
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 text-sm font-medium transition-colors flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    <i class="fas fa-print mr-2"></i>Print Selected
                </button>
                <span id="selected-count" class="text-sm text-gray-500">0 selected</span>
            </div>
            
            @if($idCards->hasPages())
                <div class="flex justify-end">
                    {{ $idCards->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .content-card {
        /*background: rgba(255, 255, 255, 0.8);*/
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
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });

        // Bulk selection functionality
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('input[name="ids[]"]');
        const bulkPrintBtn = document.getElementById('bulk-print-btn');
        const selectedCount = document.getElementById('selected-count');

        function updateBulkActions() {
            const checkedCount = document.querySelectorAll('input[name="ids[]"]:checked').length;
            bulkPrintBtn.disabled = checkedCount === 0;
            selectedCount.textContent = `${checkedCount} selected`;
            selectAll.checked = checkedCount > 0 && checkedCount === checkboxes.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        // Bulk print functionality
        bulkPrintBtn.addEventListener('click', function() {
            const selectedIds = [];
            document.querySelectorAll('input[name="ids[]"]:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length > 0) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.id-cards.bulk-print") }}';
                form.target = '_blank';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }
        });

        // Initialize
        updateBulkActions();
    });
</script>
@endsection