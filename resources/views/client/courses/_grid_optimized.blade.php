@push('styles')
<style>
    /* Optimized loading styles */
    .course-card {
        position: relative;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        will-change: transform;
    }

    .course-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .course-image-wrapper {
        position: relative;
        aspect-ratio: 16/9;
        background-color: #f8fafc;
        overflow: hidden;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .course-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.3s ease;
        opacity: 0;
    }

    .course-image.loaded {
        opacity: 1;
    }

    .course-image-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Optimized badges */
    .badge {
        position: absolute;
        z-index: 10;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(4px);
    }

    .category-badge {
        top: 0.75rem;
        left: 0.75rem;
        background-color: rgba(255, 255, 255, 0.9);
        color: #1f2937;
    }

    .price-badge {
        top: 0.75rem;
        right: 0.75rem;
        color: white;
    }

    .price-badge.free { background-color: #10b981; }
    .price-badge.premium { background-color: #3b82f6; }

    /* Optimized typography */
    .course-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    /* Loading state */
    .courses-loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Skeleton loading for better UX */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    .skeleton-title {
        height: 1.25rem;
        margin-bottom: 0.5rem;
        border-radius: 0.25rem;
    }

    .skeleton-text {
        height: 1rem;
        margin-bottom: 0.25rem;
        border-radius: 0.25rem;
    }
</style>
@endpush

@forelse($courses as $course)
    <div class="course-card bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
        <div class="course-image-wrapper">
            <div class="course-image-placeholder"></div>
            <img class="course-image"
                 data-src="{{ $course->thumbnail_url }}"
                 alt="{{ $course->title }}"
                 loading="lazy">
            
            <!-- Badges -->
            <div class="badge category-badge">{{ $course->category->name }}</div>
            <div class="badge price-badge {{ $course->is_free ? 'free' : 'premium' }}">
                {{ $course->is_free ? 'Free' : '$' . number_format($course->price, 0) }}
            </div>
        </div>

        <div class="p-4">
            <!-- Title -->
            <h3 class="course-title text-lg font-semibold text-gray-900 mb-3">
                {{ $course->title }}
            </h3>

            <!-- Rating and Info -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-1">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-700">
                            {{ number_format($course->average_rating, 1) }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-500">
                        ({{ number_format($course->total_ratings) }})
                    </span>
                </div>
                <span class="text-sm text-gray-500">{{ $course->duration }}h</span>
            </div>

            <!-- Action Button -->
            <a href="{{ route('client.courses.show', $course->slug) }}" 
               class="block w-full text-center py-2 px-4 rounded-md text-sm font-medium text-blue-600 hover:bg-blue-50 border border-blue-200 transition-colors duration-200">
                View Course
            </a>
        </div>
    </div>
@empty
    <div class="col-span-full text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No courses found</h3>
        <p class="text-gray-500">Try adjusting your search criteria or browse all courses</p>
    </div>
@endforelse

@push('scripts')
<script>
// Optimized intersection observer for lazy loading
const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            const placeholder = img.previousElementSibling;
            
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.onload = () => {
                    img.classList.add('loaded');
                    if (placeholder) {
                        placeholder.style.opacity = '0';
                        setTimeout(() => placeholder.remove(), 300);
                    }
                };
                img.onerror = () => {
                    img.src = '{{ asset("images/course-placeholder.jpg") }}';
                    img.classList.add('loaded');
                };
            }
            
            imageObserver.unobserve(img);
        }
    });
}, {
    rootMargin: '100px 0px',
    threshold: 0.1
});

// Initialize lazy loading when DOM is ready
document.querySelectorAll('.course-image[data-src]').forEach(img => {
    imageObserver.observe(img);
});
</script>
@endpush