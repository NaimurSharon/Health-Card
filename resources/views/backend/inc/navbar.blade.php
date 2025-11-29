<div class="flex items-center space-x-4">
    <!-- Notifications -->
    <button class="relative p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors duration-150">
        <i class="fas fa-bell"></i>
        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs text-white">3</span>
    </button>

    <!-- Messages -->
    <button class="relative p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors duration-150">
        <i class="fas fa-envelope"></i>
        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-blue-500 text-xs text-white">5</span>
    </button>

    <!-- User Menu -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center space-x-3 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors duration-150">
            <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                <i class="fas fa-user text-sm text-gray-600 dark:text-gray-300"></i>
            </div>
            <span class="text-sm font-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
            <i class="fas fa-chevron-down text-xs"></i>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" @click.outside="open = false" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800 z-50">
            <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-user-circle w-4 mr-3"></i>
                Profile
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-cog w-4 mr-3"></i>
                Settings
            </a>
            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-700">
                    <i class="fas fa-sign-out-alt w-4 mr-3"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>