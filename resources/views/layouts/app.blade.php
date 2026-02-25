<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AIHRM') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#000000">

    <style>
        :root {
            --primary-color: {{ \App\Models\Setting::get('primary_color', '#000000') }};
        }
        * { font-family: 'Poppins', 'Inter', sans-serif; }
        .btn-primary {
            background: var(--primary-color);
            color: #fff;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: -0.01em;
            transition: all 0.15s ease;
            border: 1px solid var(--primary-color);
        }
        .btn-primary:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-secondary {
            background: #fff;
            color: #000;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: -0.01em;
            transition: all 0.15s ease;
            border: 1px solid #e5e5e5;
        }
        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }
        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .card:hover {
            border-color: #d4d4d4;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        }
        .text-primary { color: var(--primary-color); }
        .bg-primary { background-color: var(--primary-color); }
        .border-primary { border-color: var(--primary-color); }
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }" class="bg-neutral-50 antialiased h-screen overflow-hidden flex">
    
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Mobile Header & Main Content -->
    <div class="flex-1 flex flex-col md:pl-64 h-screen overflow-hidden">
        
        <!-- Mobile Header -->
        <div class="md:hidden flex items-center justify-between bg-white border-b border-neutral-200 px-4 py-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-black rounded-md flex items-center justify-center">
                    <span class="text-white font-bold text-sm">AI</span>
                </div>
                <span class="font-bold text-lg tracking-tight">AIHRM</span>
            </a>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-neutral-500 hover:text-black">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Top Bar (Desktop) -->
        <header class="bg-white border-b border-neutral-200 h-16 flex items-center justify-between px-6 md:px-8">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ $header ?? 'Dashboard' }}
            </h2>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('chatbot.index') }}" class="text-neutral-500 hover:text-indigo-600 transition-colors" title="AI Assistant">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </a>
                
                <!-- Notifications (Placeholder) -->
                <button class="text-neutral-500 hover:text-black relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>

                <!-- User Profile Dropdown -->
                <div class="relative ml-2" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 p-1 rounded-full hover:bg-neutral-100 transition-colors">
                        <div class="h-8 w-8 rounded-full bg-neutral-900 flex items-center justify-center text-white font-bold text-xs">
                            {{ substr(Auth::user()?->name ?? 'U', 0, 2) }}
                        </div>
                        <span class="hidden lg:block text-sm font-medium text-neutral-700">{{ Auth::user()?->name ?? 'User' }}</span>
                        <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white border border-neutral-200 rounded-lg shadow-lg py-1 z-50 overflow-hidden" 
                         x-cloak>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Your Profile
                        </a>
                        <div class="border-t border-neutral-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-neutral-50 p-6 md:p-8">
            {{ $slot }}
        </main>
    </div>
    
    <x-chatbot-widget />
    <x-toast />

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then((registration) => {
                    console.log('ServiceWorker registered:', registration.scope);
                }).catch((error) => {
                    console.log('ServiceWorker registration failed:', error);
                });
            });
        }

        // Request notification permission
        async function requestNotificationPermission() {
            if ('Notification' in window) {
                const permission = await Notification.requestPermission();
                console.log('Notification permission:', permission);
                return permission === 'granted';
            }
            return false;
        }

        // Show browser notification via service worker
        async function showBrowserNotification(title, body, url) {
            if ('serviceWorker' in navigator && Notification.permission === 'granted') {
                const registration = await navigator.serviceWorker.ready;
                registration.active.postMessage({
                    type: 'SHOW_NOTIFICATION',
                    title: title,
                    body: body,
                    url: url
                });
            }
        }
    </script>
    <script>
        // Notification Polling
        let lastNotificationId = null;
        let lastAnnouncementCount = {{ $unreadAnnouncementCount ?? 0 }};
        
        function pollNotifications() {
            fetch("{{ route('notifications.poll') }}")
                .then(response => response.json())
                .then(data => {
                    if (data && data.id !== lastNotificationId) {
                        lastNotificationId = data.id;
                        // Dispatch event for x-toast component
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                message: data.message,
                                type: data.type
                            }
                        }));
                    }
                })
                .catch(error => console.error('Polling error:', error));
        }

        // Announcement polling
        function pollAnnouncements() {
            fetch("{{ route('announcements.unreadCount') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.count > lastAnnouncementCount) {
                        // New announcements!
                        const newCount = data.count - lastAnnouncementCount;
                        
                        // Show toast notification
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                message: `You have ${newCount} new announcement${newCount > 1 ? 's' : ''}`,
                                type: 'info'
                            }
                        }));

                        // Show browser notification if permitted
                        if (Notification.permission === 'granted') {
                            showBrowserNotification(
                                'New Announcement',
                                `You have ${newCount} new announcement${newCount > 1 ? 's' : ''}`,
                                '/announcements'
                            );
                        }
                    }
                    lastAnnouncementCount = data.count;
                })
                .catch(error => console.error('Announcement polling error:', error));
        }

        // Start polling every 30 seconds
        @auth
            setInterval(pollNotifications, 30000);
            setInterval(pollAnnouncements, 60000); // Poll announcements every minute
            
            // Initial check
            setTimeout(pollNotifications, 2000);
            setTimeout(pollAnnouncements, 5000);
            
            // Request notification permission on first visit
            if ('Notification' in window && Notification.permission === 'default') {
                setTimeout(() => {
                    requestNotificationPermission();
                }, 10000); // Ask after 10 seconds
            }
        @endauth
    </script>
</body>
</html>

