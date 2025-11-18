@extends('layouts.backend')

@section('title', 'Record Salary Payment')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Record Salary Payment</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Record salary payment for staff members</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('admin.salary-payments.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Payments
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.salary-payments.store') }}" method="POST" class="p-4 sm:p-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Basic Information
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Staff Member *</label>
                            <select name="staff_id" id="staff_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('staff_id') border-red-500 @enderror">
                                <option value="">Select Staff Member</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ old('staff_id') == $member->id ? 'selected' : '' }}
                                            data-salary="{{ $member->salary }}">
                                        {{ $member->getFullNameAttribute() }} - {{ $member->role }} ({{ system('currency') }} {{ number_format($member->salary, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Date *</label>
                                <input type="date" name="payment_date" id="payment_date" 
                                       value="{{ old('payment_date', now()->format('Y-m-d')) }}" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('payment_date') border-red-500 @enderror" required>
                                @error('payment_date')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="month_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Period *</label>
                                <input type="month" name="month_year" id="month_year" 
                                       value="{{ old('month_year', now()->format('Y-m')) }}" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('month_year') border-red-500 @enderror" required>
                                @error('month_year')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method *</label>
                            <select name="payment_method" id="payment_method" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('payment_method') border-red-500 @enderror" required>
                                <option value="bank_transfer" {{ old('payment_method', 'bank_transfer') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                                <option value="digital_wallet" {{ old('payment_method') == 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                            </select>
                            @error('payment_method')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Details -->
            <div class="space-y-4">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-calculator mr-2 text-green-500"></i>
                        Salary Details
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="basic_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Basic Salary *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">{{ system('currency') }}</span>
                                </div>
                                <input type="number" step="0.01" name="basic_salary" id="basic_salary" 
                                       value="{{ old('basic_salary') }}" 
                                       class="pl-12 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('basic_salary') border-red-500 @enderror" 
                                       placeholder="0.00" required>
                            </div>
                            @error('basic_salary')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bonus</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">{{ system('currency') }}</span>
                                </div>
                                <input type="number" step="0.01" name="bonus" id="bonus" 
                                       value="{{ old('bonus', 0) }}" 
                                       class="pl-12 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('bonus') border-red-500 @enderror" 
                                       placeholder="0.00">
                            </div>
                            @error('bonus')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="deductions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deductions</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">{{ system('currency') }}</span>
                                </div>
                                <input type="number" step="0.01" name="deductions" id="deductions" 
                                       value="{{ old('deductions', 0) }}" 
                                       class="pl-12 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('deductions') border-red-500 @enderror" 
                                       placeholder="0.00">
                            </div>
                            @error('deductions')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Net Salary Display -->
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Net Salary:</span>
                                <span id="netSalaryDisplay" class="text-xl font-bold text-green-600 dark:text-green-400">{{ system('currency') }} 0.00</span>
                            </div>
                            <input type="hidden" name="net_salary" id="net_salary" value="0">
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Basic Salary + Bonus - Deductions
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 sm:p-6">
            <h4 class="text-sm font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                Additional Information
            </h4>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror" 
                          placeholder="Additional notes about this payment...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 pt-6 border-t dark:border-gray-700 mt-6">
            <a href="{{ route('admin.salary-payments.index') }}" 
               class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Record Payment
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const staffSelect = document.getElementById('staff_id');
    const basicSalaryInput = document.getElementById('basic_salary');
    const bonusInput = document.getElementById('bonus');
    const deductionsInput = document.getElementById('deductions');
    const netSalaryDisplay = document.getElementById('netSalaryDisplay');
    const netSalaryInput = document.getElementById('net_salary');

    function calculateNetSalary() {
        const basicSalary = parseFloat(basicSalaryInput.value) || 0;
        const bonus = parseFloat(bonusInput.value) || 0;
        const deductions = parseFloat(deductionsInput.value) || 0;
        
        const netSalary = basicSalary + bonus - deductions;
        
        netSalaryDisplay.textContent = '{{ system('currency') }} ' + netSalary.toFixed(2);
        netSalaryInput.value = netSalary.toFixed(2);
    }

    // Auto-fill basic salary when staff is selected
    staffSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const salary = selectedOption.dataset.salary;
        if (salary) {
            basicSalaryInput.value = salary;
            calculateNetSalary();
        }
    });

    // Recalculate net salary when any amount changes
    [basicSalaryInput, bonusInput, deductionsInput].forEach(input => {
        input.addEventListener('input', calculateNetSalary);
    });

    // Initial calculation
    calculateNetSalary();
});
</script>
@endpush