@extends('layouts.admin')

@section('title', 'Payment Statistics')

@section('header')
    <h1 class="h2">
        <i class="fa                    <div class="table-responsive">
                        <table class="table">fa-chart-line me-3"></i>
        Payment Statistics & Analytics
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Payments
            </a>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="exportStatistics()">
                <i class="fas fa-download me-1"></i>
                Export Report
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid" data-page-loaded="true">
    <!-- Custom Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Custom Date Range Filter
                    </h6>
                </div>
                <div class="card-body">
                    <form id="dateRangeForm" method="GET" action="{{ route('admin.payments.statistics') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label fw-semibold">
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
                                <label for="end_date" class="form-label fw-semibold">
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>
                                    Apply Filter
                                </button>
                                @if(request('start_date') || request('end_date'))
                                <a href="{{ route('admin.payments.statistics') }}" 
                                   class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i>
                                    Clear
                                </a>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @if(request('start_date') && request('end_date'))
                                <div class="alert alert-info mb-0 py-2">
                                    <small class="mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Filtering: {{ \Carbon\Carbon::parse(request('start_date'))->format('M j') }} - 
                                        {{ \Carbon\Carbon::parse(request('end_date'))->format('M j, Y') }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="period" value="custom">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-area me-2"></i>
                        Revenue Trend 
                        @if(request('start_date') && request('end_date'))
                            (Custom Range)
                        @else
                            ({{ ucfirst($period) }}ly)
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Courses -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header modern-grey-header text-white">
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
                                    <th>Rank</th>
                                    <th>Course Title</th>
                                    <th>Enrollments</th>
                                    <th>Revenue</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCourses as $index => $courseData)
                                <tr>
                                    <td>
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
                                    <td>
                                        <strong>{{ Str::limit($courseData->course->title, 50) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $courseData->enrollments }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ number_format($courseData->revenue, 2) }}</strong>
                                    </td>
                                    <td>
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
                <div class="card-header modern-grey-header text-white">
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
                                    <th>Rank</th>
                                    <th>Student</th>
                                    <th>Purchases</th>
                                    <th>Total Spent</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topUsers as $index => $userData)
                                <tr>
                                    <td>
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
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $userData->user->name }}</strong>
                                            <small class="text-muted">{{ $userData->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $userData->purchases }} courses</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ number_format($userData->total_spent, 2) }}</strong>
                                    </td>
                                    <td>
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

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.75rem;
}

/* Custom Date Range Styles */
.form-control:focus {
    border-color: #4e73df;
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
}

/* Custom Styles */
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($revenueStats);
    
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => {
                const period = "{{ $period }}";
                if (period === 'day' || (period === 'custom' && item.period < 24)) {
                    // For hourly data, show hour with AM/PM
                    const hour = parseInt(item.period);
                    return hour === 0 ? '12 AM' : 
                           hour < 12 ? hour + ' AM' : 
                           hour === 12 ? '12 PM' : 
                           (hour - 12) + ' PM';
                } else if (period === 'week') {
                    // Use the formatted day name from backend
                    return item.period;
                } else if (period === 'month' || period === 'custom' || period === 'all') {
                    // Use the formatted date from backend
                    return item.period;
                } else if (period === 'year') {
                    // Convert month number to name
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                                   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    return months[item.period - 1];
                } else {
                    return item.period;
                }
            }),
            datasets: [{
                label: 'Revenue ($)',
                data: revenueData.map(item => parseFloat(item.revenue) || 0),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                fill: true
            }, {
                label: 'Transactions',
                data: revenueData.map(item => parseInt(item.transactions) || 0),
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            animation: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Transactions'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'Revenue: $' + context.parsed.y.toLocaleString();
                            } else {
                                return 'Transactions: ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        }
    });
});

function exportStatistics() {
    // This would typically generate a comprehensive PDF or Excel report
    showSuccessNotification('Statistics export feature will be implemented soon!');
}
</script>
@endpush