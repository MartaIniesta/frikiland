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
                    @include('livewire.notifications.notification-menu')

                    <div class="user-menu">
                        <button class="user-avatar-btn" onclick="toggleUserMenu(event)">
                            <img
                                src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('images/default-avatar.png') }}">
                        </button>

                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('user.profile', auth()->user()->username) }}">Profile</a>
                            <a href="{{ route('profile.edit') }}">Edit Profile</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}">
                        <i class='bx bx-user icon-log'></i>
                    </a>
                @endauth
            </div>
        @endif
    </div>
</header>
