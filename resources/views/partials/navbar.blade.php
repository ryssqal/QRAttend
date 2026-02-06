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
                <a href="/client/home" class="text-gray-600 hover:text-blue-600 {{ request()->is('client/home') ? 'text-gray-900 font-medium' : '' }}">Home</a>
                <a href="/client/myqr" class="text-gray-600 hover:text-blue-600 {{ request()->is('client/myqr') ? 'text-gray-900 font-medium' : '' }}">MyQR</a>
                <a href="/client/contact" class="text-gray-600 hover:text-blue-600 {{ request()->is('client/contact') ? 'text-gray-900 font-medium' : '' }}">Contact Us</a>

                <!-- Profile Avatar -->
                <div class="relative">
                    <button id="profile-avatar" class="flex items-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-full"
                            aria-haspopup="true" aria-expanded="false" aria-controls="profile-menu">
                        @php
                            $user = Auth::user();
                            $initials = strtoupper(substr($user->name, 0, 2));
                        @endphp
                        @if($user && isset($user->avatar_url) && $user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm sm:text-base">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarBtn = document.getElementById('profile-avatar');
    const dropdown = document.getElementById('profile-menu');
    const logoutBtn = document.getElementById('logout-btn');
    const logoutText = document.getElementById('logout-text');
    const logoutSpinner = document.getElementById('logout-spinner');

    let isOpen = false;

    // Toggle dropdown
    function toggleDropdown() {
        isOpen = !isOpen;
        avatarBtn.setAttribute('aria-expanded', isOpen);

        if (isOpen) {
            dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
            dropdown.classList.add('opacity-100', 'visible', 'scale-100');
            // Focus first menu item
            const firstItem = dropdown.querySelector('[role="menuitem"]');
            if (firstItem) firstItem.focus();
        } else {
            dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
            dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
        }
    }

    // Close dropdown
    function closeDropdown() {
        if (isOpen) {
            isOpen = false;
            avatarBtn.setAttribute('aria-expanded', 'false');
            dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
            dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
        }
    }

    // Avatar button click
    avatarBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleDropdown();
    });

    // Outside click
    document.addEventListener('click', function(e) {
        if (!avatarBtn.contains(e.target) && !dropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    // Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDropdown();
            avatarBtn.focus();
        }
    });

    // Keyboard navigation
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

    // Logout functionality
    logoutBtn.addEventListener('click', async function(e) {
        e.preventDefault();

        // Show loading state
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
                const data = await response.json();
                // Redirect to login or home
                window.location.href = '/';
            } else {
                throw new Error('Logout failed');
            }
        } catch (error) {
            console.error('Logout error:', error);
            // Show error toast or alert
            alert('Logout failed. Please try again.');
            // Reset loading state
            logoutBtn.disabled = false;
            logoutText.textContent = 'Logout';
            logoutSpinner.classList.add('hidden');
        }
    });
});
</script>
