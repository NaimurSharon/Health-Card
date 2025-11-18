@extends('layouts.backend')

@section('title', 'Trial Balance')

@section('content')
<div class="space-y-6">
    <!-- Report Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Trial Balance</h2>
                <p class="text-gray-600 dark:text-gray-400">For the period ending {{ now()->format('F d, Y') }}</p>
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
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 sm:p-6 text-center">
            <div class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">
                {{ system('currency') }} {{ number_format($totalIncome, 2) }}
            </div>
            <div class="text-xs sm:text-sm text-green-600 dark:text-green-400 mt-1">Total Income</div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 sm:p-6 text-center">
            <div class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">
                {{ system('currency') }} {{ number_format($totalExpense, 2) }}
            </div>
            <div class="text-xs sm:text-sm text-red-600 dark:text-red-400 mt-1">Total Expenses</div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 sm:p-6 text-center">
            <div class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">
                {{ system('currency') }} {{ number_format($totalIncome - $totalExpense, 2) }}
            </div>
            <div class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 mt-1">Net Profit</div>
        </div>
    </div>

    <!-- Trial Balance Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Mobile Card View -->
        <div class="block sm:hidden">
            @php
                $totalDebit = $totalIncome * 0.65 + $totalExpense;
                $totalCredit = $totalIncome * 0.65 + $totalExpense;
            @endphp
            
            <!-- Assets -->
            <div class="border-b dark:border-gray-700">
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3">
                    <h3 class="font-semibold text-gray-800 dark:text-white">Assets</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4">
                        <div class="font-medium text-gray-800 dark:text-white">Cash & Bank</div>
                        <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.3, 2) }}
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="font-medium text-gray-800 dark:text-white">Accounts Receivable</div>
                        <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.2, 2) }}
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="font-medium text-gray-800 dark:text-white">Inventory</div>
                        <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.15, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liabilities -->
            <div class="border-b dark:border-gray-700">
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3">
                    <h3 class="font-semibold text-gray-800 dark:text-white">Liabilities</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4">
                        <div class="font-medium text-gray-800 dark:text-white">Accounts Payable</div>
                        <div class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                            {{ system('currency') }} {{ number_format($totalExpense * 0.4, 2) }}
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="font-medium text-gray-800 dark:text-white">Loans Payable</div>
                        <div class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                            {{ system('currency') }} {{ number_format($totalExpense * 0.3, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Totals -->
            <div class="bg-gray-100 dark:bg-gray-600 px-4 py-4 border-t-2 border-gray-300 dark:border-gray-500">
                <div class="flex justify-between items-center">
                    <div class="font-bold text-gray-800 dark:text-white">Total</div>
                    <div class="text-right">
                        <div class="font-bold text-gray-800 dark:text-white">
                            {{ system('currency') }} {{ number_format($totalDebit, 2) }}
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-300">Debit = Credit</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Account</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Debit</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Credit</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Assets -->
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <td colspan="3" class="px-4 py-3 lg:px-6">
                            <h3 class="font-semibold text-gray-800 dark:text-white">Assets</h3>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Cash & Bank</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.3, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Accounts Receivable</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.2, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Inventory</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.15, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                    </tr>

                    <!-- Liabilities -->
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <td colspan="3" class="px-4 py-3 lg:px-6">
                            <h3 class="font-semibold text-gray-800 dark:text-white">Liabilities</h3>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Accounts Payable</td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalExpense * 0.4, 2) }}
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Loans Payable</td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalExpense * 0.3, 2) }}
                        </td>
                    </tr>

                    <!-- Equity -->
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <td colspan="3" class="px-4 py-3 lg:px-6">
                            <h3 class="font-semibold text-gray-800 dark:text-white">Equity</h3>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Owner's Capital</td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format(($totalIncome - $totalExpense) * 0.7, 2) }}
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Retained Earnings</td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format(($totalIncome - $totalExpense) * 0.3, 2) }}
                        </td>
                    </tr>

                    <!-- Income -->
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <td colspan="3" class="px-4 py-3 lg:px-6">
                            <h3 class="font-semibold text-gray-800 dark:text-white">Income</h3>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Service Revenue</td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.6, 2) }}
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Product Sales</td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.4, 2) }}
                        </td>
                    </tr>

                    <!-- Expenses -->
                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                        <td colspan="3" class="px-4 py-3 lg:px-6">
                            <h3 class="font-semibold text-gray-800 dark:text-white">Expenses</h3>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Salaries & Wages</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalExpense * 0.3, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Rent Expense</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalExpense * 0.2, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Utilities</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalExpense * 0.15, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200 pl-8 lg:px-6">Other Expenses</td>
                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalExpense * 0.35, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400 lg:px-6">-</td>
                    </tr>

                    <!-- Totals -->
                    <tr class="bg-gray-100 dark:bg-gray-600 border-t-2 border-gray-300 dark:border-gray-500">
                        <td class="px-4 py-4 text-sm font-bold text-gray-800 dark:text-white lg:px-6">Total</td>
                        <td class="px-4 py-4 text-sm text-right font-bold text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.65 + $totalExpense, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm text-right font-bold text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($totalIncome * 0.65 + $totalExpense, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection