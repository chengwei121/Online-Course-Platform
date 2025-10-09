/**
 * Optimized Course Filter and Search System
 * Handles course filtering with improved performance
 */

class CourseFilterManager {
    constructor(options = {}) {
        this.options = {
            searchDelay: options.searchDelay || 1000,
            filterDelay: options.filterDelay || 300,
            animationDuration: options.animationDuration || 300,
            ...options
        };

        this.searchTimeout = null;
        this.filterTimeout = null;
        this.isLoading = false;
        this.currentPage = 1;
        this.hasMore = true;

        this.filterState = {
            category: [],
            level: [],
            duration: [],
            rating: [],
            price_type: [],
            search: ''
        };

        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeFilterState();
        this.initializeLazyLoading();
        this.initializePaginationEvents();
    }

    bindEvents() {
        // Search input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', this.handleSearch.bind(this));
        }

        // Filter checkboxes
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', this.handleFilterChange.bind(this));
        });

        // Filter drawer toggle (mobile)
        const filterToggle = document.querySelector('.filter-toggle-btn');
        if (filterToggle) {
            filterToggle.addEventListener('click', this.toggleFilterDrawer.bind(this));
        }

        // Clear filters
        const clearFiltersBtn = document.querySelector('.clear-filters');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', this.clearAllFilters.bind(this));
        }

        // Remove individual filters
        document.addEventListener('click', (e) => {
            if (e.target.closest('.remove-filter')) {
                const filterTag = e.target.closest('.filter-tag');
                if (filterTag) {
                    const type = filterTag.dataset.filter;
                    const value = filterTag.dataset.value;
                    this.removeFilter(type, value);
                }
            }
        });
    }

    handleSearch(event) {
        const query = event.target.value.trim();
        this.filterState.search = query;

        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            if (!this.isLoading) {
                this.applyFilters();
            }
        }, this.options.searchDelay);
    }

    handleFilterChange(event) {
        const checkbox = event.target;
        const type = this.getFilterTypeFromCheckbox(checkbox);
        const value = checkbox.value;
        const isChecked = checkbox.checked;

        if (isChecked) {
            if (!this.filterState[type].includes(value)) {
                this.filterState[type].push(value);
            }
        } else {
            this.filterState[type] = this.filterState[type].filter(item => item !== value);
        }

        clearTimeout(this.filterTimeout);
        this.filterTimeout = setTimeout(() => {
            this.applyFilters();
        }, this.options.filterDelay);
    }

    getFilterTypeFromCheckbox(checkbox) {
        const onchangeAttr = checkbox.getAttribute('onchange');
        if (onchangeAttr && onchangeAttr.includes('applyFilter')) {
            const match = onchangeAttr.match(/applyFilter\('([^']+)'/);
            return match ? match[1] : 'unknown';
        }
        return 'unknown';
    }

    async applyFilters() {
        if (this.isLoading) return;
        
        // Show subtle loading indicator (not full screen)
        this.showSubtleLoading();
        this.updateURL();

        try {
            const url = new URL(window.location.href);
            console.log('Request URL:', url.toString());
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Server response:', data);

            if (data.html) {
                this.updateCoursesGrid(data.html);
                this.hasMore = data.has_more || false;
                
                // Update pagination if provided
                if (data.pagination) {
                    this.updatePagination(data.pagination);
                }
            }

            this.initializeLazyLoading();
            this.initializePaginationEvents();

        } catch (error) {
            console.error('Error applying filters:', error);
            this.showError('Failed to load courses. Please try again.');
        } finally {
            this.hideSubtleLoading();
        }
    }

    updateCoursesGrid(html) {
        const coursesGrid = document.querySelector('.courses-grid');
        if (coursesGrid) {
            coursesGrid.innerHTML = html;
            
            // Trigger AOS refresh if available
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
        }
    }

    updatePagination(paginationHtml) {
        const paginationContainer = document.querySelector('.pagination-container');
        if (paginationContainer) {
            paginationContainer.innerHTML = paginationHtml;
        } else {
            // If pagination container doesn't exist, find the existing pagination and replace it
            const existingPagination = document.querySelector('nav[role="navigation"]');
            if (existingPagination) {
                existingPagination.outerHTML = paginationHtml;
            }
        }
    }

    initializePaginationEvents() {
        // Remove existing event listeners to prevent duplication
        document.querySelectorAll('nav[role="navigation"] a').forEach(link => {
            // Clone node to remove all event listeners
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
        });

        // Handle pagination link clicks with AJAX
        document.querySelectorAll('nav[role="navigation"] a').forEach(link => {
            if (link.getAttribute('href') && !link.classList.contains('disabled') && !link.classList.contains('current')) {
                link.addEventListener('click', async (e) => {
                    e.preventDefault();
                    
                    const url = new URL(link.getAttribute('href'));
                    
                    // Preserve current filters from filter state (not URL)
                    ['category', 'level', 'duration', 'rating', 'price_type', 'search'].forEach(param => {
                        url.searchParams.delete(param);
                    });
                    
                    // Add current filter state
                    Object.keys(this.filterState).forEach(filterType => {
                        if (filterType === 'search') {
                            if (this.filterState[filterType]) {
                                url.searchParams.set(filterType, this.filterState[filterType]);
                            }
                        } else if (this.filterState[filterType].length > 0) {
                            this.filterState[filterType].forEach(value => {
                                url.searchParams.append(filterType, value);
                            });
                        }
                    });
                    
                    this.showLoading();
                    
                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.html) {
                            this.updateCoursesGrid(data.html);
                            if (data.pagination) {
                                this.updatePagination(data.pagination);
                            }
                            
                            // Update URL without page reload
                            window.history.pushState({}, '', url);
                            
                            // Scroll to top of courses grid
                            const coursesGrid = document.querySelector('.courses-grid');
                            if (coursesGrid) {
                                coursesGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        }

                        this.initializeLazyLoading();
                        // Reinitialize pagination events for new links
                        setTimeout(() => this.initializePaginationEvents(), 100);

                    } catch (error) {
                        console.error('Error loading page:', error);
                        // Fallback to regular navigation
                        window.location.href = link.getAttribute('href');
                    } finally {
                        this.hideLoading();
                    }
                });
            }
        });
    }

    updateURL() {
        const url = new URL(window.location.href);
        
        // Clear existing parameters (including page)
        ['category', 'level', 'duration', 'rating', 'price_type', 'search', 'page'].forEach(param => {
            url.searchParams.delete(param);
        });

        // Add current filter state
        Object.keys(this.filterState).forEach(filterType => {
            if (filterType === 'search') {
                if (this.filterState[filterType]) {
                    url.searchParams.set(filterType, this.filterState[filterType]);
                }
            } else if (this.filterState[filterType].length > 0) {
                this.filterState[filterType].forEach(value => {
                    url.searchParams.append(filterType, value);
                });
            }
        });

        // Reset to page 1 when filters are applied
        this.currentPage = 1;

        window.history.pushState({}, '', url);
    }

    removeFilter(type, value) {
        this.filterState[type] = this.filterState[type].filter(item => item !== value);
        
        // Update corresponding checkbox
        const checkbox = document.querySelector(`input[type="checkbox"][value="${value}"]`);
        if (checkbox) {
            checkbox.checked = false;
        }

        // Animate filter tag removal
        const filterTag = document.querySelector(`[data-filter="${type}"][data-value="${value}"]`);
        if (filterTag) {
            filterTag.style.opacity = '0';
            filterTag.style.transform = 'translateX(-10px) scale(0.9)';
            setTimeout(() => filterTag.remove(), this.options.animationDuration);
        }

        this.applyFilters();
    }

    clearAllFilters() {
        // Reset filter state
        Object.keys(this.filterState).forEach(key => {
            this.filterState[key] = key === 'search' ? '' : [];
        });

        // Uncheck all checkboxes
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Clear search input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = '';
        }

        // Animate filter tags removal
        document.querySelectorAll('.filter-tag').forEach((tag, index) => {
            setTimeout(() => {
                tag.style.opacity = '0';
                tag.style.transform = 'translateX(-10px) scale(0.9)';
            }, index * 50);
        });

        setTimeout(() => {
            this.applyFilters();
        }, 500);
    }

    toggleFilterDrawer() {
        const filterDrawer = document.querySelector('.filter-drawer');
        if (filterDrawer) {
            filterDrawer.classList.toggle('open');
        }
    }

    initializeFilterState() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Initialize from URL parameters
        this.filterState.category = urlParams.getAll('category');
        this.filterState.level = urlParams.getAll('level');
        this.filterState.duration = urlParams.getAll('duration');
        this.filterState.rating = urlParams.getAll('rating');
        this.filterState.price_type = urlParams.getAll('price_type');
        this.filterState.search = urlParams.get('search') || '';

        // Update UI to reflect current state
        this.updateFilterUI();
    }

    updateFilterUI() {
        // Update checkboxes
        Object.keys(this.filterState).forEach(filterType => {
            if (filterType === 'search') return;
            
            this.filterState[filterType].forEach(value => {
                const checkbox = document.querySelector(`input[type="checkbox"][value="${value}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        });

        // Update search input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = this.filterState.search;
        }
    }

    initializeLazyLoading() {
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
                    }
                    
                    imageObserver.unobserve(img);
                }
            });
        }, {
            rootMargin: '100px 0px',
            threshold: 0.1
        });

        document.querySelectorAll('.course-image[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Subtle loading - just top progress bar, no full screen overlay
    showSubtleLoading() {
        this.isLoading = true;
        
        // Show minimal top progress bar
        let loader = document.getElementById('topProgressBar');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'topProgressBar';
            loader.style.cssText = `
                position: fixed;
                top: 4rem;
                left: 0;
                width: 0%;
                height: 3px;
                background: linear-gradient(90deg, #4F46E5, #0EA5E9);
                z-index: 9998;
                transition: width 0.3s ease;
                box-shadow: 0 0 10px rgba(79, 70, 229, 0.5);
            `;
            document.body.appendChild(loader);
        }
        
        // Animate to 70%
        setTimeout(() => {
            loader.style.width = '70%';
        }, 10);

        // Add subtle opacity to courses grid
        const coursesGrid = document.querySelector('.courses-grid');
        if (coursesGrid) {
            coursesGrid.style.transition = 'opacity 0.2s ease';
            coursesGrid.style.opacity = '0.6';
        }
    }

    hideSubtleLoading() {
        this.isLoading = false;
        
        const loader = document.getElementById('topProgressBar');
        if (loader) {
            // Complete to 100%
            loader.style.width = '100%';
            // Fade out
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => {
                    if (loader.parentNode) {
                        loader.remove();
                    }
                }, 300);
            }, 100);
        }

        // Restore courses grid opacity
        const coursesGrid = document.querySelector('.courses-grid');
        if (coursesGrid) {
            coursesGrid.style.opacity = '1';
        }
    }

    // Keep old methods for backward compatibility (pagination)
    showLoading() {
        this.showSubtleLoading();
    }

    hideLoading() {
        this.hideSubtleLoading();
    }

    showError(message) {
        const coursesGrid = document.querySelector('.courses-grid');
        if (coursesGrid) {
            coursesGrid.innerHTML = `
                <div class="col-span-full text-center p-8">
                    <div class="text-red-500 mb-4">
                        <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-lg font-medium">${message}</p>
                        <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                            Reload Page
                        </button>
                    </div>
                </div>
            `;
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.courseFilterManager = new CourseFilterManager();
    
    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    }
});

// Global functions for backward compatibility with inline onchange handlers
window.applyFilter = function(type, value, isChecked) {
    console.log('applyFilter called:', { type, value, isChecked });
    
    if (window.courseFilterManager) {
        if (isChecked) {
            if (!window.courseFilterManager.filterState[type].includes(value)) {
                window.courseFilterManager.filterState[type].push(value);
            }
        } else {
            window.courseFilterManager.filterState[type] = window.courseFilterManager.filterState[type].filter(item => item !== value);
        }
        
        console.log('Updated filter state:', window.courseFilterManager.filterState);
        
        clearTimeout(window.courseFilterManager.filterTimeout);
        window.courseFilterManager.filterTimeout = setTimeout(() => {
            window.courseFilterManager.applyFilters();
        }, window.courseFilterManager.options.filterDelay);
    } else {
        console.error('courseFilterManager not found');
    }
};

window.removeFilter = function(type, value) {
    if (window.courseFilterManager) {
        window.courseFilterManager.removeFilter(type, value);
    }
};

window.clearAllFilters = function() {
    if (window.courseFilterManager) {
        window.courseFilterManager.clearAllFilters();
    }
};

window.toggleFilterDrawer = function() {
    if (window.courseFilterManager) {
        window.courseFilterManager.toggleFilterDrawer();
    }
};