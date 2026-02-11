<x-layouts.app.sidebar>
    <x-header />

    <div class="content-home">
        <div class="text-home social-web">
            <a href="{{ route('social-web') }}">
                <h2>Social web</h2>
                <p>
                    Únete para comentar, compartir posts, debatir, chatear y seguir a otros geeks.
                    Una comunidad donde cada tema encuentra su fandom.
                </p>
            </a>

        </div>

        <div class="text-home shop">
            <a href="{{ route('shop-web') }}">
                <h2>Shop</h2>
                <p>Explora, elige y compra con confianza. Aquí, cada compra conecta con lo que te gusta y con tu fandom.
                </p>
            </a>
        </div>
    </div>

    @livewire('products.product-carousel')
    @livewire('posts.top-posts-carousel')
</x-layouts.app.sidebar>
