<div class="p-4 bg-white rounded shadow mb-4">
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-2 mb-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <textarea wire:model="content" placeholder="¿Qué estás pensando?" class="w-full p-2 border rounded mb-2"></textarea>
        <input type="file" wire:model="media" class="mb-2">
        @error('media') <span class="text-red-600">{{ $message }}</span> @enderror
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Publicar</button>
    </form>
</div>
