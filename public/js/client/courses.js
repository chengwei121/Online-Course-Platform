document.addEventListener('DOMContentLoaded', function() {
    // Lazy Loading Images
    const lazyImages = document.querySelectorAll('img.lazy');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));

    // Price Filter Functionality
    const priceFilters = document.querySelectorAll('[name="price_type"]');
    const courseCards = document.querySelectorAll('.course-card');

    priceFilters.forEach(filter => {
        filter.addEventListener('change', updateCourseDisplay);
    });

    function updateCourseDisplay() {
        const selectedFilters = Array.from(priceFilters)
            .filter(filter => filter.checked)
            .map(filter => filter.value);

        courseCards.forEach(card => {
            const isFree = card.querySelector('.price-badge').classList.contains('free');
            const shouldShow = selectedFilters.length === 0 || 
                (isFree && selectedFilters.includes('free')) ||
                (!isFree && selectedFilters.includes('premium'));

            card.style.display = shouldShow ? 'block' : 'none';
        });

        // Update course counts
        updateCourseCounts();
    }

    function updateCourseCounts() {
        const freeCount = document.querySelector('.free-count');
        const premiumCount = document.querySelector('.premium-count');
        
        if (freeCount && premiumCount) {
            const visibleCourses = Array.from(courseCards).filter(card => 
                card.style.display !== 'none'
            );
            
            const freeCourses = visibleCourses.filter(card => 
                card.querySelector('.price-badge').classList.contains('free')
            ).length;
            
            const premiumCourses = visibleCourses.length - freeCourses;
            
            freeCount.textContent = freeCourses;
            premiumCount.textContent = premiumCourses;
        }
    }

    // Initialize course counts
    updateCourseCounts();

    // Animation on Scroll
    const courseCardsArray = Array.from(courseCards);
    const cardObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('aos-animate');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    courseCardsArray.forEach(card => {
        cardObserver.observe(card);
    });
}); 