{{-- Course Review Form --}}
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
    <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
        <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        Rate This Course
    </h3>
    
    <p class="text-gray-600 mb-6">Share your experience and help other students make informed decisions.</p>
    
    <form action="{{ route('client.courses.review', $course) }}" method="POST" id="reviewForm">
        @csrf
        
        <!-- Star Rating -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Your Rating</label>
            <div class="flex items-center space-x-1" id="starRating">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" 
                            class="star-btn w-8 h-8 text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors duration-200" 
                            data-rating="{{ $i }}"
                            aria-label="Rate {{ $i }} star{{ $i > 1 ? 's' : '' }}">
                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="ratingInput" value="" required>
            <p class="text-sm text-gray-500 mt-2" id="ratingText">Click on the stars to rate this course</p>
            @error('rating')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Comment -->
        <div class="mb-6">
            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                Your Review <span class="text-gray-500">(Optional)</span>
            </label>
            <textarea name="comment" 
                      id="comment" 
                      rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                      placeholder="Share your thoughts about this course... What did you learn? Would you recommend it to others?"
                      maxlength="1000">{{ old('comment') }}</textarea>
            <div class="flex justify-between items-center mt-2">
                <span class="text-sm text-gray-500">Help others by sharing your honest feedback</span>
                <span class="text-sm text-gray-400" id="charCount">0/1000</span>
            </div>
            @error('comment')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Submit Button -->
        <div class="flex items-center justify-between">
            <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Your review will be public and help other students
            </div>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    id="submitBtn"
                    disabled>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Submit Review
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const starButtons = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.getElementById('ratingText');
    const submitBtn = document.getElementById('submitBtn');
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');
    
    const ratingTexts = {
        1: 'Poor - This course didn\'t meet expectations',
        2: 'Fair - Some useful content but could be better',
        3: 'Good - Solid course with valuable content',
        4: 'Very Good - Excellent course, highly recommend',
        5: 'Outstanding - Exceptional course, couldn\'t be better!'
    };
    
    let selectedRating = 0;
    
    // Handle star rating
    starButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            updateStars();
            updateSubmitButton();
            ratingText.textContent = ratingTexts[selectedRating];
            ratingText.classList.remove('text-gray-500');
            ratingText.classList.add('text-yellow-600', 'font-medium');
        });
        
        button.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });
    });
    
    // Reset stars on mouse leave
    document.getElementById('starRating').addEventListener('mouseleave', function() {
        updateStars();
    });
    
    // Character counter for comment
    commentTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length}/1000`;
        
        if (length > 900) {
            charCount.classList.add('text-red-500');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-400');
        }
    });
    
    function highlightStars(rating) {
        starButtons.forEach((button, index) => {
            if (index < rating) {
                button.classList.remove('text-gray-300');
                button.classList.add('text-yellow-400');
            } else {
                button.classList.remove('text-yellow-400');
                button.classList.add('text-gray-300');
            }
        });
    }
    
    function updateStars() {
        highlightStars(selectedRating);
    }
    
    function updateSubmitButton() {
        if (selectedRating > 0) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Form submission handling
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        if (selectedRating === 0) {
            e.preventDefault();
            alert('Please select a rating before submitting your review.');
            return;
        }
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Submitting...
        `;
    });
});
</script>
@endpush