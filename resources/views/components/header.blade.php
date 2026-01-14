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
            {{-- Menu --}}
            {{ $menu ?? '' }}

            <a href="{{ route('home') }}">
                <h1 class="title-header">FRIKILAND</h1>
            </a>
        </div>

        {{-- Buscador --}}
        {{ $search ?? '' }}

        @if (Route::has('login'))
            <div class="auth-nav">
                @auth
                    <a href="{{ url('/dashboard') }}">
                        <i class='bx bx-user icon-log'></i>
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        <i class='bx bx-user icon-log'></i>
                    </a>
                @endauth
            </div>
        @endif
    </div>
</header>
