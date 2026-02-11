<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QRAttend - Home</title>
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
    @include('partials.navbar')

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-200 to-purple-300 text-gray-900 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Effortless Event Attendance with QR Codes
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-purple-700">
                    Generate QR codes, track attendance in real-time, and get detailed reports for your events.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/client/myqr" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                        Get Started
                    </a>
                    <a href="#events" class="border-2 border-gray-900 text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition duration-300">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Event List Section -->
    <section id="events" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Upcoming Events
                </h2>
                <p class="text-xl text-gray-600">
                    Discover exciting events happening around you
                </p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($activeEvents as $event)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition duration-300">
                    <div class="h-48 bg-gradient-to-r from-blue-200 to-purple-300 flex items-center justify-center">
                        @if($event->media_path)
                        <img src="{{ asset('uploads/'. $event->media_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $event->description }}</p>
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $event->date->format('d M Y') }} | {{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            {{ $event->location }}
                        </div>
                        <button onclick="registerForEvent({{ $event->id }}, {{ $event->password_hash ? 'true' : 'false' }})" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                            Register / Join Event
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Events</h3>
                    <p class="text-gray-600">There are currently no active events available.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="pt-20 pb-0 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    How It Works
                </h2>
                <p class="text-xl text-gray-600">
                    Simple steps to manage your event attendance
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                        1
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Create Event</h3>
                    <p class="text-gray-600">Set up your event details and generate unique QR codes for attendees.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Share QR Codes</h3>
                    <p class="text-gray-600">Distribute QR codes to your attendees via email, SMS, or print.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                        3
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Track & Report</h3>
                    <p class="text-gray-600">Monitor real-time attendance and generate detailed reports.</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Password Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Enter Event Password</h2>
            <div id="passwordError" class="text-red-600 text-sm mb-4 hidden"></div>
            <input type="password" id="eventPassword" placeholder="Please enter your event password here" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
            <div class="flex justify-end space-x-3">
                <button onclick="closePasswordModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button onclick="submitPassword()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Enter</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-100 text-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-xl font-bold mb-2">QRAttend</h3>
                <p class="text-gray-600">Making event attendance management effortless.</p>
                <div class="mt-4">
                    <a href="/contact" class="text-gray-600 hover:text-gray-900">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        let currentEventId = null;

        function registerForEvent(eventId, hasPassword) {
            currentEventId = eventId;
            if (hasPassword) {
                // Show password modal
                document.getElementById('passwordModal').classList.remove('hidden');
                document.getElementById('eventPassword').focus();
                document.getElementById('passwordError').classList.add('hidden');
                document.getElementById('passwordError').textContent = '';
            } else {
                // No password required, proceed directly
                joinEvent(eventId);
            }
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
            document.getElementById('eventPassword').value = '';
            document.getElementById('passwordError').classList.add('hidden');
            document.getElementById('passwordError').textContent = '';
        }

        function submitPassword() {
            const password = document.getElementById('eventPassword').value.trim();
            if (!password) {
                showPasswordError('Please enter the event password.');
                return;
            }

            fetch('/events/verify-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    event_id: currentEventId,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to My QR page
                    window.location.href = data.redirect;
                } else {
                    showPasswordError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showPasswordError('An error occurred while verifying the password.');
            });
        }

        function showPasswordError(message) {
            const errorDiv = document.getElementById('passwordError');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function joinEvent(eventId) {
            fetch('/client/register-event', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ event_id: eventId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    console.log(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                console.log('An error occurred while registering for the event.');
            });
        }
    </script>
</body>
</html>
