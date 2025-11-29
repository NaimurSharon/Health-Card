<div class="space-y-4">
    @if($linkedPages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($linkedPages as $linkedPage)
                <div class="border rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $linkedPage->image_path) }}" 
                         alt="Page {{ $linkedPage->page_number }}" 
                         class="w-full h-auto">
                    <div class="p-2 bg-gray-100 text-center">
                        Page {{ $linkedPage->page_number }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            No linked pages found for this section.
        </div>
    @endif
</div>