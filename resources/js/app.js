import './auth/login';
import Swiper from 'swiper';
import { Navigation, Pagination, EffectCoverflow, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-coverflow';

function initSwiper() {

    document.querySelectorAll('.mySwiper').forEach((el) => {

        if (el.swiper) {
            el.swiper.destroy(true, true);
        }

        new Swiper(el, {
            modules: [Navigation, Autoplay],
            slidesPerView: 5,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
        });
    });

    document.querySelectorAll('.testimonial-swiper').forEach((el) => {

        if (el.swiper) el.swiper.destroy(true, true);

        new Swiper(el, {
            modules: [EffectCoverflow, Pagination, Navigation],
            loop: true,
            slidesPerView: 'auto',
            centeredSlides: true,
            spaceBetween: 16,
            grabCursor: true,
            speed: 600,
            effect: 'coverflow',

            coverflowEffect: {
                rotate: -90,
                depth: 600,
                modifier: .5,
                slideShadows: false,
            },

            pagination: {
                el: el.querySelector('.swiper-pagination'),
                clickable: true,
            },

            navigation: {
                nextEl: el.querySelector('.swiper-button-next'),
                prevEl: el.querySelector('.swiper-button-prev'),
            },
        });
    });
}

document.addEventListener('livewire:navigated', initSwiper);
document.addEventListener('livewire:load', initSwiper);
document.addEventListener('DOMContentLoaded', initSwiper);
document.addEventListener('livewire:navigated', initTestimonialSwiper);
document.addEventListener('DOMContentLoaded', initTestimonialSwiper);
