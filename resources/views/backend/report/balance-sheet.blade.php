@extends('layouts.backend')

@section('title', 'Balance Sheet')

@section('content')
<div class="space-y-6">
    <!-- Report Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Balance Sheet</h2>
                <p class="text-gray-600 dark:text-gray-400">As of {{ now()->format('F d, Y') }}</p>
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

    <!-- Balance Sheet Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assets -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-green-600 px-4 py-4 sm:px-6">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-wallet mr-2"></i>Assets
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="space-y-4">
                    <!-- Current Assets -->
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-chart-line mr-2 text-green-500"></i>Current Assets
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Cash & Bank Balances</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ system('currency') }} {{ number_format($assets * 0.4, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Accounts Receivable</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ system('currency') }} {{ number_format($assets * 0.3, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Inventory</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ system('currency') }} {{ number_format($assets * 0.2, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Prepaid Expenses</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ system('currency') }} {{ number_format($assets * 0.1, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t dark:border-gray-700 mt-3 pt-3 flex justify-between font-medium bg-green-50 dark:bg-green-900/20 px-3 py-2 rounded">
                            <span class="text-green-800 dark:text-green-300">Total Current Assets</span>
                            <span class="text-green-800 dark:text-green-300">{{ system('currency') }} {{ number_format($assets * 0.4 + $assets * 0.3 + $assets * 0.2 + $assets * 0.1, 2) }}</span>
                        </div>
                    </div>

                    <!-- Fixed Assets -->
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-building mr-2 text-blue-500"></i>Fixed Assets
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Property & Equipment</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ system('currency') }} {{ number_format($assets * 0.5, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Less: Accumulated Depreciation</span>
                                <span class="font-medium text-red-600 dark:text-red-400">
                                    -{{ system('currency') }} {{ number_format($assets * 0.1, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t dark:border-gray-700 mt-3 pt-3 flex justify-between font-medium bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded">
                            <span class="text-blue-800 dark:text-blue-300">Net Fixed Assets</span>
                            <span class="text-blue-800 dark:text-blue-300">{{ system('currency') }} {{ number_format($assets * 0.4, 2) }}</span>
                        </div>
                    </div>

                    <!-- Total Assets -->
                    <div class="border-t-2 dark:border-gray-600 pt-4 mt-4 flex justify-between text-lg font-bold bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 px-4 py-3 rounded-lg">
                        <span class="text-green-800 dark:text-green-300">Total Assets</span>
                        <span class="text-green-600 dark:text-green-400">{{ system('currency') }} {{ number_format($assets, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liabilities & Equity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-blue-600 px-4 py-4 sm:px-6">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-scale-balanced mr-2"></i>Liabilities & Equity
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="space-y-4">
                    <!-- Liabilities -->
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-hand-holding-usd mr-2 text-red-500"></i>Liabilities
                        </h4>
                        
                        <!-- Current Liabilities -->
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 flex items-center">
                                <i class="fas fa-clock mr-2"></i>Current Liabilities
                            </h5>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Accounts Payable</span>
                                    <span class="font-medium text-gray-800 dark:text-white">
                                        {{ system('currency') }} {{ number_format($liabilities * 0.4, 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Short-term Loans</span>
                                    <span class="font-medium text-gray-800 dark:text-white">
                                        {{ system('currency') }} {{ number_format($liabilities * 0.3, 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Accrued Expenses</span>
                                    <span class="font-medium text-gray-800 dark:text-white">
                                        {{ system('currency') }} {{ number_format($liabilities * 0.2, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="border-t dark:border-gray-700 mt-3 pt-3 flex justify-between font-medium bg-orange-50 dark:bg-orange-900/20 px-3 py-2 rounded">
                                <span class="text-orange-800 dark:text-orange-300">Total Current Liabilities</span>
                                <span class="text-orange-800 dark:text-orange-300">{{ system('currency') }} {{ number_format($liabilities * 0.9, 2) }}</span>
                            </div>
                        </div>

                        <!-- Long-term Liabilities -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 flex items-center">
                                <i class="fas fa-calendar-alt mr-2"></i>Long-term Liabilities
                            </h5>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Long-term Debt</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ system('currency') }} {{ number_format($liabilities * 0.1, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t dark:border-gray-700 mt-3 pt-3 flex justify-between font-medium bg-red-50 dark:bg-red-900/20 px-3 py-2 rounded">
                            <span class="text-red-800 dark:text-red-300">Total Liabilities</span>
                            <span class="text-red-600 dark:text-red-400">{{ system('currency') }} {{ number_format($liabilities, 2) }}</span>
                        </div>
                    </div>

                    <!-- Equity -->
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-chart-pie mr-2 text-purple-500"></i>Equity
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Owner's Capital</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ system('currency') }} {{ number_format($equity * 0.7, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Retained Earnings</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ system('currency') }} {{ number_format($equity * 0.3, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Current Year Profit</span>
                                <span class="font-medium text-green-600 dark:text-green-400">
                                    +{{ system('currency') }} {{ number_format($equity * 0.2, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="border-t dark:border-gray-700 mt-3 pt-3 flex justify-between font-medium bg-purple-50 dark:bg-purple-900/20 px-3 py-2 rounded">
                            <span class="text-purple-800 dark:text-purple-300">Total Equity</span>
                            <span class="text-green-600 dark:text-green-400">{{ system('currency') }} {{ number_format($equity, 2) }}</span>
                        </div>
                    </div>

                    <!-- Total Liabilities & Equity -->
                    <div class="border-t-2 dark:border-gray-600 pt-4 mt-4 flex justify-between text-lg font-bold bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-4 py-3 rounded-lg">
                        <span class="text-blue-800 dark:text-blue-300">Total Liabilities & Equity</span>
                        <span class="text-blue-600 dark:text-blue-400">
                            {{ system('currency') }} {{ number_format($liabilities + $equity, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 sm:p-6 text-center border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-center mb-3">
                <div class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">
                {{ system('currency') }} {{ number_format($assets, 2) }}
            </div>
            <div class="text-xs sm:text-sm text-green-600 dark:text-green-400 mt-1">Total Assets</div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 sm:p-6 text-center border border-red-200 dark:border-red-800">
            <div class="flex items-center justify-center mb-3">
                <div class="flex-shrink-0 h-10 w-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-scale-balanced text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">
                {{ system('currency') }} {{ number_format($liabilities, 2) }}
            </div>
            <div class="text-xs sm:text-sm text-red-600 dark:text-red-400 mt-1">Total Liabilities</div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 sm:p-6 text-center border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-center mb-3">
                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">
                {{ system('currency') }} {{ number_format($equity, 2) }}
            </div>
            <div class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 mt-1">Owner's Equity</div>
        </div>
    </div>

    <!-- Balance Check -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <div class="text-center">
            <div class="flex items-center justify-center mb-3">
                <div class="flex-shrink-0 h-12 w-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-balance-scale text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Balance Check</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Assets = Liabilities + Equity</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 text-sm">
                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="font-medium text-gray-800 dark:text-white">{{ system('currency') }} {{ number_format($assets, 2) }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Assets</div>
                </div>
                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="font-medium text-gray-800 dark:text-white">{{ system('currency') }} {{ number_format($liabilities + $equity, 2) }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Liabilities + Equity</div>
                </div>
                <div class="text-center p-3 {{ $assets == ($liabilities + $equity) ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }} rounded-lg">
                    <div class="font-medium {{ $assets == ($liabilities + $equity) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $assets == ($liabilities + $equity) ? 'Balanced' : 'Not Balanced' }}
                    </div>
                    <div class="text-xs {{ $assets == ($liabilities + $equity) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ system('currency') }} {{ number_format(abs($assets - ($liabilities + $equity)), 2) }} difference
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection