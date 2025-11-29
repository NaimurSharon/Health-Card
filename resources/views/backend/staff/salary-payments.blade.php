@extends('layouts.backend')

@section('title', 'Salary Payments')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-5 sm:px-6 border-b dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Salary Payments</h2>
            <a href="{{ route('admin.salary-payments.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-plus mr-2"></i>Record Payment
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="px-4 py-4 bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-600">
        <form action="{{ route('admin.salary-payments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month & Year</label>
                <input type="month" name="month_year" value="{{ request('month_year') }}" 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
            <div class="flex items-end">
                <a href="{{ route('admin.salary-payments.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </a>
            </div>
            <div class="flex items-end">
                <div class="w-full text-center p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="text-sm font-medium text-blue-800 dark:text-blue-300">Total Paid</div>
                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ system('currency') }} {{ number_format($payments->sum('net_salary'), 2) }}</div>
                </div>
            </div>
        </form>
    </div>

    <!-- Salary Payments List -->
    <div class="overflow-x-auto">
        <!-- Mobile Card View -->
        <div class="block sm:hidden divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($payments as $payment)
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 dark:text-white">{{ $payment->staff->getFullNameAttribute() }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $payment->staff->role }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month_year)->format('F Y') }}</div>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400 mb-3">
                    <div class="flex items-center">
                        <i class="fas fa-money-bill mr-2 text-gray-400"></i>
                        Basic: {{ system('currency') }} {{ number_format($payment->basic_salary, 2) }}
                    </div>
                    <div class="flex items-center text-green-600 dark:text-green-400">
                        <i class="fas fa-plus mr-2"></i>
                        Bonus: {{ system('currency') }} {{ number_format($payment->bonus, 2) }}
                    </div>
                    <div class="flex items-center text-red-600 dark:text-red-400">
                        <i class="fas fa-minus mr-2"></i>
                        Deductions: {{ system('currency') }} {{ number_format($payment->deductions, 2) }}
                    </div>
                    <div class="flex items-center font-semibold text-gray-800 dark:text-white">
                        <i class="fas fa-calculator mr-2"></i>
                        Net: {{ system('currency') }} {{ number_format($payment->net_salary, 2) }}
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="#" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="#" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 transition-colors">
                        <i class="fas fa-receipt mr-1"></i> Receipt
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Staff Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Basic Salary</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Bonus</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Deductions</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Net Salary</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider lg:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-4 lg:px-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $payment->staff->getFullNameAttribute() }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $payment->staff->role }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 lg:px-6">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month_year)->format('F Y') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 lg:px-6">
                            {{ system('currency') }} {{ number_format($payment->basic_salary, 2) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 lg:px-6">
                            +{{ system('currency') }} {{ number_format($payment->bonus, 2) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 lg:px-6">
                            -{{ system('currency') }} {{ number_format($payment->deductions, 2) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white lg:px-6">
                            {{ system('currency') }} {{ number_format($payment->net_salary, 2) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap lg:px-6">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium lg:px-6">
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 p-2 rounded hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="View">
                                    <i class="fas fa-eye w-4 h-4"></i>
                                </a>
                                <a href="#" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 p-2 rounded hover:bg-green-50 dark:hover:bg-green-900/30 transition-colors" title="Receipt">
                                    <i class="fas fa-receipt w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-4 py-4 border-t dark:border-gray-700 sm:px-6">
        <div class="flex justify-center">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Summary -->
    <div class="px-4 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-600">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="font-medium text-gray-800 dark:text-white text-lg">
                    {{ system('currency') }} {{ number_format($payments->sum('basic_salary'), 2) }}
                </div>
                <div class="text-gray-600 dark:text-gray-400 text-xs">Total Basic</div>
            </div>
            <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-green-200 dark:border-green-800">
                <div class="font-medium text-green-600 dark:text-green-400 text-lg">
                    +{{ system('currency') }} {{ number_format($payments->sum('bonus'), 2) }}
                </div>
                <div class="text-gray-600 dark:text-gray-400 text-xs">Total Bonus</div>
            </div>
            <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-red-200 dark:border-red-800">
                <div class="font-medium text-red-600 dark:text-red-400 text-lg">
                    -{{ system('currency') }} {{ number_format($payments->sum('deductions'), 2) }}
                </div>
                <div class="text-gray-600 dark:text-gray-400 text-xs">Total Deductions</div>
            </div>
            <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-indigo-200 dark:border-indigo-800">
                <div class="font-medium text-indigo-600 dark:text-indigo-400 text-lg">
                    {{ system('currency') }} {{ number_format($payments->sum('net_salary'), 2) }}
                </div>
                <div class="text-gray-600 dark:text-gray-400 text-xs">Net Paid</div>
            </div>
        </div>
    </div>
</div>
@endsection