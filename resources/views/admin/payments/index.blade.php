@extends('layouts.admin')

@section('title', 'Payment Management')

@section('header')
    <h1 class="h2">
        <i class="fas fa-credit-card me-3"></i>
        Payment Management
        <span class="badge bg-secondary ms-2" id="lastUpdate">Live</span>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.payments.statistics') }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-chart-line me-1"></i>
                View Statistics
            </a>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="exportPayments()">
                <i class="fas fa-download me-1"></i>
                Export Data
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshData()" id="refreshBtn">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh
            </button>
        </div>
        <div class="form-check form-switch ms-2">
            <input class="form-check-input" type="checkbox" id="autoRefreshToggle" checked>
            <label class="form-check-label" for="autoRefreshToggle">
                Auto-refresh
            </label>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid" data-page-loaded="true">
    <!-- Statistics Cards -->
    <div class="row mb-4" id="statisticsCards">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalRevenue">
                                ${{ number_format($stats['total_revenue'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPayments">
                                {{ number_format($stats['total_payments']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingPayments">
                                {{ number_format($stats['pending_payments']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Average Order Value
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="averageOrderValue">
                                ${{ number_format($stats['average_order_value'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Growth Card -->
    <div class="row mb-4" id="revenueGrowthCards">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-trending-up me-2"></i>
                        Monthly Revenue Growth
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-lg font-weight-bold" id="thisMonthRevenue">
                                ${{ number_format($stats['this_month_revenue'], 2) }}
                            </div>
                            <div class="text-sm text-muted">This month's revenue</div>
                        </div>
                        <div class="col-auto">
                            <span class="badge" id="revenueGrowthBadge">
                                @if($stats['revenue_growth'] >= 0)
                                    <span class="bg-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        {{ number_format($stats['revenue_growth'], 1) }}%
                                    </span>
                                @else
                                    <span class="bg-danger">
                                        <i class="fas fa-arrow-down me-1"></i>
                                        {{ number_format(abs($stats['revenue_growth']), 1) }}%
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Failed Payments
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-lg font-weight-bold text-danger" id="failedPayments">
                                {{ number_format($stats['failed_payments']) }}
                            </div>
                            <div class="text-sm text-muted">Require attention</div>
                        </div>
                        <div class="col-auto" id="failedPaymentsAction">
                            @if($stats['failed_payments'] > 0)
                                <a href="{{ route('admin.payments.index', ['payment_status' => 'failed']) }}" 
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-eye me-1"></i>
                                    Review
                                </a>
                            @else
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>
                                    All Good
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header modern-grey-header text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-filter me-2"></i>
                Filter Payments
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Student name, email, or course title">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="amount_min" class="form-label">Min Amount</label>
                        <input type="number" 
                               class="form-control" 
                               id="amount_min" 
                               name="amount_min" 
                               value="{{ request('amount_min') }}" 
                               step="0.01"
                               placeholder="0.00">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="amount_max" class="form-label">Max Amount</label>
                        <input type="number" 
                               class="form-control" 
                               id="amount_max" 
                               name="amount_max" 
                               value="{{ request('amount_max') }}" 
                               step="0.01"
                               placeholder="1000.00">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card shadow" id="paymentsTableCard">
        <div class="card-header modern-grey-header text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-list me-2"></i>
                Payments List (<span id="paymentsCount">{{ $payments->total() }}</span> total)
            </h6>
            <div class="d-flex align-items-center">
                <small class="text-white-50 me-2">Last updated: <span id="lastUpdateTime">{{ now()->format('H:i:s') }}</span></small>
                <div class="spinner-border spinner-border-sm text-light d-none" id="tableLoadingSpinner"></div>
            </div>
        </div>
        <div class="card-body p-0" id="paymentsTableContainer">
            <div id="paymentsTable">
                @include('admin.payments.partials.payments-table', ['payments' => $payments])
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modern Grey Card Header */
.modern-grey-header {
    background: linear-gradient(135deg, #64748b 0%, #475569 50%, #334155 100%);
    border-bottom: 3px solid #1e293b;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
    transition: background-color 0.2s ease;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge {
    font-size: 0.75em;
}

/* Statistics Cards Animation */
.stats-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Real-time update indicators */
.updating {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.updated {
    animation: highlightUpdate 0.5s ease;
}

@keyframes highlightUpdate {
    0% { background-color: #d4edda; }
    100% { background-color: transparent; }
}

/* Auto-refresh indicator */
#lastUpdate {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

/* Loading states */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 0.35rem;
}

/* Enhanced pagination */
.pagination {
    margin: 0;
}

.pagination .page-link {
    border: 1px solid #dee2e6;
    color: #6c757d;
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    color: #495057;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background-color: #4e73df;
    border-color: #4e73df;
    box-shadow: 0 2px 4px rgba(78, 115, 223, 0.3);
}

/* Filter form enhancements */
.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Refresh button animation */
.refreshing {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* New payment row highlight */
.payment-row.new-payment {
    background-color: #d1ecf1 !important;
    animation: newPaymentHighlight 3s ease;
}

@keyframes newPaymentHighlight {
    0% { background-color: #d1ecf1; }
    100% { background-color: transparent; }
}

/* Status badges with better spacing */
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}

/* Card footer improvements */
.card-footer {
    background-color: #f8f9fc;
    border-top: 1px solid #e3e6f0;
    padding: 1rem 1.25rem;
}

/* Form switch for auto-refresh */
.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.form-check-label {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Empty state improvements */
.text-gray-300 {
    color: #d1d3e2 !important;
}

.text-gray-600 {
    color: #858796 !important;
}

/* Table row transitions */
.payment-row {
    transition: all 0.2s ease;
}

.payment-row:hover {
    transform: scale(1.002);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time update configuration
    let autoRefreshInterval;
    let isAutoRefreshEnabled = true;
    let currentPage = {{ $payments->currentPage() }};
    const refreshInterval = 30000; // 30 seconds
    
    // Initialize real-time updates
    initializeRealTimeUpdates();
    
    // Auto-submit form when filters change
    const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input[type="date"]');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.type === 'select-one' || this.type === 'date') {
                showTableLoading();
                setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 300);
            }
        });
    });
    
    // Auto-refresh toggle
    const autoRefreshToggle = document.getElementById('autoRefreshToggle');
    autoRefreshToggle.addEventListener('change', function() {
        isAutoRefreshEnabled = this.checked;
        if (isAutoRefreshEnabled) {
            startAutoRefresh();
            updateLastUpdateBadge('Live');
        } else {
            stopAutoRefresh();
            updateLastUpdateBadge('Paused');
        }
    });
    
    // Manual refresh button
    const refreshBtn = document.getElementById('refreshBtn');
    refreshBtn.addEventListener('click', function() {
        refreshData();
    });
    
    // Initialize auto-refresh
    function initializeRealTimeUpdates() {
        if (isAutoRefreshEnabled) {
            startAutoRefresh();
        }
        
        // Update timestamp every second
        setInterval(updateCurrentTime, 1000);
    }
    
    // Start auto-refresh
    function startAutoRefresh() {
        stopAutoRefresh(); // Clear any existing interval
        
        autoRefreshInterval = setInterval(() => {
            if (isAutoRefreshEnabled && document.visibilityState === 'visible') {
                refreshData(true); // Silent refresh
            }
        }, refreshInterval);
    }
    
    // Stop auto-refresh
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
        }
    }
    
    // Refresh all data
    function refreshData(silent = false) {
        if (!silent) {
            showRefreshAnimation();
        }
        
        // Refresh statistics
        refreshStatistics(silent);
        
        // Refresh payments list
        refreshPaymentsList(silent);
    }
    
    // Refresh statistics
    function refreshStatistics(silent = false) {
        const currentFilters = getFilterParams();
        
        performAjaxRequest("{{ route('admin.payments.realtime-stats') }}", {
            method: 'GET',
            body: new URLSearchParams(currentFilters).toString(),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(data => {
            if (data.success) {
                updateStatisticsCards(data.stats, silent);
                if (!silent) {
                    updateLastUpdateBadge('Updated');
                    updateLastUpdateTime(data.timestamp);
                }
            }
        })
        .catch(error => {
            console.error('Failed to refresh statistics:', error);
            if (!silent) {
                showErrorNotification('Failed to refresh statistics');
            }
        });
    }
    
    // Refresh payments list
    function refreshPaymentsList(silent = false) {
        const currentFilters = getFilterParams();
        currentFilters.page = currentPage;
        
        if (!silent) {
            showTableLoading();
        }
        
        performAjaxRequest("{{ route('admin.payments.list-ajax') }}", {
            method: 'GET',
            body: new URLSearchParams(currentFilters).toString(),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(data => {
            if (data.success) {
                updatePaymentsTable(data, silent);
                if (!silent) {
                    hideTableLoading();
                }
            }
        })
        .catch(error => {
            console.error('Failed to refresh payments list:', error);
            hideTableLoading();
            if (!silent) {
                showErrorNotification('Failed to refresh payments list');
            }
        });
    }
    
    // Update statistics cards
    function updateStatisticsCards(stats, silent = false) {
        const updates = [
            { id: 'totalRevenue', value: '$' + numberFormat(stats.total_revenue, 2) },
            { id: 'totalPayments', value: numberFormat(stats.total_payments) },
            { id: 'pendingPayments', value: numberFormat(stats.pending_payments) },
            { id: 'averageOrderValue', value: '$' + numberFormat(stats.average_order_value, 2) },
            { id: 'thisMonthRevenue', value: '$' + numberFormat(stats.this_month_revenue, 2) },
            { id: 'failedPayments', value: numberFormat(stats.failed_payments) }
        ];
        
        updates.forEach(update => {
            const element = document.getElementById(update.id);
            if (element && element.textContent.trim() !== update.value) {
                element.textContent = update.value;
                if (!silent) {
                    element.classList.add('updated');
                    setTimeout(() => element.classList.remove('updated'), 500);
                }
            }
        });
        
        // Update revenue growth badge
        updateRevenueGrowthBadge(stats.revenue_growth);
        
        // Update failed payments action
        updateFailedPaymentsAction(stats.failed_payments);
    }
    
    // Update payments table
    function updatePaymentsTable(data, silent = false) {
        const tableContainer = document.getElementById('paymentsTable');
        const currentRows = Array.from(document.querySelectorAll('.payment-row')).map(row => 
            row.dataset.paymentId
        );
        
        // Update payments count
        const paymentsCount = document.getElementById('paymentsCount');
        if (paymentsCount) {
            paymentsCount.textContent = data.pagination.total;
        }
        
        // Update table content (This would require server-side rendering)
        // For now, we'll just update the timestamp to show it's working
        if (!silent) {
            updateLastUpdateTime(data.timestamp);
        }
        
        // Check for new payments
        const newRows = data.payments.filter(payment => 
            !currentRows.includes(payment.id.toString())
        );
        
        if (newRows.length > 0 && !silent) {
            showSuccessNotification(`${newRows.length} new payment(s) received!`);
        }
    }
    
    // Helper functions
    function getFilterParams() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = {};
        for (let [key, value] of formData.entries()) {
            if (value) params[key] = value;
        }
        return params;
    }
    
    function showRefreshAnimation() {
        const refreshBtn = document.getElementById('refreshBtn');
        const icon = refreshBtn.querySelector('i');
        icon.classList.add('refreshing');
        setTimeout(() => icon.classList.remove('refreshing'), 1000);
    }
    
    function showTableLoading() {
        const spinner = document.getElementById('tableLoadingSpinner');
        const tableContainer = document.getElementById('paymentsTableContainer');
        if (spinner) spinner.classList.remove('d-none');
        if (tableContainer) tableContainer.classList.add('updating');
    }
    
    function hideTableLoading() {
        const spinner = document.getElementById('tableLoadingSpinner');
        const tableContainer = document.getElementById('paymentsTableContainer');
        if (spinner) spinner.classList.add('d-none');
        if (tableContainer) tableContainer.classList.remove('updating');
    }
    
    function updateLastUpdateBadge(text) {
        const badge = document.getElementById('lastUpdate');
        if (badge) {
            badge.textContent = text;
            badge.classList.add('updated');
            setTimeout(() => badge.classList.remove('updated'), 500);
        }
    }
    
    function updateLastUpdateTime(time) {
        const timeElement = document.getElementById('lastUpdateTime');
        if (timeElement) {
            timeElement.textContent = time;
        }
    }
    
    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: false, 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
        
        // Update current time in any time displays
        const currentTimeElements = document.querySelectorAll('.current-time');
        currentTimeElements.forEach(element => {
            element.textContent = timeString;
        });
    }
    
    function updateRevenueGrowthBadge(growth) {
        const badge = document.getElementById('revenueGrowthBadge');
        if (badge) {
            const isPositive = growth >= 0;
            const badgeClass = isPositive ? 'bg-success' : 'bg-danger';
            const icon = isPositive ? 'fa-arrow-up' : 'fa-arrow-down';
            const value = Math.abs(growth).toFixed(1);
            
            badge.innerHTML = `
                <span class="${badgeClass}">
                    <i class="fas ${icon} me-1"></i>
                    ${value}%
                </span>
            `;
        }
    }
    
    function updateFailedPaymentsAction(failedCount) {
        const actionDiv = document.getElementById('failedPaymentsAction');
        if (actionDiv) {
            if (failedCount > 0) {
                actionDiv.innerHTML = `
                    <a href="{{ route('admin.payments.index') }}?payment_status=failed" 
                       class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-eye me-1"></i>
                        Review
                    </a>
                `;
            } else {
                actionDiv.innerHTML = `
                    <span class="badge bg-success">
                        <i class="fas fa-check me-1"></i>
                        All Good
                    </span>
                `;
            }
        }
    }
    
    function numberFormat(number, decimals = 0) {
        return new Intl.NumberFormat('en-US', { 
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals 
        }).format(number);
    }
    
    // Pause auto-refresh when page is not visible
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible' && isAutoRefreshEnabled) {
            // Resume auto-refresh and immediately refresh data
            startAutoRefresh();
            refreshData(true);
        }
    });
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
});

// Export payments function
function exportPayments() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    const exportUrl = "{{ route('admin.payments.export') }}" + '?' + params.toString();
    
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'payments-export.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showSuccessNotification('Payment data export started!');
}
</script>
@endpush