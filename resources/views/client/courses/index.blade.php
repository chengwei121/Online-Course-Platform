@extends('layouts.client')

@section('title', 'Browse Courses')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<style>
    /* Loading Screen */
    .loading-screen {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-screen.hidden {
        display: none;
    }

    .loading-spinner {
        text-align: center;
        background-color: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .spinner {
        width: 50px;
        height: 50px;
        margin: 0 auto;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .course-card {
    position: relative;
        overflow: hidden;
    }

    .price-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        z-index: 10;
    }

    .price-badge.free {
        background-color: #4CAF50;
        color: white;
    }

    .price-badge.premium {
        background-color: #2196F3;
        color: white;
    }

    .course-price {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2196F3;
    }

    .course-price.free {
        color: #4CAF50;
    }

    /* Course Card Hover Effect */
    .course-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Category Badge */
    .category-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        background-color: rgba(255, 255, 255, 0.9);
        color: #1a1a1a;
        z-index: 10;
    }

    /* Mobile Filter Drawer */
    .filter-drawer {
        position: fixed;
        top: 4rem; /* Account for navbar height */
        left: -100%;
        width: 100%;
        height: calc(100vh - 4rem); /* Subtract navbar height */
        background-color: white;
        z-index: 40; /* Lower than navbar */
        transition: left 0.3s ease-in-out;
        overflow-y: auto;
    }

    .filter-drawer.open {
        left: 0;
    }

    .filter-drawer-header {
        position: sticky;
        top: 0;
        background-color: white;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 41; /* Higher than drawer content but lower than navbar */
    }

    .filter-drawer-content {
        padding: 1rem;
    }

    @media (min-width: 768px) {
        .filter-drawer {
            position: static;
            width: auto;
            height: auto;
            background-color: transparent;
            overflow-y: visible;
        }

        .filter-drawer-header {
            display: none;
        }
    }

    /* Filter Toggle Button */
    .filter-toggle-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: #374151;
        cursor: pointer;
    }

    @media (min-width: 768px) {
        .filter-toggle-btn {
            display: none;
        }
    }

    /* Search Container */
    .search-container {
        margin-bottom: 3rem;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 1.25rem 1.75rem 1.25rem 3.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        font-size: 1.125rem;
        line-height: 1.5;
        color: #374151;
        background-color: #ffffff;
    }

    .search-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        width: 1.25rem;
        height: 1.25rem;
    }

    /* Filter Section */
    .filter-section {
        border-bottom: 2px solid #e5e7eb;
        padding: 1.5rem 0;
    }

    .filter-section:last-child {
        border-bottom: none;
    }

    /* Filter Header */
    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        user-select: none;
        border-bottom: 1px solid #E5E7EB;
        pointer-events: none;
    }

    .filter-title {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
    }

    /* Chevron Icon */
    .chevron {
        display: none;
    }

    .chevron-icon {
        width: 16px;
        height: 16px;
        transition: transform 0.2s ease;
        color: #111827;
    }

    .chevron-icon.open {
        transform: rotate(180deg);
    }

    /* Filter Content */
    .filter-content {
        padding: 1.25rem 1.5rem;
        overflow: visible;
        max-height: none !important;
    }

    /* Filter Item */
    .filter-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        gap: 1rem;
    }

    /* Checkbox */
    .filter-checkbox {
        width: 16px;
        height: 16px;
        border: 1.5px solid #D1D5DB;
        border-radius: 4px;
        cursor: pointer;
    }

    .filter-checkbox:checked {
        background-color: #4F46E5;
        border-color: #4F46E5;
    }

    /* Label */
    .filter-label {
        font-size: 14px;
        color: #374151;
    }

    /* Count */
    .filter-count {
        font-size: 12px;
        color: #6B7280;
        margin-left: 4px;
    }

    /* Active Filters */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin: 2rem 0;
        min-height: 2rem;
    }

    .active-filters:empty {
        display: none;
    }

    .filter-tag {
        padding: 0.5rem 1rem;
        font-size: 0.9375rem;
    }

    .remove-filter {
        margin-left: 6px;
        color: #4f46e5;
        cursor: pointer;
    }

    .clear-filters {
        font-size: 13px;
        color: #4f46e5;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .clear-filters:hover {
        color: #3730a3;
        transform: scale(1.05);
    }

    /* Enhanced filter animations */
    .filter-checkbox {
        transition: all 0.2s ease;
    }

    .filter-checkbox:checked {
        transform: scale(1.1);
    }

    .filter-tag {
        transition: all 0.3s ease;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px) scale(0.8);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    /* Loading state for filters */
    .filter-loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .filter-loading .filter-checkbox {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Improved search input */
    .search-input {
        transition: all 0.3s ease;
    }

    .search-input:focus {
        transform: scale(1.02);
    }
</style>
@endpush

@section('content')
<!-- Loading Screen -->
<div id="loadingScreen" class="loading-screen hidden">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p class="mt-4 text-gray-700 text-lg font-medium">Loading...</p>
    </div>
</div>

<!-- Main Content with proper spacing from header -->
<div class="pt-24 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Browse Our Courses</h1>

    <!-- Mobile Filter Toggle -->
    <button type="button" class="filter-toggle-btn mb-4" onclick="toggleFilterDrawer()">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
        </svg>
        Filters
    </button>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filters Sidebar -->
          <div class="filter-drawer">
            <div class="filter-drawer-header">
                <h2 class="text-lg font-semibold">Filters</h2>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="toggleFilterDrawer()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="filter-drawer-content">
                <!-- Skill Level -->
                <div class="filter-section">
                    <div class="filter-header" onclick="toggleFilter('skillLevel')" aria-expanded="true">
                        <h3 class="filter-title">Skill Level</h3>
                        <div class="chevron">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div id="skillLevel" class="filter-content show">
                        @foreach(['beginner', 'intermediate', 'advanced'] as $level)
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" value="{{ $level }}" 
                                   {{ request('level') == $level ? 'checked' : '' }}
                                   onchange="applyFilter('level', this.value, this.checked)">
                            <span class="filter-label capitalize">{{ $level }}</span>
                            <span class="filter-count">({{ $courses->where('level', $level)->count() }})</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Domains/Categories -->
                <div class="filter-section">
                    <div class="filter-header" onclick="toggleFilter('domains')" aria-expanded="true">
                        <h3 class="filter-title">Domains</h3>
                        <div class="chevron">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div id="domains" class="filter-content show">
                        @foreach($categories as $category)
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" value="{{ $category->id }}"
                                   {{ request('category') == $category->id ? 'checked' : '' }}
                                   onchange="applyFilter('category', this.value, this.checked)">
                            <span class="filter-label">{{ $category->name }}</span>
                            <span class="filter-count">({{ $category->courses_count }})</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Duration -->
                <div class="filter-section">
                    <div class="filter-header">
                        <h3 class="filter-title">Duration</h3>
                        <div class="chevron">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div id="duration" class="filter-content show">
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" value="short"
                                   {{ request('duration') == 'short' ? 'checked' : '' }}
                                   onchange="applyFilter('duration', this.value, this.checked)">
                            <span class="filter-label">0-3 hours</span>
                            <span class="filter-count">({{ $courses->where('learning_hours', '<=', 3)->count() }})</span>
                        </label>
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" value="medium"
                                   {{ request('duration') == 'medium' ? 'checked' : '' }}
                                   onchange="applyFilter('duration', this.value, this.checked)">
                            <span class="filter-label">3-6 hours</span>
                            <span class="filter-count">({{ $courses->whereBetween('learning_hours', [3, 6])->count() }})</span>
                        </label>
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" value="long"
                                   {{ request('duration') == 'long' ? 'checked' : '' }}
                                   onchange="applyFilter('duration', this.value, this.checked)">
                            <span class="filter-label">6+ hours</span>
                            <span class="filter-count">({{ $courses->where('learning_hours', '>', 6)->count() }})</span>
                        </label>
                    </div>
                </div>

                <!-- Course Rating -->
                <div class="filter-section">
                    <div class="filter-header" onclick="toggleFilter('rating')">
                        <h3 class="filter-title">Course Rating</h3>
                        <div class="chevron">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div id="rating" class="filter-content">
                        @foreach([4, 3, 2, 1] as $rating)
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" value="{{ $rating }}"
                                   {{ request('rating') == $rating ? 'checked' : '' }}
                                   onchange="applyFilter('rating', this.value, this.checked)">
                            <span class="filter-label">{{ $rating }}+ stars</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Type Filter -->
                <div class="filter-section">
                    <div class="filter-header" onclick="toggleFilter('priceType')" aria-expanded="true">
                        <h3 class="filter-title">Price</h3>
                        <div class="chevron">
                            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div id="priceType" class="filter-content show">
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" value="free"
                                   {{ request('price_type') == 'free' ? 'checked' : '' }}
                                   onchange="applyFilter('price_type', this.value, this.checked)">
                            <span class="filter-label">Free Courses</span>
                            <span class="filter-count">({{ $courses->where('is_free', true)->count() }})</span>
                        </label>
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" value="premium"
                                   {{ request('price_type') == 'premium' ? 'checked' : '' }}
                                   onchange="applyFilter('price_type', this.value, this.checked)">
                            <span class="filter-label">Premium Courses</span>
                            <span class="filter-count">({{ $courses->where('is_free', false)->count() }})</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Search Bar -->
            <div class="search-container">
                <input type="text" 
                       class="search-input"
                       placeholder="Search courses..." 
                       value="{{ request('search') }}"
                       id="searchInput">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Active Filters -->
            <div class="active-filters" style="display: flex; align-items: center; gap: 1rem; margin: 1rem 0;">
                @php
                    $activeFilters = [];
                    
                    // Handle multiple values for each filter type
                    $filterTypes = ['category', 'level', 'duration', 'rating', 'price_type'];
                    
                    foreach ($filterTypes as $filterType) {
                        $values = request($filterType);
                        if ($values) {
                            if (is_array($values)) {
                                foreach ($values as $value) {
                                    $activeFilters[] = ['type' => $filterType, 'value' => $value];
                                }
                            } else {
                                $activeFilters[] = ['type' => $filterType, 'value' => $values];
                            }
                        }
                    }
                @endphp
                
                @foreach($activeFilters as $filter)
                    <span class="filter-tag" data-filter="{{ $filter['type'] }}" data-value="{{ $filter['value'] }}">
                        @if($filter['type'] === 'category')
                            {{ $categories->find($filter['value'])->name ?? 'Unknown Category' }}
                        @elseif($filter['type'] === 'price_type')
                            {{ $filter['value'] === 'free' ? 'Free' : 'Premium' }}
                        @else
                            {{ ucfirst($filter['value']) }}
                        @endif
                        <button onclick="removeFilter('{{ $filter['type'] }}', '{{ $filter['value'] }}')" class="remove-filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                @endforeach
                
                <button onclick="clearAllFilters()" class="clear-filters" 
                        @if(count($activeFilters) == 0) style="opacity: 0.5; pointer-events: none;" @endif>
                    Clear All
                </button>
            </div>

            <!-- Courses Grid -->
            <div class="courses-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @include('client.courses._grid')
            </div>

            <!-- Pagination -->
            <div class="pagination-container mt-8">
                {{ $courses->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="{{ asset('js/course-filter-optimized.js') }}"></script>
@endpush