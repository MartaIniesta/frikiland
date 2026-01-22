<section class="w-full">
    <x-header />

    <x-banner-categories>
        <a class="cat" href="{{ route('profile.edit') }}" wire:navigate>
            {{ __('EDIT PROFILE') }}
        </a>

        <a class="cat" href="{{ route('user-password.edit') }}" wire:navigate>
            {{ __('PASSWORD') }}
        </a>

        <a class="cat active" href="{{ route('profile.configuration') }}" wire:navigate>
            {{ __('CONFIGURATION') }}
        </a>
    </x-banner-categories>

    <main class="main-content-privacy">
        <div class="content-privacy">
            <h2>~ Configuracion de Privacidad ~</h2>

            <div class="content-privacy-box">
                <button class="privacy-title" wire:click="toggle">
                    Publicaciones favoritas
                    <i class="bx {{ $open ? 'bx-chevron-up' : 'bx-chevron-down' }}"></i>
                </button>

                @if ($open)
                    <div class="privacy-options">
                        <label class="privacy-card {{ $visibility === 'public' ? 'active' : '' }}">
                            <input type="radio" wire:model="visibility" value="public">
                            🌍 Público
                        </label>

                        <label class="privacy-card {{ $visibility === 'followers' ? 'active' : '' }}">
                            <input type="radio" wire:model="visibility" value="followers">
                            👥 Solo seguidores
                        </label>

                        <label class="privacy-card {{ $visibility === 'private' ? 'active' : '' }}">
                            <input type="radio" wire:model="visibility" value="private">
                            🔒 Privado
                        </label>

                        <button class="btn-save-privacy" wire:click="save">
                            Guardar
                        </button>
                    </div>
                @endif

                @if ($saved)
                    <small class="saved-msg">Configuración guardada</small>
                @endif
            </div>

        </div>
    </main>
</section>
