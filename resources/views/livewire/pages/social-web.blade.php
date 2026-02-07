<div>
    <x-header>
        <x-slot:search>
            <div class="search">
                <input type="text" placeholder="Buscar…" aria-label="Buscar">
                <button>
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </x-slot:search>
    </x-header>

    <x-banner-categories>
        <a href="{{ route('social-web.for-you') }}"
            class="cat {{ request()->routeIs('social-web.for-you') ? 'active' : '' }}">
            PARA TI
        </a>

        <a href="{{ route('social-web.following') }}"
            class="cat {{ request()->routeIs('social-web.following') ? 'active' : '' }}">
            SIGUIENDO
        </a>
    </x-banner-categories>

    <main class="wrap-main">
        <section class="main-content">
            @if (request()->is('social-web/following'))
                <livewire:posts.posts-following />
            @else
                <livewire:posts.posts-for-you />
            @endif
        </section>
    </main>
</div>
