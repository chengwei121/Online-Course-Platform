<!-- Notification Dropdown -->
<div class="nav-item dropdown">
    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="display: none;">
            0
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 400px;">
        <div class="dropdown-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Notifications</h6>
            <button class="btn btn-sm btn-link text-primary mark-all-read" style="display: none;">
                Mark all as read
            </button>
        </div>
        <div class="dropdown-divider"></div>
        <div class="notification-list">
            <div class="text-center py-3 no-notifications">
                <i class="fas fa-bell-slash text-muted mb-2"></i>
                <p class="text-muted mb-0">No new notifications</p>
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-center" href="{{ route('client.notifications.index') }}">
            <small>View all notifications</small>
        </a>
    </div>
</div>

@push('styles')
<style>
.notification-dropdown {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.notification-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f8f9fa;
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background-color: #e3f2fd;
    border-left: 3px solid #2196f3;
}

.notification-badge {
    font-size: 0.65em;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.querySelector('.notification-badge');
    const notificationList = document.querySelector('.notification-list');
    const noNotifications = document.querySelector('.no-notifications');
    const markAllReadBtn = document.querySelector('.mark-all-read');

    // Load notifications when dropdown is clicked
    notificationDropdown.addEventListener('click', function() {
        loadNotifications();
    });

    // Mark all as read functionality
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }

    function loadNotifications() {
        fetch('{{ route("client.notifications.get") }}')
            .then(response => response.json())
            .then(data => {
                updateNotificationBadge(data.unread_count);
                displayNotifications(data.notifications);
            })
            .catch(error => console.error('Error loading notifications:', error));
    }

    function updateNotificationBadge(count) {
        if (notificationBadge) {
            notificationBadge.textContent = count;
            notificationBadge.style.display = count > 0 ? 'inline' : 'none';
        }
        
        if (markAllReadBtn) {
            markAllReadBtn.style.display = count > 0 ? 'inline' : 'none';
        }
    }

    function displayNotifications(notifications) {
        if (!notificationList) return;

        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="text-center py-3">
                    <i class="fas fa-bell-slash text-muted mb-2"></i>
                    <p class="text-muted mb-0">No new notifications</p>
                </div>
            `;
            return;
        }

        notificationList.innerHTML = notifications.map(notification => `
            <div class="notification-item ${notification.is_read ? '' : 'unread'}" data-id="${notification.id}">
                <div class="d-flex align-items-start">
                    <div class="me-2">
                        <i class="${notification.icon} ${notification.color}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 ${notification.is_read ? '' : 'fw-bold'}">${notification.title}</h6>
                        <p class="mb-1 small">${notification.message}</p>
                        <small class="text-muted">${notification.time_ago}</small>
                    </div>
                    ${!notification.is_read ? '<div class="ms-2"><span class="badge bg-primary">New</span></div>' : ''}
                </div>
                ${notification.action_url ? `
                    <div class="mt-2">
                        <a href="${notification.action_url}" class="btn btn-sm btn-outline-primary" onclick="markAsRead(${notification.id})">
                            View Details
                        </a>
                    </div>
                ` : ''}
            </div>
        `).join('');
    }

    function markAsRead(notificationId) {
        fetch(`/client/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications(); // Refresh notifications
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    function markAllAsRead() {
        fetch('{{ route("client.notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications(); // Refresh notifications
            }
        })
        .catch(error => console.error('Error marking all notifications as read:', error));
    }

    // Auto-refresh every 30 seconds
    setInterval(function() {
        fetch('{{ route("client.notifications.count") }}')
            .then(response => response.json())
            .then(data => {
                updateNotificationBadge(data.count);
            })
            .catch(error => console.log('Error fetching notification count:', error));
    }, 30000);

    // Initial load
    loadNotifications();
});

// Make markAsRead available globally
window.markAsRead = function(notificationId) {
    fetch(`/client/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh notifications
            document.getElementById('notificationDropdown').click();
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
};
</script>
@endpush