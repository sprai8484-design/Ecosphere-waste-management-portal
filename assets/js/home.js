document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".hero-slider", {
        loop: true,
        speed: 1000,
        autoplay: {
            delay: 3000, // Slides every 3 seconds
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
});