@if($payments->count() > 0)
<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Payment ID</th>
                <th>Student</th>
                <th>Course</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr class="payment-row" data-payment-id="{{ $payment->id }}">
                <td>
                    <strong>#{{ $payment->id }}</strong>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <strong>{{ $payment->user->name }}</strong>
                        <small class="text-muted">{{ $payment->user->email }}</small>
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <strong>{{ Str::limit($payment->course->title, 30) }}</strong>
                        <small class="text-muted">
                            by {{ $payment->course->instructor->name }}
                        </small>
                    </div>
                </td>
                <td>
                    <strong class="text-success">
                        ${{ number_format($payment->amount_paid, 2) }}
                    </strong>
                </td>
                <td>
                    @if($payment->payment_status == 'completed')
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>
                            Completed
                        </span>
                    @elseif($payment->payment_status == 'pending')
                        <span class="badge bg-warning">
                            <i class="fas fa-clock me-1"></i>
                            Pending
                        </span>
                    @else
                        <span class="badge bg-danger">
                            <i class="fas fa-times me-1"></i>
                            Failed
                        </span>
                    @endif
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <strong>{{ $payment->enrolled_at->format('M d, Y') }}</strong>
                        <small class="text-muted">{{ $payment->enrolled_at->format('H:i') }}</small>
                    </div>
                </td>
                <td>
                    <a href="{{ route('admin.payments.show', $payment) }}" 
                       class="btn btn-sm btn-outline-info" title="View Payment Details">
                        <i class="fas fa-eye me-1"></i>
                        View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Enhanced Pagination -->
<div class="card-footer bg-light border-top">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Showing <strong>{{ $payments->firstItem() ?? 0 }}</strong> to <strong>{{ $payments->lastItem() ?? 0 }}</strong> 
                of <strong>{{ $payments->total() }}</strong> payments
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                @if($payments->hasPages())
                    <nav aria-label="Payments pagination">
                        {{ $payments->appends(request()->query())->onEachSide(2)->links('pagination::bootstrap-4') }}
                    </nav>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <div class="mb-4">
        <i class="fas fa-search fa-4x text-gray-300 mb-3"></i>
        <h4 class="text-gray-600">No payments found</h4>
        <p class="text-muted lead">
            @if(request()->hasAny(['search', 'payment_status', 'date_from', 'date_to', 'amount_min', 'amount_max']))
                Try adjusting your search criteria or filters.
            @else
                No course purchases have been made yet.
            @endif
        </p>
    </div>
    
    @if(request()->hasAny(['search', 'payment_status', 'date_from', 'date_to', 'amount_min', 'amount_max']))
    <div class="mt-3">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-times me-2"></i>
            Clear All Filters
        </a>
    </div>
    @endif
</div>
@endif