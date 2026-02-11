<section class="testimonial">
    <h2 class="carousel-title">POSTS MÁS GUSTADOS</h2>

    <div class="testimonial-swiper swiper">
        <div class="swiper-wrapper">

            @foreach ($posts as $post)
                <article class="testimonial-card swiper-slide">
                    <img src="{{ asset($post->user->avatar) }}" class="testimonial-img">

                    <a href="{{ route('user.profile', $post->user->username) }}" class="user-link">
                        <h3 class="testimonial-name">
                            {{ $post->user->name }}
                            <span>{{ '@' . $post->user->username }}</span>
                        </h3>
                    </a>

                    <p class="testimonial-description">
                        {{ Str::limit($post->content, 100) }}
                    </p>

                    <div class="testimonial-rating">
                        <livewire:favorite-content :model="$post" wire:key="fav-post-{{ $post->id }}" />
                    </div>
                </article>
            @endforeach

        </div>

        <div class="swiper-button-prev">
            <i class="ri-arrow-left-long-line"></i>
        </div>

        <div class="swiper-button-next">
            <i class="ri-arrow-right-long-line"></i>
        </div>
    </div>
</section>
