@extends('layouts.admin')

@section('title', 'Payments')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Payments</h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-dark action-btn" onclick="window.location.reload()" title="Refresh Data">
                <i class="fas fa-sync-alt me-1"></i>
                <span class="d-none d-sm-inline">Refresh</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-dark action-btn" title="Export Data">
                <i class="fas fa-download me-1"></i>
                <span class="d-none d-sm-inline">Export</span>
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid px-2">
    <!-- Enhanced Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card h-100 border-0 shadow-sm stats-card revenue-card">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div class="stats-icon-wrapper">
                            <div class="stats-icon bg-gradient-success">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stats-value h3 mb-1 text-dark fw-bold">RM{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                        <div class="stats-label text-muted small fw-semibold text-uppercase mb-1">Total Revenue</div>
                        <div class="stats-trend text-success small">
                            <i class="fas fa-arrow-up me-1"></i>+12.5% from last month
                        </div>
                    </div>
                    <div class="stats-bg-pattern"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card h-100 border-0 shadow-sm stats-card payments-card">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div class="stats-icon-wrapper">
                            <div class="stats-icon bg-gradient-primary">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stats-value h3 mb-1 text-dark fw-bold">{{ number_format($stats['total_payments'] ?? 0) }}</div>
                        <div class="stats-label text-muted small fw-semibold text-uppercase mb-1">Total Payments</div>
                        <div class="stats-trend text-primary small">
                            <i class="fas fa-arrow-up me-1"></i>+8.3% this week
                        </div>
                    </div>
                    <div class="stats-bg-pattern"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card h-100 border-0 shadow-sm stats-card pending-card">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div class="stats-icon-wrapper">
                            <div class="stats-icon bg-gradient-warning">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stats-value h3 mb-1 text-dark fw-bold">{{ number_format($stats['pending_payments'] ?? 0) }}</div>
                        <div class="stats-label text-muted small fw-semibold text-uppercase mb-1">Pending Payments</div>
                        <div class="stats-trend text-warning small">
                            <i class="fas fa-clock me-1"></i>Awaiting processing
                        </div>
                    </div>
                    <div class="stats-bg-pattern"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card h-100 border-0 shadow-sm stats-card failed-card">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="flex-shrink-0 me-3">
                        <div class="stats-icon-wrapper">
                            <div class="stats-icon bg-gradient-danger">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="stats-value h3 mb-1 text-dark fw-bold">{{ number_format($stats['failed_payments'] ?? 0) }}</div>
                        <div class="stats-label text-muted small fw-semibold text-uppercase mb-1">Failed Payments</div>
                        <div class="stats-trend text-danger small">
                            <i class="fas fa-arrow-down me-1"></i>-2.1% improvement
                        </div>
                    </div>
                    <div class="stats-bg-pattern"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Table with Header and Footer -->
    <div class="card border-0 shadow-lg">
        <!-- Card Header -->
        <div class="card-header bg-secondary text-white py-3 px-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-3 col-md-12">
                    <div class="header-content">
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="fas fa-credit-card me-2 text-light"></i>Payment Management
                        </h4>
                        <p class="mb-0 text-light opacity-75 small">
                            {{ $payments->total() }} total records • Page {{ $payments->currentPage() }} of {{ $payments->lastPage() }}
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-12">
                    <div class="search-container mt-lg-0 mt-3">
                        <form method="GET" action="{{ route('admin.payments.index') }}" class="position-relative" id="headerSearchForm">
                            <input type="search" 
                                   name="search" 
                                   id="headerSearch"
                                   class="form-control form-control-lg header-search" 
                                   placeholder="Search payments, students, courses..."
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            <div class="search-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            @if(request()->hasAny(['payment_status', 'date_from', 'date_to', 'amount_min', 'amount_max']))
                                @foreach(request()->except(['search', 'page']) as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                            @endif
                            <button type="submit" style="display: none;"></button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-12">
                    <div class="header-actions d-flex justify-content-lg-end justify-content-start align-items-center gap-2 mt-lg-0 mt-3">
                        <button type="button" class="btn btn-outline-light btn-sm modern-btn filter-btn" title="Filter Data">
                            <i class="fas fa-filter me-1"></i>
                            <span class="d-none d-md-inline">Filter</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Body -->
        <div class="card-body p-3">
            @include('admin.payments.partials.payments-table', ['payments' => $payments])
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced Statistics Cards */
.card {
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
}

.stats-card {
    position: relative;
    overflow: hidden;
    height: 100%;
    min-height: 120px;
}

.stats-card .card-body {
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    align-items: center;
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12) !important;
}

/* Individual card styling with unique colors */
.revenue-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fff9 100%);
    border-top: 4px solid #28a745;
}

.payments-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    border-top: 4px solid #007bff;
}

.pending-card {
    background: linear-gradient(135deg, #ffffff 0%, #fffdf8 100%);
    border-top: 4px solid #ffc107;
}

.failed-card {
    background: linear-gradient(135deg, #ffffff 0%, #fff8f8 100%);
    border-top: 4px solid #dc3545;
}

/* Enhanced icon design */
.stats-icon-wrapper {
    position: relative;
    flex-shrink: 0;
}

.stats-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: white;
    position: relative;
    z-index: 2;
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    flex-shrink: 0;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
}

/* Background patterns */
.stats-bg-pattern {
    position: absolute;
    top: -15px;
    right: -15px;
    width: 70px;
    height: 70px;
    opacity: 0.08;
    background: radial-gradient(circle, currentColor 2px, transparent 2px);
    background-size: 10px 10px;
    z-index: 1;
}

.revenue-card .stats-bg-pattern {
    color: #28a745;
}

.payments-card .stats-bg-pattern {
    color: #007bff;
}

.pending-card .stats-bg-pattern {
    color: #ffc107;
}

.failed-card .stats-bg-pattern {
    color: #dc3545;
}

/* Typography enhancements */
.stats-value {
    font-weight: 800;
    line-height: 1.1;
    color: #2d3748 !important;
    font-size: 1.75rem;
    margin-bottom: 0.25rem;
}

.stats-label {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #718096 !important;
    font-size: 0.7rem;
    margin-bottom: 0.25rem;
}

.stats-trend {
    font-weight: 600;
    font-size: 0.75rem;
    line-height: 1.2;
}

.stats-trend i {
    font-size: 0.65rem;
}

.revenue-card .stats-bg-pattern {
    color: #28a745;
}

.payments-card .stats-bg-pattern {
    color: #007bff;
}

.pending-card .stats-bg-pattern {
    color: #ffc107;
}

.failed-card .stats-bg-pattern {
    color: #dc3545;
}

/* Typography enhancements */
.stats-value {
    font-weight: 800;
    line-height: 1.1;
    color: #2d3748 !important;
    font-size: 1.8rem;
}

.stats-label {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #718096 !important;
    font-size: 0.75rem;
}

.stats-trend {
    font-weight: 600;
    font-size: 0.8rem;
}

.stats-trend i {
    font-size: 0.7rem;
}

/* Action buttons - Black and White theme */
.action-btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 45px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-dark.action-btn {
    background-color: #212529;
    border-color: #212529;
    color: #ffffff;
}

.btn-dark.action-btn:hover {
    background-color: #000000;
    border-color: #000000;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.btn-outline-dark.action-btn {
    background-color: #ffffff;
    border-color: #212529;
    color: #212529;
    border-width: 2px;
}

.btn-outline-dark.action-btn:hover {
    background-color: #212529;
    border-color: #212529;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.action-btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
}

.action-btn i {
    font-size: 0.875rem;
}

/* Form styling - keeping neutral colors */
.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #212529;
    box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
}

.btn-primary {
    background-color: #212529;
    border-color: #212529;
}

.btn-primary:hover {
    background-color: #000000;
    border-color: #000000;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #ffffff;
}

/* Table Card - Custom Grey Gradient Header and Footer */
.card-header.bg-secondary {
    background: linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%) !important;
    border-bottom: 2px solid #334155;
    border-radius: 16px 16px 0 0 !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-footer.bg-secondary {
    background: linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%) !important;
    border-top: 2px solid #334155;
    border-radius: 0 0 16px 16px !important;
    box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
}

.card-header.bg-secondary h5 {
    color: #ffffff;
    font-weight: 700;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.card-header.bg-secondary small,
.card-footer.bg-secondary small {
    color: rgba(255, 255, 255, 0.9);
}

/* Enhanced Action Buttons */
.table-action-btn {
    border-radius: 8px !important;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 40px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.table-action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.table-action-btn:hover::before {
    left: 100%;
}

.btn-light.table-action-btn {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-color: rgba(255, 255, 255, 0.9);
    color: #334155;
    box-shadow: 0 2px 4px rgba(51, 65, 85, 0.15);
}

.btn-light.table-action-btn:hover {
    background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
    border-color: #ffffff;
    color: #334155;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(51, 65, 85, 0.25);
}

.btn-outline-light.table-action-btn {
    border-color: rgba(255, 255, 255, 0.7);
    color: #ffffff;
    background-color: transparent;
}

.btn-outline-light.table-action-btn:hover {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
    border-color: rgba(255, 255, 255, 0.95);
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.card-header .badge.bg-light {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
    color: #334155 !important;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(51, 65, 85, 0.15);
    border: 1px solid rgba(51, 65, 85, 0.1);
}

/* Enhanced Header Layout */
.header-content h4 {
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
    font-weight: 700;
    letter-spacing: -0.025em;
}

.header-content p {
    font-size: 0.875rem;
    opacity: 0.85;
    margin-bottom: 0;
}

/* Modern Button Styling */
.modern-btn {
    border-radius: 8px !important;
    padding: 0.5rem 1rem !important;
    font-weight: 500;
    font-size: 0.875rem;
    border: 1.5px solid !important;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
    min-width: 44px;
}

.modern-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.modern-btn:active {
    transform: translateY(0);
}

/* Primary Button (Print) */
.btn-light.modern-btn {
    background: #ffffff !important;
    border-color: #ffffff !important;
    color: #1f2937 !important;
    font-weight: 600;
}

.btn-light.modern-btn:hover {
    background: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #111827 !important;
}

/* Secondary Buttons */
.btn-outline-light.modern-btn {
    background: transparent !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
    color: rgba(255, 255, 255, 0.95) !important;
}

.btn-outline-light.modern-btn:hover {
    background: rgba(255, 255, 255, 0.15) !important;
    border-color: rgba(255, 255, 255, 0.6) !important;
    color: #ffffff !important;
}

/* Dropdown Styling */
.modern-dropdown {
    background: #ffffff;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    padding: 0.5rem 0;
    min-width: 200px;
    margin-top: 0.5rem;
}

.modern-dropdown .dropdown-item {
    color: #374151;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    background: none;
    transition: all 0.15s ease;
}

.modern-dropdown .dropdown-item:hover {
    background: #f8fafc;
    color: #1f2937;
    padding-left: 1.5rem;
}

.modern-dropdown .dropdown-item i {
    width: 16px;
    text-align: center;
    opacity: 0.7;
}

.modern-dropdown .dropdown-divider {
    border-top: 1px solid #e5e7eb;
    margin: 0.5rem 0;
}

/* Filter Dropdown Animations */
@keyframes dropdownFadeIn {
    from {
        opacity: 0;
        transform: translateY(-8px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes dropdownFadeOut {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(-8px) scale(0.95);
    }
}

/* Toast Animations */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

/* Header Actions Layout */
.header-actions {
    gap: 0.5rem;
}

/* Pagination Info Alignment Fix */
.pagination-info,
.dataTables_info,
p:has(.fas.fa-info-circle) {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: #6c757d;
}

.pagination-info i,
.dataTables_info i {
    flex-shrink: 0;
}

/* Table pagination container */
.table-pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

/* Responsive Design */
@media (max-width: 991px) {
    .header-actions {
        margin-top: 1rem;
        width: 100%;
        justify-content: flex-start;
    }
    
    .modern-btn {
        padding: 0.375rem 0.75rem !important;
        font-size: 0.8rem;
    }
}

@media (max-width: 767px) {
    .header-content h4 {
        font-size: 1.1rem;
    }
    
    .header-content p {
        font-size: 0.8rem;
    }
    
    .header-actions {
        flex-wrap: wrap;
        gap: 0.375rem;
    }
    
    .modern-btn {
        padding: 0.375rem 0.625rem !important;
        min-width: 40px;
    }
    
    .modern-btn span {
        display: none !important;
    }
}

@media (max-width: 576px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
    }
}

/* Compact table styling */
.table-sm {
    font-size: 0.875rem;
}

.table-sm th {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    font-weight: 500;
    color: #495057;
    font-size: 0.875rem;
}

.table-sm td {
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.2s ease;
    padding: 0.5rem !important;
    font-size: 0.875rem;
}

.table-sm tbody tr:hover {
    background: linear-gradient(135deg, rgba(100, 116, 139, 0.03) 0%, rgba(71, 85, 105, 0.05) 100%);
}

/* Compact action buttons */
.compact-btn {
    width: 28px;
    height: 28px;
    padding: 0 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    font-size: 0.75rem;
    border-width: 1px;
}

.compact-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

/* Compact badges */
.badge.small {
    font-size: 0.7rem !important;
    padding: 0.25rem 0.5rem !important;
    font-weight: 500;
}

/* Header search compact */
.header-search {
    background: rgba(255, 255, 255, 0.95) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    color: #1f2937 !important;
    padding-left: 2.5rem !important;
    border-radius: 8px !important;
    font-size: 0.875rem;
    height: 38px;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
}

.header-search:focus {
    background: rgba(255, 255, 255, 1) !important;
    border-color: rgba(255, 255, 255, 0.8) !important;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1) !important;
    color: #111827 !important;
}

.header-search::placeholder {
    color: rgba(107, 114, 128, 0.8) !important;
    font-size: 0.8rem;
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(107, 114, 128, 0.6);
    font-size: 0.875rem;
    pointer-events: none;
    z-index: 2;
}
</style>
@endpush

@push('scripts')
<!-- jsPDF library for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality with debouncing for header search
    let searchTimeout;
    const headerSearchInput = document.querySelector('#headerSearch');
    if (headerSearchInput) {
        headerSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
        headerSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    }

    // Filter button dropdown functionality
    const filterBtn = document.querySelector('.filter-btn');
    if (filterBtn) {
        let dropdown;
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (dropdown) {
                dropdown.remove();
                dropdown = null;
                return;
            }
            dropdown = document.createElement('div');
            dropdown.className = 'filter-dropdown-menu';
            dropdown.style.cssText = `
                position: absolute;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border: 1px solid rgba(0,0,0,0.08);
                border-radius: 12px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.15), 0 8px 16px rgba(0,0,0,0.1);
                padding: 8px 0;
                min-width: 220px;
                z-index: 9999;
                backdrop-filter: blur(20px);
                animation: dropdownFadeIn 0.2s ease-out;
            `;

            // Use getBoundingClientRect for accurate positioning
            const rect = filterBtn.getBoundingClientRect();
            dropdown.style.top = (rect.bottom + window.scrollY + 8) + 'px';
            dropdown.style.left = (rect.left + window.scrollX) + 'px';

            dropdown.innerHTML = `
                <div style="padding: 8px 16px 4px; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e5e7eb; margin-bottom: 4px;">
                    <i class="fas fa-filter me-1"></i>Filter by Status
                </div>
                <button class="filter-option" data-status="all" style="width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 0.875rem; font-weight: 500; color: #374151; transition: all 0.15s ease; position: relative; display: flex; align-items: center;">
                    <i class="fas fa-list me-2" style="width: 16px; color: #6b7280;"></i>
                    <span>All Statuses</span>
                    <div class="status-indicator" style="margin-left: auto; width: 8px; height: 8px; border-radius: 50%; background: linear-gradient(135deg, #6b7280, #9ca3af);"></div>
                </button>
                <button class="filter-option" data-status="completed" style="width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 0.875rem; font-weight: 500; color: #374151; transition: all 0.15s ease; position: relative; display: flex; align-items: center;">
                    <i class="fas fa-check-circle me-2" style="width: 16px; color: #10b981;"></i>
                    <span>Completed</span>
                    <div class="status-indicator" style="margin-left: auto; width: 8px; height: 8px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #34d399);"></div>
                </button>
                <button class="filter-option" data-status="pending" style="width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 0.875rem; font-weight: 500; color: #374151; transition: all 0.15s ease; position: relative; display: flex; align-items: center;">
                    <i class="fas fa-clock me-2" style="width: 16px; color: #f59e0b;"></i>
                    <span>Pending</span>
                    <div class="status-indicator" style="margin-left: auto; width: 8px; height: 8px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #fbbf24);"></div>
                </button>
                <button class="filter-option" data-status="failed" style="width: 100%; padding: 10px 16px; border: none; background: none; text-align: left; font-size: 0.875rem; font-weight: 500; color: #374151; transition: all 0.15s ease; position: relative; display: flex; align-items: center;">
                    <i class="fas fa-times-circle me-2" style="width: 16px; color: #ef4444;"></i>
                    <span>Failed</span>
                    <div class="status-indicator" style="margin-left: auto; width: 8px; height: 8px; border-radius: 50%; background: linear-gradient(135deg, #ef4444, #f87171);"></div>
                </button>
                <div style="border-top: 1px solid #e5e7eb; margin: 8px 0; padding-top: 8px;">
                    <button class="filter-option clear-filter" data-status="clear" style="width: 100%; padding: 8px 16px; border: none; background: none; text-align: center; font-size: 0.8rem; font-weight: 500; color: #6b7280; transition: all 0.15s ease;">
                        <i class="fas fa-eraser me-1"></i>Clear Filter
                    </button>
                </div>
            `;

            // Add hover effects
            dropdown.addEventListener('mouseover', function(e) {
                if (e.target.classList.contains('filter-option')) {
                    e.target.style.background = 'linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%)';
                    e.target.style.paddingLeft = '20px';
                }
            });

            dropdown.addEventListener('mouseout', function(e) {
                if (e.target.classList.contains('filter-option')) {
                    e.target.style.background = 'none';
                    e.target.style.paddingLeft = '16px';
                }
            });

            document.body.appendChild(dropdown);

            dropdown.querySelectorAll('.filter-option').forEach(btn => {
                btn.addEventListener('click', function(ev) {
                    ev.preventDefault();
                    const status = this.getAttribute('data-status');
                    let url = new URL(window.location.href);

                    if (status === 'all' || status === 'clear') {
                        url.searchParams.delete('payment_status');
                    } else {
                        url.searchParams.set('payment_status', status);
                    }
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            });

            setTimeout(() => {
                document.addEventListener('click', function handler(ev) {
                    if (!dropdown.contains(ev.target) && ev.target !== filterBtn) {
                        dropdown.style.animation = 'dropdownFadeOut 0.15s ease-out';
                        setTimeout(() => {
                            dropdown.remove();
                            dropdown = null;
                        }, 150);
                        document.removeEventListener('click', handler);
                    }
                });
            }, 10);
        });
    }

    // Dropdown menu items
    document.addEventListener('click', function(e) {
        if (e.target.closest('a[href="#"]')) {
            e.preventDefault();
            const text = e.target.textContent.trim();
            if (text.includes('Download PDF')) {
                exportToPDF();
            } else if (text.includes('Share Report')) {
                if (navigator.share) {
                    navigator.share({
                        title: 'Payment Report',
                        text: 'Check out this payment report',
                        url: window.location.href
                    });
                } else {
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        alert('Report URL copied to clipboard!');
                    });
                }
            } else if (text.includes('Analytics')) {
                window.location.href = '{{ route("admin.payments.statistics") }}';
            } else if (text.includes('Settings')) {
                alert('Settings functionality coming soon!');
            }
        }
    });
    
    // PDF export function
    function exportToPDF() {
        try {
            // Create new jsPDF instance
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
            
            // Get current date
            const now = new Date();
            const dateStr = now.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            // Add header
            doc.setFontSize(20);
            doc.setTextColor(40, 40, 40);
            doc.text('Payment Report', 14, 20);
            
            doc.setFontSize(12);
            doc.setTextColor(100, 100, 100);
            doc.text(`Generated on: ${dateStr}`, 14, 30);
            
            // Add statistics section
            const stats = getStatsData();
            if (stats.length > 0) {
                doc.setFontSize(14);
                doc.setTextColor(40, 40, 40);
                doc.text('Summary Statistics', 14, 45);
                
                // Create stats table
                doc.autoTable({
                    startY: 50,
                    head: [['Metric', 'Value']],
                    body: stats,
                    theme: 'grid',
                    headStyles: { fillColor: [100, 116, 139] },
                    margin: { left: 14, right: 14 }
                });
            }
            
            // Get table data
            const table = document.querySelector('.table');
            if (table) {
                // Extract headers
                const headers = [];
                const headerCells = table.querySelectorAll('thead th');
                headerCells.forEach(cell => {
                    const text = cell.textContent.trim();
                    if (text && text !== 'Actions') {
                        headers.push(text);
                    }
                });
                
                // Extract data
                const data = [];
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const rowData = [];
                    const cells = row.querySelectorAll('td');
                    
                    cells.forEach((cell, index) => {
                        // Skip the actions column
                        if (index < cells.length - 1) {
                            let text = cell.textContent.trim();
                            
                            // Clean up status badges
                            if (cell.querySelector('.badge')) {
                                text = cell.querySelector('.badge').textContent.trim();
                            }
                            
                            // Clean up and format text
                            text = text.replace(/\s+/g, ' ').trim();
                            
                            rowData.push(text);
                        }
                    });
                    
                    if (rowData.length > 0) {
                        data.push(rowData);
                    }
                });
                
                // Add payments table
                const startY = stats.length > 0 ? doc.lastAutoTable.finalY + 20 : 55;
                
                doc.setFontSize(14);
                doc.setTextColor(40, 40, 40);
                doc.text('Payment Details', 14, startY - 5);
                
                doc.autoTable({
                    startY: startY,
                    head: [headers],
                    body: data,
                    theme: 'striped',
                    headStyles: { 
                        fillColor: [71, 85, 105],
                        textColor: [255, 255, 255],
                        fontStyle: 'bold'
                    },
                    alternateRowStyles: {
                        fillColor: [248, 250, 252]
                    },
                    styles: {
                        fontSize: 8,
                        cellPadding: 3,
                        lineColor: [200, 200, 200],
                        lineWidth: 0.1
                    },
                    columnStyles: {
                        0: { cellWidth: 15 }, // ID
                        1: { cellWidth: 35 }, // User
                        2: { cellWidth: 45 }, // Course
                        3: { cellWidth: 25 }, // Amount
                        4: { cellWidth: 30 }, // Payment Method
                        5: { cellWidth: 25 }, // Status
                        6: { cellWidth: 40 }, // Transaction ID
                        7: { cellWidth: 35 }  // Date
                    },
                    margin: { left: 14, right: 14 }
                });
            }
            
            // Add footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(10);
                doc.setTextColor(150, 150, 150);
                doc.text(`Page ${i} of ${pageCount}`, doc.internal.pageSize.getWidth() - 30, doc.internal.pageSize.getHeight() - 10);
                doc.text('Online Course Platform - Payment Management', 14, doc.internal.pageSize.getHeight() - 10);
            }
            
            // Generate filename and save
            const filename = `payments_report_${now.toISOString().split('T')[0]}.pdf`;
            doc.save(filename);
            
            // Show success toast
            showSuccessToast('PDF file downloaded successfully!', 'fas fa-file-pdf');
            
        } catch (error) {
            console.error('PDF export error:', error);
            showErrorToast('PDF export failed. Please try again.');
        }
    }
    
    // Helper function to get statistics data
    function getStatsData() {
        const stats = [];
        const statsCards = document.querySelectorAll('.stats-card');
        
        statsCards.forEach(card => {
            const label = card.querySelector('.stats-label');
            const value = card.querySelector('.stats-value');
            
            if (label && value) {
                stats.push([
                    label.textContent.trim(),
                    value.textContent.trim()
                ]);
            }
        });
        
        return stats;
    }
    
    // Helper function to show success toast
    function showSuccessToast(message, icon = 'fas fa-check-circle') {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            z-index: 10000;
            font-weight: 500;
            animation: slideInRight 0.3s ease-out;
        `;
        toast.innerHTML = `<i class="${icon} me-2"></i>${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Helper function to show error toast
    function showErrorToast(message) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            z-index: 10000;
            font-weight: 500;
            animation: slideInRight 0.3s ease-out;
        `;
        toast.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
});
</script>
@endpush