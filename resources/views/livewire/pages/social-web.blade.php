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
        <a href="{{ route('social-web') }}" class="cat active">PARA TI</a>
        <a href="#" class="cat">SIGUIENDO</a>
    </x-banner-categories>


    <!-- POSTS -->
    <div class="content-web">
        <livewire:posts />
        <livewire:posts-feed />
    </div>
</div>
