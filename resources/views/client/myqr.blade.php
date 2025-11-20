<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                    <a href="/contact" class="text-gray-600 hover:text-blue-600">Contact Us</a>
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
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Active QR Code</h2>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Active</span>
            </div>

            <!-- Event Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tech Conference 2024</h3>
                <div class="grid md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Date:</span> December 15, 2024
                    </div>
                    <div>
                        <span class="font-medium">Time:</span> 9:00 AM - 5:00 PM
                    </div>
                    <div>
                        <span class="font-medium">Attendees:</span> 0 / 200
                    </div>
                </div>
            </div>

            <!-- QR Code Display -->
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-shrink-0">
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Placeholder QR Code - In real app, this would be generated dynamically -->
                        <div class="w-48 h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-32 h-32 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm12 0v2h2V5h-2zM3 11h6v6H3v-6zm2 2v2h2v-2H5zm8-6h6v6h-6V7zm2 2v2h2V9h-2zm-8 8h6v6H7v-6zm2 2v2h2v-2H9zm8-8h6v6h-6v-6zm2 2v2h2v-2h-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex-1">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Scan to Attend</h4>
                            <p class="text-gray-600">Attendees can scan this QR code to mark their attendance at the event.</p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download PNG
                            </button>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Copy Link
                            </button>
                            <button class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email QR
                            </button>
                            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Deactivate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History QR Code Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">History QR Code</h2>
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Completed</span>
            </div>

            <div class="space-y-4">
                <!-- History Item 1 -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Workshop: AI Basics</h3>
                            <p class="text-sm text-gray-600">November 20, 2024 • 45 / 50 attendees</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View QR</button>
                        <button class="text-green-600 hover:text-green-800 text-sm font-medium">Download</button>
                        <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">Details</button>
                    </div>
                </div>

                <!-- History Item 2 -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Career Talk Series</h3>
                            <p class="text-sm text-gray-600">October 15, 2024 • 120 / 150 attendees</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View QR</button>
                        <button class="text-green-600 hover:text-green-800 text-sm font-medium">Download</button>
                        <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">Details</button>
                    </div>
                </div>

                <!-- History Item 3 -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Web Development Bootcamp</h3>
                            <p class="text-sm text-gray-600">September 10, 2024 • 85 / 100 attendees</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View QR</button>
                        <button class="text-green-600 hover:text-green-800 text-sm font-medium">Download</button>
                        <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">Details</button>
                    </div>
                </div>

                <!-- History Item 4 -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Digital Marketing Workshop</h3>
                            <p class="text-sm text-gray-600">August 25, 2024 • 65 / 80 attendees</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View QR</button>
                        <button class="text-green-600 hover:text-green-800 text-sm font-medium">Download</button>
                        <button class="text-gray-600 hover:text-gray-800 text-sm font-medium">Details</button>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-6">
                <button class="bg-gray-100 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-200 transition duration-300">
                    Load More History
                </button>
            </div>
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
</body>
</html>
