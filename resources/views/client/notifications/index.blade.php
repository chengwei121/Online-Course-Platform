@extends('layouts.client')

@section('title', 'Notifications')

@section('content')
<div class="container my-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-bell text-primary me-2"></i>
                Notifications
            </h1>
            <p class="text-muted">Stay updated with your course activities and announcements</p>
        </div>
        <div class="col-md-4 text-end">
            @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('client.notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-check-double me-1"></i>
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('client.notifications.index') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        @foreach($notificationTypes as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('client.notifications.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div class="card mb-3 notification-item {{ !$notification->is_read ? 'border-primary' : '' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="notification-icon me-3">
                                    <div class="bg-light rounded-circle p-2" style="width: 48px; height: 48px;">
                                        <i class="{{ $notification->icon }} {{ $notification->color }} d-flex align-items-center justify-content-center h-100"></i>
                                    </div>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                                            {{ $notification->title }}
                                            @if(!$notification->is_read)
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endif
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted me-3">{{ $notification->time_ago }}</small>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if(!$notification->is_read)
                                                        <li>
                                                            <form action="{{ route('client.notifications.read', $notification) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-check me-2"></i>Mark as Read
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($notification->action_url)
                                                        <li>
                                                            <a href="{{ $notification->action_url }}" class="dropdown-item">
                                                                <i class="fas fa-external-link-alt me-2"></i>View Details
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('client.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="mb-2">{{ $notification->message }}</p>
                                    
                                    @if($notification->sender)
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            From: {{ $notification->sender->name }}
                                        </small>
                                    @endif
                                    
                                    @if($notification->priority === 'high')
                                        <span class="badge bg-danger ms-2">High Priority</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $notifications->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Notifications Found</h4>
                    <p class="text-muted">You don't have any notifications yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.notification-item {
    transition: all 0.3s ease;
}
.notification-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.notification-icon {
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-refresh notification count
setInterval(function() {
    fetch('{{ route("client.notifications.count") }}')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'inline' : 'none';
            }
        })
        .catch(error => console.log('Error fetching notification count:', error));
}, 30000); // Check every 30 seconds
</script>
@endpush
@endsection