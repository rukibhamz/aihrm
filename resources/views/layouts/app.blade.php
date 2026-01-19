<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AIHRM') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#000000">

    <style>
        * { font-family: 'Inter', sans-serif; }
        .btn-primary {
            background: #000;
            color: #fff;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: -0.01em;
            transition: all 0.15s ease;
            border: 1px solid #000;
        }
        .btn-primary:hover {
            background: #1a1a1a;
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
            border-color: #000;
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
    </style>
</head>
<body class="bg-neutral-50 antialiased h-screen overflow-hidden flex">
    
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
            <button @click="open = !open" x-data="{ open: false }" class="text-neutral-500 hover:text-black">
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

                </form>
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
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    <script>
        // Notification Polling
        let lastNotificationId = null;
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

        // Start polling every 30 seconds
        @auth
            setInterval(pollNotifications, 30000);
            // Initial check
            setTimeout(pollNotifications, 2000);
        @endauth
    </script>
</body>
</html>

