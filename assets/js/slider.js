// script.js

// This ensures our script runs only after the whole page is loaded and ready
document.addEventListener('DOMContentLoaded', () => {

    /* --- 1. The Automatic Sliding Gallery --- */
    const sliderTrack = document.getElementById('sliderTrack');
    
    if (sliderTrack) {
        let currentSlide = 0;
        const totalSlides = 10;
        const slideWidth = 300; // This must match the CSS width of each image

        function startSlider() {
            currentSlide++;
            
            // If we reach the end, reset to the first slide
            if (currentSlide >= totalSlides) {
                currentSlide = 0;
            }
            
            // Calculate the move distance and apply it with CSS transform
            const moveDistance = currentSlide * slideWidth;
            sliderTrack.style.transform = `translateX(-${moveDistance}px)`;
            
            // Add a clean transition for smoothness
            sliderTrack.style.transition = 'transform 0.5s ease'; 
        }

        // Run this function automatically every 3 seconds (3000ms)
        setInterval(startSlider, 3000);
    }

    /* --- 2. The Interactive FAQ Accordion --- */
    const faqButtons = document.querySelectorAll('.faq-question');

    faqButtons.forEach(button => {
        button.addEventListener('click', () => {
            const faqItem = button.parentElement; // The overall faq-item container
            const answer = faqItem.querySelector('.faq-answer'); // The hidden answer text

            // Check if the current answer is already open
            const isOpen = answer.style.display === 'block';

            // First, close *all* FAQ answers on the page (creates a clean, single-open experience)
            document.querySelectorAll('.faq-answer').forEach(a => a.style.display = 'none');
            // Change all plus icons back to "+"
            document.querySelectorAll('.faq-question span').forEach(s => s.textContent = '+');

            // Now, open only the one we clicked (if it wasn't already open)
            answer.style.display = isOpen ? 'none' : 'block';
            button.querySelector('span').textContent = isOpen ? '+' : '-';
        });
    });
});