<div>
    <x-header>
        <x-slot:menu>
            <div class="menu" id="menu">
                <i class="bx bx-menu"></i>
            </div>
        </x-slot:menu>

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
        <a href="{{ url('/social-web') }}" class="cat {{ request('feed', 'for_you') === 'for_you' ? 'active' : '' }}">
            PARA TI
        </a>

        <a href="{{ url('/social-web/following') }}"
            class="cat {{ request()->is('social-web/following') ? 'active' : '' }}">
            SIGUIENDO
        </a>
    </x-banner-categories>

    <!-- POSTS -->
    <div class="content-web">
        <livewire:posts.posts />
    </div>
</div>
