import './auth/login';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import { Navigation, Autoplay } from 'swiper/modules';

function initSwiper() {

    const swipers = document.querySelectorAll('.mySwiper');
    if (!swipers.length) return;

    swipers.forEach((swiperEl) => {

        if (swiperEl.swiper) {
            swiperEl.swiper.destroy(true, true);
        }

        new Swiper(swiperEl, {
            modules: [Navigation, Autoplay],
            slidesPerView: 5,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
            },
            navigation: {
                nextEl: ".custom-next",
                prevEl: ".custom-prev",
            },
            breakpoints: {
                1400: { slidesPerView: 5 },
                1024: { slidesPerView: 4 },
                768: { slidesPerView: 2 },
                480: { slidesPerView: 1 }
            }
        });
    });
}

document.addEventListener('livewire:navigated', initSwiper);
document.addEventListener('livewire:load', initSwiper);
document.addEventListener('DOMContentLoaded', initSwiper);
