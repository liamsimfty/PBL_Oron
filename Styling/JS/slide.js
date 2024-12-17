document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slide');
    let currentIndex = 0;

    const showSlide = (index) => {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    };

    const prevSlide = () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
        showSlide(currentIndex);
    };

    const nextSlide = () => {
        currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
        showSlide(currentIndex);
    };

    document.querySelectorAll('.arrow.prev').forEach(arrow => {
        arrow.addEventListener('click', prevSlide);
    });

    document.querySelectorAll('.arrow.next').forEach(arrow => {
        arrow.addEventListener('click', nextSlide);
    });

    showSlide(currentIndex);
});
