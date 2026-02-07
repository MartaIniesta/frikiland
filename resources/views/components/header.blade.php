<header>
    {{-- Barra superior --}}
    <div class="info-top">
        <p class="text-top" id="rotating-message">
            Envío GRATIS en pedidos superiores a 29€* (Sólo Península)
        </p>
    </div>

    {{-- Barra principal --}}
    <div class="shop-nav">
        <div class="nav-left">
            <a href="{{ route('home') }}">
                <h1 class="title-header">FRIKILAND</h1>
            </a>
        </div>

        {{-- Buscador --}}
        {{ $search ?? '' }}

        @if (Route::has('login'))
            <div class="auth-nav">
                @auth
                    <livewire:notifications.notification-menu />

                    @include('livewire.user.dropdown-user')
                @else
                    <a href="{{ route('login') }}">
                        <i class='bx bx-user icon-log'></i>
                    </a>
                @endauth
            </div>
        @endif
    </div>
</header>
