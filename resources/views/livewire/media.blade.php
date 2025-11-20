<div class="flex flex-wrap gap-2 mt-1">
    @foreach ($media as $index => $mediaItem)
        @php
            if (is_string($mediaItem)) {
                $url = Storage::url($mediaItem);
                $ext = strtolower(pathinfo($mediaItem, PATHINFO_EXTENSION));
            } else {
                $url = $mediaItem->temporaryUrl();
                $ext = strtolower($mediaItem->getClientOriginalExtension());
            }
        @endphp

        <div class="relative border p-1 rounded">
            @if (in_array($ext, ['jpg','jpeg','png','gif']))
                <img src="{{ $url }}" class="w-24 h-24 object-cover">
            @elseif (in_array($ext, ['mp4','mov','avi']))
                <video controls class="w-48 h-32">
                    <source src="{{ $url }}" type="video/{{ $ext }}">
                </video>
            @endif

            @if(isset($removable) && $removable)
                <button 
                    wire:click="removeMedia({{ $postId }}, {{ $index }})" 
                    class="absolute top-0 right-0 px-1 rounded text-xs text-white"
                >
                    X
                </button>
            @endif
        </div>
    @endforeach
</div>
