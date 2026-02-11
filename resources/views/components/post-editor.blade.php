@props([
    'contentModel',
    'newMediaModel',
    'media' => [],
    'submit',
    'cancel' => null,
    'placeholder' => 'Escribe algo...',
    'buttonText' => 'Publicar',
    'avatar' => null,
    'removeMethod' => 'removeTempMedia',
])

<article class="create-post-box relative" x-data="{ dragging: false }" x-on:dragover.prevent="dragging = true"
    x-on:dragleave.prevent="dragging = false"
    x-on:drop.prevent="
        dragging = false;
        $wire.uploadMultiple('{{ $newMediaModel }}', [...$event.dataTransfer.files])
    "
    :class="{ 'dragging': dragging }">

    {{-- HEADER --}}
    <div class="create-post-top">
        @if ($avatar)
            <img src="{{ asset($avatar) }}" class="create-avatar">
        @endif

        <textarea wire:model.defer="{{ $contentModel }}" class="create-textarea" placeholder="{{ $placeholder }}"></textarea>
    </div>

    {{-- MEDIA PREVIEW --}}
    @if (!empty($media))
        @include('livewire.posts.media', [
            'media' => $media,
            'removable' => true,
            'removeMethod' => $removeMethod,
        ])
    @endif

    {{-- ACTIONS --}}
    <div class="create-actions">
        <div class="left-actions">
            <label for="mediaInput-{{ $contentModel }}">
                <i class="bx bx-image"></i>
            </label>

            <input type="file" id="mediaInput-{{ $contentModel }}" wire:model="{{ $newMediaModel }}" multiple hidden>

            <button type="button" @click="showLinkInput = !showLinkInput" title="Añadir enlace">
                <i class="bx bx-paperclip"></i>
            </button>
        </div>

        <div class="flex gap-2">
            @if ($cancel)
                <button wire:click="{{ $cancel }}" class="btn-post">
                    Cancelar
                </button>
            @endif

            <button wire:click="{{ $submit }}" class="btn-post">
                {{ $buttonText }}
            </button>
        </div>
    </div>
</article>
