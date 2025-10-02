@push('styles')
<style>
    .course-card {
        position: relative;
        transition: all 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-2px);
    }

    .course-image-wrapper {
        position: relative;
        aspect-ratio: 16/9;
        background-color: #f3f4f6;
        overflow: hidden;
    }

    .course-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .course-image.loaded {
        opacity: 1;
    }

    /* Fallback for placeholder SVG */
    .course-image[src*="course-placeholder.svg"] {
        opacity: 1;
        object-fit: contain;
        padding: 2rem;
        background-color: #f8fafc;
    }

    .course-image-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite linear;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .course-image-error {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f3f4f6;
        color: #6b7280;
        font-size: 0.875rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .course-image-error.show {
        opacity: 1;
    }

    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    .course-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-card .instructor-avatar {
        border: 2px solid #fff;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .course-card:hover img {
        transform: scale(1.05);
        transition: transform 0.6s ease;
    }

    .course-card:hover .shadow-sm {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Price Badge and Category Badge Shared Styles */
    .price-badge,
    .category-badge {
        position: absolute;
        z-index: 10;
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        height: 28px;
    }

    .category-badge {
        top: 1rem;
        left: 1rem;
        background-color: rgba(255, 255, 255, 0.9);
        color: #1a1a1a;
    }

    .price-badge {
        top: 1rem;
        right: 1rem;
    }

    .price-badge.free {
        background-color: #4CAF50;
        color: white;
    }

    .price-badge.premium {
        background-color: #2196F3;
        color: white;
    }

    .price-badge svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.5rem;
    }
</style>
@endpush

@forelse($courses as $course)
    <div class="course-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up">
        <div class="course-image-wrapper">
            <div class="course-image-placeholder"></div>
            <div class="course-image-error">
                <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Image unavailable</span>
            </div>
            <img class="course-image lazy"
                 data-src="{{ $course->thumbnail_url ?? asset('images/course-placeholder.svg') }}"
                 src="{{ asset('images/course-placeholder.svg') }}"
                 alt="{{ $course->title }}">
            <span class="category-badge">{{ $course->category->name }}</span>
            <span class="price-badge {{ $course->is_free ? 'free' : 'premium' }}">
                {{ $course->is_free ? 'Free' : 'Premium' }}
            </span>
        </div>

        <!-- Course Content -->
        <div class="p-6">
            <!-- Course Title -->
            <h3 class="course-title text-xl font-bold text-gray-900 mb-4">{{ $course->title }}</h3>

            <!-- Rating and Students -->
            <div class="flex items-center gap-2 mb-3">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="ml-1 text-sm font-bold text-gray-900">{{ number_format($course->average_rating, 1) }}</span>
                </div>
                <span class="text-sm text-gray-500">• {{ number_format($course->total_ratings) }} learners</span>
            </div>

            <!-- Duration -->
            <div class="text-sm text-gray-500 mb-6">
                @if($course->learning_hours)
                    {{ $course->learning_hours }} hrs
                @elseif($course->duration)
                    {{ $course->duration }}
                @else
                    Duration not specified
                @endif
            </div>

            <!-- View Course Button -->
            <a href="{{ route('client.courses.show', $course->slug) }}" 
               class="block w-full text-center py-2.5 px-4 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors duration-200 border border-gray-200">
                View Course
            </a>
        </div>
    </div>
@empty
    <div class="col-span-full text-center py-8">
    <div class="col-span-full text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No courses found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria</p>
    </div>
@endforelse

@if($courses->hasMorePages())
<div id="loadMoreTrigger" class="col-span-full text-center p-4">
    <div class="spinner hidden"></div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                const placeholder = img.previousElementSibling.previousElementSibling;
                const errorDisplay = img.previousElementSibling;
                
                if (img.dataset.src) {
                    const tempImage = new Image();
                    
                    tempImage.onload = function() {
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        
                        // Fade out placeholder after image is loaded
                        setTimeout(() => {
                            if (placeholder) {
                                placeholder.style.opacity = '0';
                            }
                        }, 100);
                    };
                    
                    tempImage.onerror = function() {
                        // Use placeholder instead of showing error state
                        img.src = '{{ asset("images/course-placeholder.svg") }}';
                        img.classList.add('loaded');
                        if (placeholder) {
                            placeholder.style.opacity = '0';
                        }
                        console.warn('Failed to load course image:', img.dataset.src);
                    };
                    
                    tempImage.src = img.dataset.src;
                }
                
                observer.unobserve(img);
            }
        });
    }, {
        rootMargin: '50px 0px',
        threshold: 0.1
    });

    // Observe all course images
    document.querySelectorAll('.course-image.lazy').forEach(img => {
        imageObserver.observe(img);
    });
});
</script>
@endpush