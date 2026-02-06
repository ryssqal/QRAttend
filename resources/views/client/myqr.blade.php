<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QRAttend - My QR</title>
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
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">QRAttend</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="/client/home" class="text-gray-600 hover:text-blue-600">Home</a>
                    <a href="/client/myqr" class="text-gray-900 hover:text-blue-600 font-medium">MyQR</a>
                    <a href="/client/contact" class="text-gray-600 hover:text-blue-600">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My QR Codes</h1>
            <p class="text-gray-600">Manage and generate QR codes for your events</p>
        </div>

        <!-- Active QR Code Section -->
        @if($activeEvents->isNotEmpty())
            @php $activeEvent = $activeEvents->first(); @endphp
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Active QR Code</h2>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Active</span>
                </div>

                <!-- Event Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $activeEvent->title }}</h3>
                    <div class="grid md:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Date:</span> {{ $activeEvent->date ? $activeEvent->date->format('F j, Y') : 'N/A' }}
                        </div>
                        <div>
                            <span class="font-medium">Time:</span> {{ $activeEvent->start_time ? $activeEvent->start_time->format('g:i A') : 'N/A' }} - {{ $activeEvent->end_time ? $activeEvent->end_time->format('g:i A') : 'N/A' }}
                        </div>
                        <div>
                            <span class="font-medium">Attendees:</span> {{ $activeEvent->participants->count() }} / {{ $activeEvent->pax }}
                        </div>
                    </div>
                </div>

                <!-- QR Code Display -->
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-shrink-0">
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4 shadow-sm text-center">
                            {!! $qrCodes[$activeEvent->id] ?? '<p class="text-red-500">QR Code not generated yet</p>' !!}
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Scan to Attend</h4>
                                <p class="text-gray-600">Attendees can scan this QR code to mark their attendance at the event.</p>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('client.downloadQr', $activeEvent->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-300 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download PNG
                                </a>
                                <button type="button"
                                        onclick="deactivateQr({{ $activeEvent->id }}, '{{ addslashes($activeEvent->title) }}')"
                                        class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition duration-300 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Deactivate QR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Active QR Code</h3>
                    <p class="text-gray-600">You don't have any active events with QR codes at the moment.</p>
                </div>
            </div>
        @endif

        <!-- Used / Inactive QR Codes Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Used / Inactive QR Codes</h2>
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Completed</span>
            </div>

            @if($usedEvents->isNotEmpty())
                <div class="space-y-4">
                    @foreach($usedEvents as $event)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $event->date ? $event->date->format('F j, Y') : 'N/A' }} • {{ $event->participants->count() }} / {{ $event->pax }} attendees</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">Details</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Used QR Codes</h3>
                    <p class="text-gray-600">You haven't used any QR codes yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-xl font-bold mb-2">QRAttend</h3>
                <p class="text-gray-400">Making event attendance management effortless.</p>
                <div class="mt-4">
                    <a href="/contact" class="text-gray-400 hover:text-white">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        async function deactivateQr(eventId, eventTitle) {
            // Show confirmation dialog
            if (!confirm(`Nyahaktifkan QR untuk "${eventTitle}"? Peserta tidak akan dapat mengimbas lagi, tetapi data sedia ada akan disimpan.`)) {
                return;
            }

            try {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

                // Send AJAX request
                const response = await fetch(`/client/event/${eventId}/deactivate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : '',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    alert(data.message);

                    // Move the card from active to inactive section
                    moveToInactiveSection(eventId, eventTitle);
                } else {
                    // Show error message
                    alert('Error: ' + (data.message || 'Failed to deactivate QR code'));
                }
            } catch (error) {
                console.error('AJAX Error:', error);
                alert('An error occurred while deactivating the QR code. Please check the console for details.');
            }
        }

        function moveToInactiveSection(eventId, eventTitle) {
            // Find the active section card
            const activeSection = document.querySelector('.bg-white.rounded-lg.shadow-sm.border.p-6.mb-8');
            if (!activeSection) return;

            // Find the inactive section
            const inactiveSection = document.querySelectorAll('.bg-white.rounded-lg.shadow-sm.border.p-6')[1];
            if (!inactiveSection) return;

            // Find the "No Used QR Codes" message
            const noUsedMessage = inactiveSection.querySelector('.text-center.py-8');
            if (noUsedMessage) {
                // Replace with a container for inactive events
                const container = document.createElement('div');
                container.className = 'space-y-4';
                noUsedMessage.parentNode.replaceChild(container, noUsedMessage);
            }

            // Create new inactive event card
            const eventCard = document.createElement('div');
            eventCard.className = 'flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300';
            eventCard.innerHTML = `
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">${eventTitle}</h3>
                        <p class="text-sm text-gray-600">Recently deactivated • 0 / 0 attendees</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">Details</button>
                </div>
            `;

            // Add to inactive section
            const container = inactiveSection.querySelector('.space-y-4') || inactiveSection;
            container.appendChild(eventCard);

            // Remove from active section
            activeSection.remove();
        }
    </script>
</body>
</html>
