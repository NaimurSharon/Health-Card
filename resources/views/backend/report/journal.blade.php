@extends('layouts.backend')

@section('title', 'General Journal')

@section('content')
<div class="space-y-6">
    <!-- Report Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">General Journal</h2>
                <p class="text-gray-600 dark:text-gray-400">Double-entry accounting journal with debit and credit entries</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <form method="GET" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" 
                           class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" 
                           class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm transition-colors">
                        Filter
                    </button>
                </form>
                <a href="{{ route('admin.journal.export', request()->query()) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-file-export mr-2"></i>Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 sm:p-6 text-center border border-green-200 dark:border-green-800">
            <div class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">
                {{ system('currency') }} {{ number_format($totalDebit, 2) }}
            </div>
            <div class="text-xs sm:text-sm text-green-600 dark:text-green-400 mt-1">Total Debit</div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 sm:p-6 text-center border border-red-200 dark:border-red-800">
            <div class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">
                {{ system('currency') }} {{ number_format($totalCredit, 2) }}
            </div>
            <div class="text-xs sm:text-sm text-red-600 dark:text-red-400 mt-1">Total Credit</div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 sm:p-6 text-center border border-blue-200 dark:border-blue-800">
            <div class="text-xl sm:text-2xl font-bold {{ $totalDebit == $totalCredit ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                {{ system('currency') }} {{ number_format(abs($totalDebit - $totalCredit), 2) }}
            </div>
            <div class="text-xs sm:text-sm {{ $totalDebit == $totalCredit ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }} mt-1">
                {{ $totalDebit == $totalCredit ? 'Balanced' : 'Difference' }}
            </div>
        </div>
    </div>

    <!-- Journal Entries Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <!-- Desktop Table View -->
<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Particular</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reference</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Debit ({{ setting('currency') }})</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Credit ({{ setting('currency') }})</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800">
            @php
                $currentGroup = null;
                $groupIndex = 0;
            @endphp
            
            @foreach($entries->groupBy('entry_group') as $group => $groupEntries)
                @php 
                    $firstEntry = $groupEntries->first();
                    $debitEntry = $groupEntries->where('entry_type', 'debit')->first();
                    $creditEntry = $groupEntries->where('entry_type', 'credit')->first();
                    $groupIndex++;
                @endphp
                
                <!-- Debit Entry Row -->
                @if($debitEntry)
                <tr class="">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::parse($firstEntry->transaction_date)->format('d-m-Y') }}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-white pl-8">
                        {{ $debitEntry->particular }}
                    </td>
                    <td class="px-4 py-2 text-sm text-center text-gray-600 dark:text-gray-400">
                        {{ $debitEntry->reference ?? '-' }}
                    </td>
                    <td class="px-4 py-2 text-sm text-right font-medium text-green-600 dark:text-green-400">
                        {{ number_format($debitEntry->amount, 2) }}
                    </td>
                    <td class="px-4 py-2 text-sm text-right font-medium text-gray-500 dark:text-gray-400">
                        -
                    </td>
                </tr>
                @endif

                <!-- Credit Entry Row -->
                @if($creditEntry)
                <tr class="">
                    <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <!-- Empty for subsequent entries -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-white pl-8">
                        {{ $creditEntry->particular }}
                    </td>
                    <td class="px-4 py-2 text-sm text-center text-gray-600 dark:text-gray-400">
                        {{ $creditEntry->reference ?? '-' }}
                    </td>
                    <td class="px-4 py-2 text-sm text-right font-medium text-gray-500 dark:text-gray-400">
                        -
                    </td>
                    <td class="px-4 py-2 text-sm text-right font-medium text-red-600 dark:text-red-400">
                        {{ number_format($creditEntry->amount, 2) }}
                    </td>
                </tr>
                @endif

                <!-- Payment Methods Breakdown -->
                @if(isset($firstEntry->payment_methods) && count($firstEntry->payment_methods) > 0)
                <tr class=" bg-gray-50 dark:bg-gray-700/30">
                    <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <!-- Empty for payment methods -->
                    </td>
                    <td colspan="4" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 pl-8">
                        <div class="flex items-center ml-3 gap-4">
                            <span class="font-medium">Payment Methods:</span>
                            @foreach($firstEntry->payment_methods as $payment)
                            @php
                                $methodIcons = [
                                    'cash' => 'money-bill',
                                    'bank_transfer' => 'university',
                                    'bank' => 'university',
                                    'mobile_banking' => 'mobile-alt',
                                    'card' => 'credit-card',
                                    'credit' => 'hand-holding-usd',
                                ];
                                $icon = $methodIcons[$payment['method']] ?? 'money-check';
                            @endphp
                            <div class="flex items-center">
                                <i class="fas fa-{{ $icon }} mr-1 text-gray-500 text-xs"></i>
                                <span class="text-xs capitalize">{{ str_replace('_', ' ', $payment['method']) }}</span>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300 ml-1">
                                    ({{ setting('currency') }}{{ number_format($payment['amount'], 2) }})
                                </span>
                            </div>
                            @endforeach
                            <div class="flex items-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                                <span>Total: {{ setting('currency') }}{{ number_format($firstEntry->payment_methods->sum('amount'), 2) }}</span>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif

                <!-- Empty spacer row between transactions -->
                <tr>
                    <td colspan="5" class="py-2"></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-300 dark:border-gray-500">
            <tr>
                <td colspan="3" class="px-4 py-4 text-sm font-bold text-gray-800 dark:text-white text-right">Total</td>
                <td class="px-4 py-4 text-sm font-bold text-green-600 dark:text-green-400 text-right">
                    {{ number_format($totalDebit, 2) }}
                </td>
                <td class="px-4 py-4 text-sm font-bold text-red-600 dark:text-red-400 text-right">
                    {{ number_format($totalCredit, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Mobile Card View -->
<div class="block sm:hidden">
    @php
        $currentGroup = null;
        $groupIndex = 0;
    @endphp
    
    @foreach($entries->groupBy('entry_group') as $group => $groupEntries)
        @php 
            $firstEntry = $groupEntries->first();
            $debitEntry = $groupEntries->where('entry_type', 'debit')->first();
            $creditEntry = $groupEntries->where('entry_type', 'credit')->first();
            $groupIndex++;
        @endphp
        
        <!-- Single Card for Each Transaction Group -->
        <div class="p-4 ">
            <!-- Header -->
            <div class="mb-3 pb-2 border-b dark:border-gray-600">
                <div class="font-medium text-gray-800 dark:text-white">
                    {{ \Carbon\Carbon::parse($firstEntry->transaction_date)->format('d-m-Y') }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $firstEntry->description }}
                    @if($firstEntry->transaction_count > 1)
                    <span class="text-xs text-gray-500">({{ $firstEntry->transaction_count }} payments combined)</span>
                    @endif
                </div>
            </div>
            
            <!-- Particulars and Amounts -->
            <div class="space-y-2">
                @if($debitEntry)
                <div class="flex justify-between items-center">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-800 dark:text-white">{{ $debitEntry->particular }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Ref: {{ $debitEntry->reference ?? '-' }}</span>
                    </div>
                    <span class="text-sm font-medium text-green-600 dark:text-green-400">
                        {{ setting('currency') }} {{ number_format($debitEntry->amount, 2) }}
                    </span>
                </div>
                @endif

                @if($creditEntry)
                <div class="flex justify-between items-center">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-800 dark:text-white">{{ $creditEntry->particular }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Ref: {{ $creditEntry->reference ?? '-' }}</span>
                    </div>
                    <span class="text-sm font-medium text-red-600 dark:text-red-400">
                        {{ setting('currency') }} {{ number_format($creditEntry->amount, 2) }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Payment Methods -->
            @if(isset($firstEntry->payment_methods) && count($firstEntry->payment_methods) > 0)
            <div class="mt-3 pt-3 border-t dark:border-gray-600">
                <div class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Payment Methods:</div>
                <div class="space-y-1">
                    @foreach($firstEntry->payment_methods as $payment)
                    @php
                        $methodIcons = [
                            'cash' => 'money-bill',
                            'bank_transfer' => 'university',
                            'bank' => 'university',
                            'mobile_banking' => 'mobile-alt',
                            'card' => 'credit-card',
                            'credit' => 'hand-holding-usd',
                        ];
                        $icon = $methodIcons[$payment['method']] ?? 'money-check';
                    @endphp
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <i class="fas fa-{{ $icon }} mr-1"></i>
                            <span class="capitalize">{{ str_replace('_', ' ', $payment['method']) }}</span>
                        </div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            {{ setting('currency') }}{{ number_format($payment['amount'], 2) }}
                        </span>
                    </div>
                    @endforeach
                    <div class="flex justify-between items-center text-xs font-semibold pt-1 border-t dark:border-gray-600">
                        <span class="text-gray-700 dark:text-gray-300">Total:</span>
                        <span class="text-green-600 dark:text-green-400">
                            {{ setting('currency') }}{{ number_format($firstEntry->payment_methods->sum('amount'), 2) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endforeach
</div>


        <!-- Pagination -->
        @if($entries->hasPages())
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $entries->links() }}
        </div>
        @endif
    </div>

    <!-- Payment Methods Breakdown -->
    @if($paymentMethods->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Payment Methods Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            @foreach($paymentMethods as $method)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full mx-auto mb-3">
                    <i class="fas fa-{{ 
                        $method['method'] == 'cash' ? 'money-bill' : (
                        $method['method'] == 'bank' ? 'university' : (
                        $method['method'] == 'mobile_banking' ? 'mobile-alt' : (
                        $method['method'] == 'card' ? 'credit-card' : (
                        $method['method'] == 'credit' ? 'hand-holding-usd' : 'money-check'
                        ))))
                    }} text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    {{ system('currency') }}{{ number_format($method['total'], 2) }}
                </div>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-300 capitalize mb-1">
                    {{ str_replace('_', ' ', $method['method']) }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $method['count'] }} transaction(s)
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Total Summary -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-800 dark:text-white">Total Payments</span>
                <span class="text-xl font-bold text-green-600 dark:text-green-400">
                    {{ system('currency') }}{{ number_format($paymentMethods->sum('total'), 2) }}
                </span>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection