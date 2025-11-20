@foreach ($replies as $reply)
    <div class="ml-6 mt-2 border-l pl-4 space-y-1">
        <strong>{{ $reply->user->name }}</strong>
        <p>{{ $reply->content }}</p>

        @if ($reply->media)
            @include('livewire.media', ['media' => $reply->media])
        @endif

        @if(Auth::id() === $reply->user_id)
            <button wire:click="edit({{ $reply->id }})" class="text-green-500 text-sm">Editar</button>
            <button wire:click="delete({{ $reply->id }})" class="text-red-500 text-sm">Eliminar</button>
        @endif

        @if ($editingPostId === $reply->id)
            <textarea wire:model="editingContent" class="w-full border p-2 rounded mt-1"></textarea>

            {{-- Media existente --}}
            @if ($existingMedia)
                @include('livewire.media', [
                    'media' => $existingMedia,
                    'removable' => true,
                    'postId' => $reply->id
                ])
            @endif

            {{-- Subir nuevos archivos --}}
            <input type="file" wire:model="newMedia" multiple class="mt-2">

            <button wire:click="update" class="px-3 py-1 mt-1 rounded">Guardar</button>
            <button wire:click="cancelEdit" class="px-3 py-1 mt-1 rounded border">Cancelar</button>
        @endif
    </div>
@endforeach
