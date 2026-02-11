<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QRAttend - Dashboard</title>
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
                            <a href="/pengurusMajlis/home" class="bg-blue-600 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="/pengurusMajlis/events" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">Event List</a>
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
        <!-- Dashboard Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Event Card -->
            <div class="bg-blue-600 text-white rounded-lg p-6 shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Active Event</p>
                        <p class="text-3xl font-bold">{{ $activeEvents ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Registered Guest Card -->
            <div class="bg-blue-600 text-white rounded-lg p-6 shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Registered Guest</p>
                        <p class="text-3xl font-bold">{{ $registeredGuests ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Attendance Confirmed Card -->
            <div class="bg-blue-600 text-white rounded-lg p-6 shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Attendance Confirmed</p>
                        <p class="text-3xl font-bold">{{ $confirmedAttendance ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Add New Event Card -->
            <div class="bg-blue-600 text-white rounded-lg p-6 shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='/pengurusMajlis/events/create'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Add New Event</p>
                        <p class="text-3xl font-bold">+</p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event List Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 lg:mb-0">Event List</h2>

                <!-- Compact Stats Bar -->
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Active: {{ $activeEvents ?? 0 }}
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Guests: {{ $registeredGuests ?? 0 }}
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Confirmed: {{ $confirmedAttendance ?? 0 }}
                    </div>
                </div>
            </div>

            <!-- Event Cards Grid -->
             @php use Illuminate\Support\Str; @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events ?? [] as $event)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition duration-300">
                    <!-- Event Banner -->
                    <div class="h-48 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                        @if($event->media_path)
                            @if(Str::endsWith($event->media_path, '.mp4'))
                                <video class="w-full h-full object-cover" controls>
                                    <source src="{{ asset('uploads/' . $event->media_path) }}" type="video/mp4">
                                </video>
                            @else
                                <img 
                                    src="{{ asset('uploads/' . $event->media_path) }}" 
                                    alt="{{ $event->title }}" 
                                    class="w-full h-full object-cover"
                                >
                            @endif
                        @endif

                    </div>

                    <!-- Event Details -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                Location TBD
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('M d, Y') : 'TBD' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : 'TBD' }}
                                @if($event->end_time)
                                    - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    onclick="window.location.href='/pengurusMajlis/attendance/{{ $event->id }}'">
                                Record
                            </button>
                            <button class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                    onclick="confirmDeleteEvent({{ $event->id }}, '{{ addslashes($event->title) }}')">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="col-span-full text-center py-12">
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
    // Delete event functionality
    window.confirmDeleteEvent = (eventId, eventTitle) => {
        if (confirm(`Are you sure you want to delete "${eventTitle}"? This action cannot be undone.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/pengurusMajlis/events/${eventId}`;

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);

            // Add method spoofing for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            document.body.appendChild(form);
            form.submit();
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Profile dropdown functionality (same as before)
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
                const response = await fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
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
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeMobileMenu = document.getElementById('close-mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.remove('translate-x-full');
        });

        closeMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.add('translate-x-full');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('translate-x-full');
            }
        });
    });
    </script>
</body>
</html>
