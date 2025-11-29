@extends('layouts.backend')

@section('title', 'Financial Statements')

@section('content')
<div class="space-y-6">
    <!-- Report Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Financial Statements</h2>
                <p class="text-gray-600 dark:text-gray-400">Comprehensive financial overview</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <form method="GET" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <select name="period" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                        <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ request('period') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="yearly" {{ request('period') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    <select name="year" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                        @for($i = now()->year; $i >= now()->year - 5; $i--)
                        <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm transition-colors">
                        Apply
                    </button>
                </form>
                <a href="{{ route('admin.financial-statements.export', request()->query()) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-file-export mr-2"></i>Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Income Statement -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-green-600 px-4 py-4 sm:px-6">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-chart-line mr-2"></i>Income Statement
            </h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Revenue -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-800 dark:text-white">Revenue</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Total Revenue</span>
                            <span class="font-medium text-green-600 dark:text-green-400">
                                {{ system('currency') }} {{ number_format($incomeData['revenue'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Expenses -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-800 dark:text-white">Expenses</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Total Expenses</span>
                            <span class="font-medium text-red-600 dark:text-red-400">
                                {{ system('currency') }} {{ number_format($incomeData['expenses'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Income -->
            <div class="border-t dark:border-gray-700 mt-4 pt-4 flex justify-between text-lg font-bold bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 px-4 py-3 rounded-lg">
                <span class="{{ $incomeData['net_income'] >= 0 ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300' }}">
                    {{ $incomeData['net_income'] >= 0 ? 'Net Income' : 'Net Loss' }}
                </span>
                <span class="{{ $incomeData['net_income'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ system('currency') }} {{ number_format(abs($incomeData['net_income']), 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Balance Sheet -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-blue-600 px-4 py-4 sm:px-6">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-scale-balanced mr-2"></i>Balance Sheet
            </h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Assets -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-800 dark:text-white">Assets</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Total Assets</span>
                            <span class="font-medium text-blue-600 dark:text-blue-400">
                                {{ system('currency') }} {{ number_format($balanceSheetData['assets'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Liabilities & Equity -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-800 dark:text-white">Liabilities & Equity</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Total Liabilities</span>
                            <span class="font-medium text-orange-600 dark:text-orange-400">
                                {{ system('currency') }} {{ number_format($balanceSheetData['liabilities'], 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Total Equity</span>
                            <span class="font-medium text-green-600 dark:text-green-400">
                                {{ system('currency') }} {{ number_format($balanceSheetData['equity'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash Flow Statement -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-purple-600 px-4 py-4 sm:px-6">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-money-bill-wave mr-2"></i>Cash Flow Statement
            </h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Operating Activities</span>
                    <span class="font-medium text-green-600 dark:text-green-400">
                        {{ system('currency') }} {{ number_format($cashFlowData['operating_activities'], 2) }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Investing Activities</span>
                    <span class="font-medium {{ $cashFlowData['investing_activities'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ system('currency') }} {{ number_format($cashFlowData['investing_activities'], 2) }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Financing Activities</span>
                    <span class="font-medium {{ $cashFlowData['financing_activities'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ system('currency') }} {{ number_format($cashFlowData['financing_activities'], 2) }}
                    </span>
                </div>
                <div class="border-t dark:border-gray-700 pt-4 flex justify-between font-bold text-lg bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 px-4 py-3 rounded-lg">
                    <span class="{{ $cashFlowData['net_cash_flow'] >= 0 ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300' }}">
                        Net Cash Flow
                    </span>
                    <span class="{{ $cashFlowData['net_cash_flow'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ system('currency') }} {{ number_format($cashFlowData['net_cash_flow'], 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection