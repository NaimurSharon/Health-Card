@extends('layouts.backend')

@section('title', 'General Ledger')

@section('content')
<div class="space-y-6">
    <!-- Report Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">General Ledger</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Complete transaction history</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <button onclick="window.print()" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-print mr-2"></i>Print Report
                </button>
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->

    <!-- Ledger Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Mobile Card View -->
        <div class="block sm:hidden divide-y divide-gray-200 dark:divide-gray-700">
            @php
                $balance = 0;
            @endphp
            @foreach($transactions as $transaction)
            @php
                if ($transaction instanceof \App\Models\IncomeTransaction) {
                    $credit = $transaction->amount;
                    $debit = 0;
                    $balance += $transaction->amount;
                    $typeClass = 'text-green-600 dark:text-green-400';
                    $typeBg = 'bg-green-100 dark:bg-green-900';
                } else {
                    $debit = $transaction->amount;
                    $credit = 0;
                    $balance -= $transaction->amount;
                    $typeClass = 'text-red-600 dark:text-red-400';
                    $typeBg = 'bg-red-100 dark:bg-red-900';
                }
                $balanceClass = $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
            @endphp
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <div class="flex-shrink-0 h-10 w-10 {{ $typeBg }} rounded-lg flex items-center justify-center mr-3">
                                <i class="fas {{ $transaction instanceof \App\Models\IncomeTransaction ? 'fa-arrow-down text-green-600 dark:text-green-400' : 'fa-arrow-up text-red-600 dark:text-red-400' }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-800 dark:text-white text-sm">{{ $transaction->description }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->transaction_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400 mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-2 text-gray-400"></i>
                                {{ $transaction instanceof \App\Models\IncomeTransaction ? $transaction->category->name ?? 'N/A' : $transaction->category->name ?? 'N/A' }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-hashtag mr-2 text-gray-400"></i>
                                {{ $transaction instanceof \App\Models\IncomeTransaction ? $transaction->invoice_number : $transaction->expense_number }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-2 text-sm mb-3">
                    <div class="text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Debit</div>
                        <div class="font-medium text-red-600 dark:text-red-400">
                            @if($debit > 0)
                            {{ system('currency') }} {{ number_format($debit, 2) }}
                            @else
                            -
                            @endif
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Credit</div>
                        <div class="font-medium text-green-600 dark:text-green-400">
                            @if($credit > 0)
                            {{ system('currency') }} {{ number_format($credit, 2) }}
                            @else
                            -
                            @endif
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Balance</div>
                        <div class="font-medium {{ $balanceClass }}">
                            {{ system('currency') }} {{ number_format($balance, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Debit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Credit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $balance = 0;
                    @endphp
                    @foreach($transactions as $transaction)
                    @php
                        if ($transaction instanceof \App\Models\IncomeTransaction) {
                            $credit = $transaction->amount;
                            $debit = 0;
                            $balance += $transaction->amount;
                        } else {
                            $debit = $transaction->amount;
                            $credit = 0;
                            $balance -= $transaction->amount;
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 lg:px-6">
                            {{ $transaction->transaction_date->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-4 lg:px-6">
                            <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $transaction->description }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction instanceof \App\Models\IncomeTransaction ? $transaction->category->name ?? 'N/A' : $transaction->category->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 lg:px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $transaction instanceof \App\Models\IncomeTransaction ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $transaction instanceof \App\Models\IncomeTransaction ? 'Income' : 'Expense' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 lg:px-6">
                            {{ $transaction instanceof \App\Models\IncomeTransaction ? $transaction->invoice_number : $transaction->expense_number }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-red-600 dark:text-red-400 lg:px-6">
                            @if($debit > 0)
                            {{ system('currency') }} {{ number_format($debit, 2) }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400 lg:px-6">
                            @if($credit > 0)
                            {{ system('currency') }} {{ number_format($credit, 2) }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium {{ $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} lg:px-6">
                            {{ system('currency') }} {{ number_format($balance, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-white text-right lg:px-6">Total Balance:</td>
                        <td colspan="3" class="px-4 py-4 text-sm font-medium {{ $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} lg:px-6">
                            {{ system('currency') }} {{ number_format($balance, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex justify-center">
            {{ $transactions->links() }}
        </div>
    </div>
    @endif
</div>
@endsection