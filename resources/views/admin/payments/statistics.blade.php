@extends('layouts.admin')

@section('title', 'Payment Statistics')

@section('header')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h2 mb-0 fw-bold">
            <i class="fas fa-chart-line me-2 text-primary"></i>
            Payment Statistics & Analytics
        </h1>
        <div class="btn-toolbar ms-4">
            <div class="btn-group">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Payments
                </a>
                <button type="button" 
                        class="btn btn-sm btn-outline-success {{ !request('start_date') && !request('end_date') && $period === 'all' ? 'export-disabled' : '' }}" 
                        onclick="exportStatistics()"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="bottom" 
                        title="{{ !request('start_date') && !request('end_date') && $period === 'all' ? 'Please apply a filter before exporting' : 'Export statistics as CSV' }}">
                    <i class="fas fa-download me-1"></i>
                    Export Report
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid" data-page-loaded="true">
    <!-- Combined: Date Range Filter & Revenue Chart -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-chart-area me-2"></i>
                            Revenue Trend 
                            @if(request('start_date') && request('end_date'))
                                (Custom Range)
                            @else
                                ({{ ucfirst($period) }}ly)
                            @endif
                        </h6>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Filter Available
                        </span>
                    </div>
                </div>
                <div class="card-body position-relative">
                    <!-- Active Filter Badge (Top Right) -->
                    @if(request('start_date') && request('end_date'))
                    <div class="active-filter-badge">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Active Filter:</strong> 
                        {{ \Carbon\Carbon::parse(request('start_date'))->format('M d, Y') }} 
                        to 
                        {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
                    </div>
                    @endif

                    <!-- Date Range Filter Form -->
                    <div class="filter-section mb-4 p-3 bg-light rounded">
                        <form id="dateRangeForm" method="GET" action="{{ route('admin.payments.statistics') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label fw-semibold small">
                                        <i class="fas fa-calendar-day me-1 text-primary"></i>
                                        Start Date
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ request('start_date') }}"
                                           max="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label fw-semibold small">
                                        <i class="fas fa-calendar-check me-1 text-success"></i>
                                        End Date
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ request('end_date') }}"
                                           max="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i>
                                        Apply Filter
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    @if(request('start_date') || request('end_date'))
                                    <a href="{{ route('admin.payments.statistics') }}" 
                                       class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-times me-1"></i>
                                        Clear Filter
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" name="period" value="custom">
                        </form>
                    </div>

                    <!-- Revenue Chart -->
                    <div class="chart-section">
                        <canvas id="revenueChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Courses -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white py-2">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-trophy me-2"></i>
                        Top Courses by Revenue 
                        @if(request('start_date') && request('end_date'))
                            (Custom Range)
                        @else
                            ({{ ucfirst($period) }})
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    @if($topCourses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Rank</th>
                                        <th class="text-center">Course Title</th>
                                        <th class="text-center">Enrollments</th>
                                        <th class="text-center">Revenue</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach($topCourses as $index => $courseData)
                                <tr>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-crown me-1"></i>
                                                #1
                                            </span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-medal me-1"></i>
                                                #2
                                            </span>
                                        @elseif($index == 2)
                                            <span class="badge bg-dark">
                                                <i class="fas fa-award me-1"></i>
                                                #3
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">
                                                #{{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ Str::limit($courseData->course->title, 50) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $courseData->enrollments }}</span>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-success">RM{{ number_format($courseData->revenue, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.courses.show', $courseData->course) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye me-1"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-600">No course sales data</h5>
                        <p class="text-muted">No courses have been sold in the selected period.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white py-2">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-users me-2"></i>
                        Top Customers by Spending 
                        @if(request('start_date') && request('end_date'))
                            (Custom Range)
                        @else
                            ({{ ucfirst($period) }})
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    @if($topUsers->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Rank</th>
                                        <th class="text-center">Student</th>
                                        <th class="text-center">Purchases</th>
                                        <th class="text-center">Total Spent</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach($topUsers as $index => $userData)
                                <tr>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-crown me-1"></i>
                                                #1
                                            </span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-medal me-1"></i>
                                                #2
                                            </span>
                                        @elseif($index == 2)
                                            <span class="badge bg-dark">
                                                <i class="fas fa-award me-1"></i>
                                                #3
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">
                                                #{{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <strong>{{ $userData->user->name }}</strong>
                                            <small class="text-muted">{{ $userData->user->email }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $userData->purchases }} courses</span>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-success">RM{{ number_format($userData->total_spent, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.clients.show', $userData->user) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-user me-1"></i>
                                            View Profile
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-600">No customer data</h5>
                        <p class="text-muted">No customers have made purchases in the selected period.</p>
                    </div>
                    @endif
                </div>
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

/* Export button disabled state */
.export-disabled {
    opacity: 0.6;
    cursor: not-allowed !important;
    position: relative;
}

.export-disabled:hover {
    opacity: 0.7;
}

.export-disabled::after {
    content: '⚠';
    position: absolute;
    top: -8px;
    right: -8px;
    background: #f59e0b;
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Optimize spacing and layout */
.container-fluid {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Fix excessive spacing after filtering */
.main-content {
    padding-top: 0 !important;
}

.main-content .navbar {
    margin-bottom: 0.5rem !important;
}

.main-content .border-bottom {
    margin-bottom: 0.5rem !important;
    padding-bottom: 0.5rem !important;
    padding-top: 0.5rem !important;
}

/* Remove extra spacing from page sections */
.main-content > .container-fluid {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Ensure no extra margins on flash messages */
.alert:not(.position-fixed) {
    margin-bottom: 0.75rem !important;
    margin-top: 0 !important;
}

/* Fix potential spacing from header section */
.d-flex.justify-content-between.flex-wrap {
    margin-bottom: 0.5rem !important;
    padding-bottom: 0.5rem !important;
    padding-top: 0.5rem !important;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.75rem;
    margin-bottom: 0;
}

.card-header {
    padding: 0.75rem 1rem;
}

.card-body {
    padding: 1rem;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
    padding: 0.5rem 1rem;
}

/* Center table headers */
th.text-center {
    vertical-align: middle !important;
    text-align: center !important;
}

/* Reduce excessive margins */
.row {
    margin-bottom: 0;
}

.mb-3 {
    margin-bottom: 0.75rem !important;
}

.mb-2 {
    margin-bottom: 0.5rem !important;
}

/* Prevent excessive spacing after page reload/filter */
body {
    padding-top: 0 !important;
}

.container-fluid .row:first-child {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Ensure consistent spacing */
@media (min-width: 768px) {
    .main-content {
        padding-top: 0 !important;
    }
}

/* Optimize chart container */
#revenueChart {
    max-height: 400px;
}

/* Filter Section Styling */
.filter-section {
    border: 1px dashed #dee2e6;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
}

.filter-section .form-label {
    color: #495057;
    font-weight: 600;
}

.filter-section .form-control {
    border: 2px solid #dee2e6;
    transition: all 0.3s;
}

.filter-section .form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    background-color: #fff;
}

.chart-section {
    background: #fff;
    padding: 1rem;
    border-radius: 0.5rem;
}

/* Fix table spacing */
.table-responsive {
    margin-bottom: 0;
}

.table {
    margin-bottom: 0;
}

/* Page header adjustments */
.main-content .border-bottom {
    margin-bottom: 1rem !important;
    padding-bottom: 1rem !important;
}

/* Form control improvements */
.form-label {
    margin-bottom: 0.25rem;
    font-weight: 500;
}

/* Button spacing */
.btn-toolbar .btn-group {
    gap: 0.25rem;
}

/* Alert spacing optimization */
.alert {
    margin-bottom: 0.5rem;
}

/* Fix date range form spacing */
#dateRangeForm .row {
    margin: 0;
}

#dateRangeForm .col-md-3 {
    padding: 0 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .btn-toolbar {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Date Range Form Validation
    const dateRangeForm = document.getElementById('dateRangeForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (dateRangeForm) {
        // Update end date minimum when start date changes
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });
        
        // Update start date maximum when end date changes
        endDateInput.addEventListener('change', function() {
            startDateInput.max = this.value;
        });
        
        // Form validation on submit
        dateRangeForm.addEventListener('submit', function(e) {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            if (startDate && endDate) {
                if (new Date(startDate) > new Date(endDate)) {
                    e.preventDefault();
                    alert('Start date cannot be later than end date.');
                    return false;
                }
            }
            
            if (!startDate && !endDate) {
                e.preventDefault();
                alert('Please select at least a start date or end date.');
                return false;
            }
        });
    }

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueData = @json($revenueStats);
        const period = "{{ $period }}";
        const hasCustomRange = {{ request('start_date') && request('end_date') ? 'true' : 'false' }};
        
        // Debug log to check data
        console.log('Revenue Data:', revenueData);
        console.log('Period:', period);
        console.log('Has Custom Range:', hasCustomRange);
        
        // If no data, show message
        if (!revenueData || revenueData.length === 0) {
            revenueCtx.parentElement.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Revenue Data</h5>
                    <p class="text-muted">No payment data found for the selected period.</p>
                </div>
            `;
            return;
        }
        
        // Process chart labels based on period and data
        const chartLabels = revenueData.map(item => {
            if (hasCustomRange || period === 'custom') {
                // For custom date ranges, use the period as provided by backend
                return item.period;
            } else if (period === 'day') {
                // For hourly data, show hour with AM/PM
                const hour = parseInt(item.period);
                return hour === 0 ? '12 AM' : 
                       hour < 12 ? hour + ' AM' : 
                       hour === 12 ? '12 PM' : 
                       (hour - 12) + ' PM';
            } else if (period === 'week') {
                // Use the formatted day name from backend
                return item.period;
            } else if (period === 'month' || period === 'all') {
                // Use the formatted date from backend
                return item.period;
            } else if (period === 'year') {
                // Convert month number to name
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                               'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return months[item.period - 1] || item.period;
            } else {
                return item.period;
            }
        });
        
        // Create the chart
        const revenueChart = new Chart(revenueCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: revenueData.map(item => parseFloat(item.revenue) || 0),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }, {
                    label: 'Transactions',
                    data: revenueData.map(item => parseInt(item.transactions) || 0),
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#1cc88a',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#4e73df',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                return 'Period: ' + context[0].label;
                            },
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return 'Revenue: $' + context.parsed.y.toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                } else {
                                    return 'Transactions: ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: hasCustomRange ? 'Custom Date Range' : 'Time Period',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue ($)',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Transactions',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Add data summary below chart
        const totalRevenue = revenueData.reduce((sum, item) => sum + parseFloat(item.revenue || 0), 0);
        const totalTransactions = revenueData.reduce((sum, item) => sum + parseInt(item.transactions || 0), 0);
        const avgTransactionValue = totalTransactions > 0 ? totalRevenue / totalTransactions : 0;
        
        const summaryHtml = `
            <div class="row mt-3 pt-3 border-top">
                <div class="col-md-4 text-center">
                    <div class="metric-card">
                        <h5 class="text-primary mb-1">$${totalRevenue.toLocaleString('en-US', {minimumFractionDigits: 2})}</h5>
                        <small class="text-muted">Total Revenue</small>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="metric-card">
                        <h5 class="text-success mb-1">${totalTransactions.toLocaleString()}</h5>
                        <small class="text-muted">Total Transactions</small>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="metric-card">
                        <h5 class="text-info mb-1">$${avgTransactionValue.toLocaleString('en-US', {minimumFractionDigits: 2})}</h5>
                        <small class="text-muted">Avg. Transaction Value</small>
                    </div>
                </div>
            </div>
        `;
        
        revenueCtx.parentElement.insertAdjacentHTML('beforeend', summaryHtml);
    }
});

function exportStatistics() {
    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    // Get current filter parameters
    const urlParams = new URLSearchParams(window.location.search);
    const period = urlParams.get('period') || '{{ $period }}';
    const startDate = urlParams.get('start_date') || '{{ request("start_date") }}';
    const endDate = urlParams.get('end_date') || '{{ request("end_date") }}';
    
    // Check if any filter is applied (not 'all' period and no custom date range)
    const hasCustomDateRange = startDate && endDate;
    const hasPeriodFilter = period && period !== 'all';
    
    if (!hasCustomDateRange && !hasPeriodFilter) {
        // Show warning message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 350px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2" style="font-size: 20px;"></i>
                <div>
                    <strong>Filter Required!</strong>
                    <p class="mb-0 mt-1" style="font-size: 13px;">Please select a time period or date range before exporting statistics.</p>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 6 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 6000);
        
        // Scroll to filter section
        document.getElementById('dateRangeForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        return; // Exit function without exporting
    }
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Preparing Export...';
    btn.disabled = true;
    
    // Build export URL
    let exportUrl = '{{ route("admin.payments.statistics.export") }}';
    const params = new URLSearchParams();
    
    if (period) params.append('period', period);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    
    if (params.toString()) {
        exportUrl += '?' + params.toString();
    }
    
    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'payment-statistics.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Reset button after a short delay
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            <strong>Export Successful!</strong> Your statistics report has been downloaded.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }, 1000);
}
</script>
@endpush