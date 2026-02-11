<div class="home-carousel">
    <h2 class="carousel-title">PRODUCTOS DESTACADOS</h2>

    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($products as $product)
                <div class="swiper-slide">
                    <a href="{{ route('shop-web', ['product' => $product->id]) }}" class="product-link">
                        <div class="product-card">
                            <div class="product-image">
                                @if (!empty($product->images))
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x300">
                                @endif
                            </div>

                            <h4>{{ $product->name }}</h4>
                            <p class="price">{{ number_format($product->price, 2) }} €</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
