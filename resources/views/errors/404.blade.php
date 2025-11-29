@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">404 - Page Not Found</h1>
    </div>
    
    <div class="p-6">
        <div class="text-center py-8">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="mt-4 text-xl font-medium text-gray-800 dark:text-gray-200">Oops! The page you're looking for doesn't exist.</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">You may have mistyped the address or the page may have moved.</p>
            
            <div class="mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>
</div>
@endsection