@php
    $count = count($media);
@endphp

<div
    class="
        mt-2 gap-2
        {{ $count === 1 ? 'grid grid-cols-1' : '' }}
        {{ $count === 2 ? 'grid grid-cols-2' : '' }}
        {{ $count === 3 ? 'grid grid-cols-2 grid-rows-2' : '' }}
        {{ $count >= 4 ? 'grid grid-cols-2 grid-rows-2' : '' }}
    ">
    @foreach ($media as $index => $mediaItem)
        @php
            if (is_string($mediaItem)) {
                $url = Storage::url($mediaItem);
                $mime = Storage::disk('public')->mimeType($mediaItem);
            } else {
                $url = $mediaItem->temporaryUrl();
                $mime = $mediaItem->getMimeType();
            }

            $isImage = $mime && str_starts_with($mime, 'image/');
            $isVideo = $mime && str_starts_with($mime, 'video/');
        @endphp

        @if ($index < 4)
            <div
                class="
                    relative overflow-hidden rounded
                    {{ $count === 1 ? 'h-64' : '' }}
                    {{ $count === 2 ? 'h-48' : '' }}
                    {{ $count === 3 && $index === 0 ? 'row-span-2 h-full' : 'h-32' }}
                    {{ $count >= 4 ? 'h-32' : '' }}
                ">
                @if ($isImage)
                    <img src="{{ $url }}" class="w-full h-full object-cover">
                @elseif ($isVideo)
                    <video class="w-full h-full object-cover" controls playsinline muted>
                        <source src="{{ $url }}" type="{{ $mime }}">
                    </video>
                @endif

                {{-- Overlay +X --}}
                @if ($count > 4 && $index === 3)
                    <div
                        class="absolute inset-0 bg-black/60 flex items-center justify-center text-white text-2xl font-bold">
                        +{{ $count - 4 }}
                    </div>
                @endif

                {{-- Eliminar --}}
                @if (isset($removable) && $removable)
                    <button wire:click="{{ $removeMethod ?? 'removeTempMedia' }}({{ $index }})"
                        class="absolute top-1 right-1 bg-black/70 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center z-10">
                        ×
                    </button>
                @endif
            </div>
        @endif
    @endforeach
</div>
