@if($payments->count() > 0)
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0 table-sm">
        <thead>
            <tr>
                <th class="py-2 px-3 text-center" style="width: 8%;">ID</th>
                <th class="py-2 px-3" style="width: 20%;">Student</th>
                <th class="py-2 px-3" style="width: 25%;">Course</th>
                <th class="py-2 px-3 text-center" style="width: 12%;">Amount</th>
                <th class="py-2 px-3 text-center" style="width: 10%;">Status</th>
                <th class="py-2 px-3 text-center" style="width: 12%;">Date</th>
                <th class="py-2 px-3 text-center" style="width: 13%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr class="payment-row border-bottom" data-payment-id="{{ $payment->id }}">
                <td class="py-2 px-3 text-center">
                    <div class="fw-bold text-primary">#{{ $payment->id }}</div>
                </td>
                <td class="py-2 px-3">
                    <div>
                        <div class="fw-semibold text-dark small">{{ Str::limit($payment->user->name, 20) }}</div>
                        <small class="text-muted">{{ Str::limit($payment->user->email, 25) }}</small>
                    </div>
                </td>
                <td class="py-2 px-3">
                    <div>
                        <div class="fw-semibold text-dark small">{{ Str::limit($payment->course->title, 30) }}</div>
                        <small class="text-muted">{{ Str::limit($payment->course->instructor->name, 20) }}</small>
                    </div>
                </td>
                <td class="py-2 px-3 text-center">
                    <div class="fw-bold text-success">RM{{ number_format($payment->amount_paid, 0) }}</div>
                </td>
                <td class="py-2 px-3 text-center">
                    @if($payment->payment_status == 'completed')
                        <span class="badge bg-success text-white px-2 py-1 small">
                            <i class="fas fa-check fa-xs me-1"></i>Done
                        </span>
                    @elseif($payment->payment_status == 'pending')
                        <span class="badge bg-warning text-dark px-2 py-1 small">
                            <i class="fas fa-clock fa-xs me-1"></i>Wait
                        </span>
                    @else
                        <span class="badge bg-danger text-white px-2 py-1 small">
                            <i class="fas fa-times fa-xs me-1"></i>Fail
                        </span>
                    @endif
                </td>
                <td class="py-2 px-3 text-center">
                    <div>
                        <div class="fw-semibold text-dark small">{{ $payment->enrolled_at->format('M d') }}</div>
                        <small class="text-muted">{{ $payment->enrolled_at->format('Y') }}</small>
                    </div>
                </td>
                <td class="py-2 px-3 text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <!-- View Button -->
                        <a href="{{ route('admin.payments.show', $payment) }}" 
                           class="btn btn-sm btn-outline-primary compact-btn" 
                           title="View">
                            <i class="fas fa-eye fa-xs"></i>
                        </a>
                        
                        <!-- Download Button -->
                        <button type="button" 
                                class="btn btn-sm btn-outline-success compact-btn" 
                                title="Download">
                            <i class="fas fa-download fa-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Enhanced Pagination Footer -->
<div class="card-footer bg-light border-0">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center text-muted">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                <span>
                    Showing <span class="fw-semibold text-dark">{{ $payments->firstItem() ?? 0 }}</span> to 
                    <span class="fw-semibold text-dark">{{ $payments->lastItem() ?? 0 }}</span> of 
                    <span class="fw-semibold text-dark">{{ $payments->total() }}</span> payments
                </span>
            </div>
        </div>
        <div class="col-md-6">
            @if($payments->hasPages())
                <nav aria-label="Payments pagination" class="d-flex justify-content-end">
                    <div class="pagination-wrapper">
                        {{ $payments->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </nav>
            @endif
        </div>
    </div>
</div>
@else
<!-- Enhanced Empty State -->
<div class="text-center py-5 my-5">
    <div class="empty-state-icon bg-light rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
        <i class="fas fa-search fa-2x text-muted"></i>
    </div>
    <h4 class="text-dark mb-2">No payments found</h4>
    <p class="text-muted lead mb-4">
        @if(request()->hasAny(['search', 'payment_status', 'date_from', 'date_to', 'amount_min', 'amount_max']))
            We couldn't find any payments matching your current filters.<br>
            Try adjusting your search criteria to see more results.
        @else
            No course purchases have been made yet.<br>
            Payments will appear here once students start enrolling in courses.
        @endif
    </p>
    
    @if(request()->hasAny(['search', 'payment_status', 'date_from', 'date_to', 'amount_min', 'amount_max']))
    <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-primary">
            <i class="fas fa-times me-2"></i>
            Clear All Filters
        </a>
        <button onclick="window.history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Go Back
        </button>
    </div>
    @else
    <div class="mt-4">
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-graduation-cap me-2"></i>
            Manage Courses
        </a>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-info">
            <i class="fas fa-users me-2"></i>
            View Students
        </a>
    </div>
    @endif
</div>
@endif