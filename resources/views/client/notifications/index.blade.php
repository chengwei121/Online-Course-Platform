@extends('layouts.client')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 pt-24">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Stats -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                            <i class="fas fa-bell text-white text-lg"></i>
                        </div>
                        Notifications
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">Stay updated with your course activities</p>
                </div>
                
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('client.notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                            <i class="fas fa-check-double"></i>
                            Mark All as Read
                        </button>
                    </form>
                @endif
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-3 mt-4">
                <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 font-medium">Total</p>
                            <p class="text-xl font-bold text-gray-900">{{ $notifications->total() }}</p>
                        </div>
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 font-medium">Unread</p>
                            <p class="text-xl font-bold text-orange-600">{{ $notifications->where('is_read', false)->count() }}</p>
                        </div>
                        <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-envelope-open text-orange-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-600 font-medium">Read</p>
                            <p class="text-xl font-bold text-green-600">{{ $notifications->where('is_read', true)->count() }}</p>
                        </div>
                        <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-4">
            <form method="GET" action="{{ route('client.notifications.index') }}" class="flex flex-col sm:flex-row gap-2">
                <div class="flex-1">
                    <select name="type" class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">All Types</option>
                        @foreach($notificationTypes as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1">
                    <select name="status" class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 text-sm bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm hover:shadow-md font-medium">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="{{ route('client.notifications.index') }}" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                        <i class="fas fa-times mr-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-2">
                @foreach($notifications as $notification)
                    <div class="group bg-white rounded-lg shadow-sm border {{ !$notification->is_read ? 'border-blue-200 bg-blue-50/30' : 'border-gray-100' }} hover:shadow-md transition-all duration-200">
                        <div class="p-3">
                            <div class="flex gap-3">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    @php
                                        $iconBg = match($notification->type) {
                                            'grade' => 'bg-gradient-to-br from-green-400 to-green-600',
                                            'assignment' => 'bg-gradient-to-br from-blue-400 to-blue-600',
                                            'course_update' => 'bg-gradient-to-br from-purple-400 to-purple-600',
                                            'announcement' => 'bg-gradient-to-br from-orange-400 to-orange-600',
                                            'enrollment' => 'bg-gradient-to-br from-pink-400 to-pink-600',
                                            'reminder' => 'bg-gradient-to-br from-yellow-400 to-yellow-600',
                                            default => 'bg-gradient-to-br from-gray-400 to-gray-600'
                                        };
                                    @endphp
                                    <div class="w-11 h-11 {{ $iconBg }} rounded-lg flex items-center justify-center shadow-md">
                                        <i class="{{ $notification->icon }} text-white text-base"></i>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <h3 class="text-base font-semibold text-gray-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                            {{ $notification->title }}
                                        </h3>
                                        
                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                            @if(!$notification->is_read)
                                                <span class="px-2 py-0.5 bg-blue-500 text-white text-xs font-bold rounded-full">NEW</span>
                                            @endif
                                            
                                            @if($notification->priority === 'high')
                                                <span class="px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">
                                                    <i class="fas fa-exclamation-circle mr-0.5"></i>HIGH
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-700 mb-2">{{ $notification->message }}</p>

                                    <!-- Footer -->
                                    <div class="flex flex-wrap items-center gap-3 text-xs">
                                        <span class="flex items-center gap-1 text-gray-500">
                                            <i class="far fa-clock"></i>
                                            {{ $notification->time_ago }}
                                        </span>
                                        
                                        @if($notification->sender)
                                            <span class="flex items-center gap-1 text-gray-500">
                                                <i class="far fa-user"></i>
                                                {{ $notification->sender->name }}
                                            </span>
                                        @endif

                                        <div class="ml-auto flex items-center gap-1.5">
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 font-medium text-xs shadow-sm hover:shadow-md">
                                                    <i class="fas fa-arrow-right text-xs"></i>
                                                    View Details
                                                </a>
                                            @endif

                                            <!-- Actions Dropdown -->
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open" @click.away="open = false" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                                    <i class="fas fa-ellipsis-v text-sm"></i>
                                                </button>
                                                
                                                @if(!$notification->is_read)
                                                    <div x-show="open" 
                                                         x-transition:enter="transition ease-out duration-100"
                                                         x-transition:enter-start="transform opacity-0 scale-95"
                                                         x-transition:enter-end="transform opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-75"
                                                         x-transition:leave-start="transform opacity-100 scale-100"
                                                         x-transition:leave-end="transform opacity-0 scale-95"
                                                         class="absolute right-0 bottom-full mb-1 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50"
                                                         style="display: none;">
                                                        
                                                        <form action="{{ route('client.notifications.read', $notification) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="w-full text-left px-3 py-1.5 text-xs text-gray-700 hover:bg-blue-50 transition-colors flex items-center gap-2">
                                                                <i class="fas fa-check text-blue-500"></i>
                                                                Mark as Read
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Read indicator line -->
                        @if(!$notification->is_read)
                            <div class="h-0.5 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notifications->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-10 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-bell-slash text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No Notifications</h3>
                <p class="text-sm text-gray-500 max-w-sm mx-auto">
                    You're all caught up! Check back later for new updates and announcements.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection