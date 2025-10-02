@extends('layouts.client')

@section('title', 'My Payments')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Payments</h1>
                    <p class="text-gray-600 mt-2">Track your course purchases and payment history</p>
                </div>
                <div class="hidden sm:block">
                    <nav class="flex space-x-1 bg-white rounded-lg p-1 shadow-sm">
                        <a href="{{ route('client.enrollments.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 rounded-md transition-colors">
                            My Learning
                        </a>
                        <span class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md">
                            My Payments
                        </span>
                        <a href="{{ route('client.reviews.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 rounded-md transition-colors">
                            My Reviews
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Spent</p>
                        <p class="text-2xl font-bold text-gray-900">RM{{ number_format($totalSpent, 2) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed Purchases</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $completedCourses }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingPayments }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Payment History</h2>
                <p class="text-sm text-gray-600 mt-1">All your course purchases and transactions</p>
            </div>

            @if($enrollments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($enrollments as $enrollment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($enrollment->course->thumbnail)
                                                <img src="{{ $enrollment->course->thumbnail }}" 
                                                     alt="{{ $enrollment->course->title }}"
                                                     class="w-12 h-12 rounded-lg object-cover mr-4">
                                            @else
                                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($enrollment->course->title, 40) }}
                                                </h3>
                                                <p class="text-sm text-gray-500">{{ $enrollment->course->category->name ?? 'General' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $enrollment->course->instructor->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        @if($enrollment->course->is_free)
                                            <span class="text-green-600 font-semibold">Free</span>
                                        @else
                                            RM{{ number_format($enrollment->amount ?? $enrollment->course->price, 2) }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $status = $enrollment->payment_status ?? 'completed';
                                            $statusConfig = [
                                                'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Completed'],
                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                                'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Failed'],
                                            ];
                                            $config = $statusConfig[$status] ?? $statusConfig['completed'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $config['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $enrollment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('client.courses.show', $enrollment->course->slug) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                View Course
                                            </a>
                                            @if($enrollment->payment_status === 'completed')
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route('client.courses.learn', $enrollment->course->slug) }}" 
                                                   class="text-green-600 hover:text-green-900 font-medium">
                                                    Start Learning
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No payments yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start your learning journey by enrolling in courses.</p>
                    <div class="mt-6">
                        <a href="{{ route('client.courses.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Browse Courses
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection