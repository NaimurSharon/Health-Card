<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            800: '#1e293b',
                            700: '#334155',
                            600: '#475569',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700 max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white text-center">Login</h2>
            </div>

            <div class="p-6">
                @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input id="email" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                        <input id="password" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                            type="password"
                            name="password"
                            required 
                            autocomplete="current-password"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="block mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600" 
                                name="remember"
                            >
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Remember me</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200" 
                               href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif

                        <button type="submit" 
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Log in
                        </button>
                    </div>
                </form>
            </div>
            
            @if (Route::has('register'))
            <!--<div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 text-center">-->
            <!--    <p class="text-sm text-gray-600 dark:text-gray-300">-->
            <!--        Don't have an account?-->
            <!--        <a href="{{ route('register') }}" -->
            <!--           class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">-->
            <!--            Register-->
            <!--        </a>-->
            <!--    </p>-->
            <!--</div>-->
            @endif
        </div>
    </div>
</body>
</html>