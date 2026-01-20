<button wire:click="toggleFollow"
    class="px-3 py-1 rounded
        {{ $isFollowing ? 'bg-gray-300' : 'bg-blue-500 text-white' }}">
    {{ $isFollowing ? 'Siguiendo' : 'Seguir' }}
</button>
