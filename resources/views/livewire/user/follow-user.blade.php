<button wire:click="toggleFollow" class="btn-profile-users {{ $isFollowing ? 'following' : '' }}">
    {{ $isFollowing ? 'Siguiendo' : 'Seguir' }}
</button>
