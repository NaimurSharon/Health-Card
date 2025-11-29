<!-- Empty State Component -->
<div class="text-center py-12">
    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-50 to-purple-50 rounded-full flex items-center justify-center mb-6">
        <i class="fas fa-file-medical-alt text-blue-500 text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">No Health Reports Found</h3>
    <p class="text-gray-500 max-w-md mx-auto mb-6">
        {{ $message ?? 'Health reports will appear here once they are created for students.' }}
    </p>
    @if(isset($action) && $action)
    <a href="{{ $action['url'] }}" 
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-plus mr-2"></i>
        {{ $action['text'] }}
    </a>
    @endif
</div>