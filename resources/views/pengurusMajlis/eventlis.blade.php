<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QRAttend - Event List</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">QRAttend</h1>
                    </div>
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            <a href="/pengurusMajlis/home" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">Dashboard</a>
                            <a href="/pengurusMajlis/events" class="bg-blue-600 text-white px-3 py-2 rounded-md text-sm font-medium">Event List</a>
                            <a href="/pengurusMajlis/events/create" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">New Event</a>
                            <a href="/pengurusMajlis/attendance" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">Attendance</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Profile Avatar -->
                    <div class="relative">
                        <button id="profile-avatar" class="flex items-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-full"
                                aria-haspopup="true" aria-expanded="false" aria-controls="profile-menu">
                            @php
                                $user = Auth::user();
                                $initials = strtoupper(substr($user->name, 0, 2));
                            @endphp
                            @if($user && isset($user->avatar_url) && $user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ $initials }}
                                </div>
                            @endif
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profile-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 opacity-0 invisible transform scale-95 transition-all duration-300 ease-out"
                             role="menu" aria-labelledby="profile-avatar">
                            <a href="/settings" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset"
                               role="menuitem" tabindex="-1">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </a>
                            <button id="logout-btn" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset"
                                    role="menuitem" tabindex="-1">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span id="logout-text">Logout</span>
                                <div id="logout-spinner" class="ml-2 hidden">
                                    <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Event List</h1>
            <p class="mt-2 text-gray-600">Manage and view all your events</p>
        </div>

        <!-- Event List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @forelse($events as $event)
            <div class="event-item border-b border-gray-200 last:border-b-0 p-4 hover:bg-gray-50 transition duration-200" data-event-id="{{ $event->id }}">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Event Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $event->title }}</h3>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('M d, Y') : 'TBD' }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : 'TBD' }}
                                    @if($event->end_time)
                                        - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    {{ $event->location ?: 'Location TBD' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 sm:gap-3">
                        <button class="bg-green-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-green-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm"
                                onclick="window.location.href='/pengurusMajlis/events/{{ $event->id }}/edit'">
                            Edit
                        </button>
                        <button class="bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm"
                                onclick="window.location.href='/pengurusMajlis/attendance/{{ $event->id }}'">
                            Record
                        </button>
                        <button class="bg-red-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm"
                                onclick="confirmDeleteEvent({{ $event->id }}, '{{ addslashes($event->title) }}')">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No events yet</h3>
                <p class="text-gray-600 mb-4">Create your first event to get started with attendance tracking.</p>
                <button class="bg-blue-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-700 transition duration-200"
                        onclick="window.location.href='/pengurusMajlis/events/create'">
                    Create Event
                </button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Mobile Navigation Menu (Hidden by default, shown when hamburger is clicked) -->
    <div id="mobile-menu" class="md:hidden fixed inset-0 z-40 bg-white transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-4 border-b">
                <h1 class="text-xl font-bold text-blue-600">QRAttend</h1>
                <button id="close-mobile-menu" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 px-4 py-6 space-y-4">
                <a href="/pengurusMajlis/home" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 bg-blue-100">Dashboard</a>
                <a href="/pengurusMajlis/events" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-100">Event List</a>
                <a href="/pengurusMajlis/events/create" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-100">New Event</a>
                <a href="/pengurusMajlis/attendance" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-100">Attendance</a>
            </div>
        </div>
    </div>

    <script>
    // Delete event functionality with AJAX
    window.confirmDeleteEvent = async (eventId, eventTitle) => {
        if (confirm(`Are you sure you want to delete "${eventTitle}"? This action cannot be undone.`)) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`/pengurusMajlis/events/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    // Remove the event item from the DOM
                    const eventItem = document.querySelector(`[data-event-id="${eventId}"]`);
                    if (eventItem) {
                        eventItem.remove();
                    }

                    // Check if there are no more events and show empty state if needed
                    const eventItems = document.querySelectorAll('.event-item');
                    if (eventItems.length === 1) { // Only the one being removed
                        setTimeout(() => {
                            location.reload(); // Reload to show empty state properly
                        }, 100);
                    }
                } else {
                    const errorData = await response.json();
                    alert('Failed to delete event: ' + (errorData.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Failed to delete event. Please try again.');
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Profile dropdown functionality
        const avatarBtn = document.getElementById('profile-avatar');
        const dropdown = document.getElementById('profile-menu');
        const logoutBtn = document.getElementById('logout-btn');
        const logoutText = document.getElementById('logout-text');
        const logoutSpinner = document.getElementById('logout-spinner');

        let isOpen = false;

        function toggleDropdown() {
            isOpen = !isOpen;
            avatarBtn.setAttribute('aria-expanded', isOpen);

            if (isOpen) {
                dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.add('opacity-100', 'visible', 'scale-100');
            } else {
                dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
            }
        }

        function closeDropdown() {
            if (isOpen) {
                isOpen = false;
                avatarBtn.setAttribute('aria-expanded', 'false');
                dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
                dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
            }
        }

        avatarBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown();
        });

        document.addEventListener('click', function(e) {
            if (!avatarBtn.contains(e.target) && !dropdown.contains(e.target)) {
                closeDropdown();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropdown();
                avatarBtn.focus();
            }
        });

        dropdown.addEventListener('keydown', function(e) {
            const items = dropdown.querySelectorAll('[role="menuitem"]');
            const currentIndex = Array.from(items).indexOf(document.activeElement);

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    const nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                    items[nextIndex].focus();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    const prevIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                    items[prevIndex].focus();
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    document.activeElement.click();
                    break;
            }
        });

        logoutBtn.addEventListener('click', async function(e) {
            e.preventDefault();

            logoutBtn.disabled = true;
            logoutText.textContent = 'Logging out...';
            logoutSpinner.classList.remove('hidden');

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const response = await fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    window.location.href = '/';
                } else {
                    throw new Error('Logout failed');
                }
            } catch (error) {
                console.error('Logout error:', error);
                alert('Logout failed. Please try again.');
                logoutBtn.disabled = false;
                logoutText.textContent = 'Logout';
                logoutSpinner.classList.add('hidden');
            }
        });

        // Mobile menu functionality
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');

        closeMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.add('translate-x-full');
        });
    });
    </script>
</body>
</html>
