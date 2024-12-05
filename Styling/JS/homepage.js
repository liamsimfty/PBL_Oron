let currentSlide = 0;

const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

function showSlide(index) {
    // Wrap around if we reach the end or beginning
    if (index >= totalSlides) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = totalSlides - 1;
    } else {
        currentSlide = index;
    }

    // Move slider to the current slide
    document.querySelector('.slider').style.transform = `translateX(-${currentSlide * 100}%)`;
}

// Auto slide every 3 seconds
setInterval(() => {
    showSlide(currentSlide + 1);
}, 3000);

// Optional: Add buttons to navigate slides manually
document.querySelector('.prev').addEventListener('click', () => showSlide(currentSlide - 1));
document.querySelector('.next').addEventListener('click', () => showSlide(currentSlide + 1));
