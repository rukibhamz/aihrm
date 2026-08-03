<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function () {
            try {
                var root = document.documentElement;
                var theme = localStorage.getItem('aihrm-theme');
                if (theme === 'dark') root.classList.add('dark');
                else root.classList.remove('dark');

                // Apply sidebar width before paint (desktop only)
                var collapsed = localStorage.getItem('sidebarCollapsed') === 'true'
                    && window.matchMedia('(min-width: 768px)').matches;
                root.classList.toggle('sidebar-collapsed', collapsed);
            } catch (e) {}
        })();
    </script>
    <style>[x-cloak]{display:none!important}</style>
    <x-branding-meta />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="{{ \App\Models\Setting::get('primary_color', '#000000') }}">

    <style>
        :root {
            --primary-color: {{ \App\Models\Setting::get('primary_color', '#000000') }};
            --secondary-color: {{ \App\Models\Setting::get('secondary_color', '#171717') }};
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
        .dark .btn-secondary {
            background: #171717;
            color: #f5f5f5;
            border-color: #404040;
        }
        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }
        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 0.75rem;
            transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .dark .card {
            background: #18181b;
            border-color: #27272a;
            box-shadow: 0 1px 0 rgba(255,255,255,0.04);
        }
        .card:hover {
            border-color: #d4d4d4;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        }
        .dark .card:hover {
            border-color: #3f3f46;
            box-shadow: 0 8px 24px rgba(0,0,0,0.35);
        }
        .text-primary { color: var(--primary-color); }
        .bg-primary { background-color: var(--primary-color); }
        .border-primary { border-color: var(--primary-color); }

        /* Sidebar — light */
        .sidebar-nav-link {
            color: #525252;
            border-radius: 0.75rem;
            transition: background-color 0.15s ease, color 0.15s ease;
        }
        .sidebar-nav-link:hover {
            background-color: #f5f5f5;
            color: #171717;
        }
        .sidebar-nav-link:hover svg {
            color: #171717;
        }
        .sidebar-nav-link-active {
            background-color: color-mix(in srgb, var(--primary-color) 12%, white) !important;
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        .sidebar-nav-link-active svg {
            color: var(--primary-color) !important;
        }
        .sidebar-sub-link {
            color: #737373;
            border-radius: 0.625rem;
            font-size: 0.8125rem;
            transition: background-color 0.15s ease, color 0.15s ease;
        }
        .sidebar-sub-link:hover {
            background-color: #f5f5f5;
            color: #171717;
        }
        .sidebar-sub-link-active {
            background-color: color-mix(in srgb, var(--primary-color) 8%, white) !important;
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        .sidebar-sub-link-active span {
            color: var(--primary-color) !important;
            opacity: 1 !important;
        }
        .sidebar-sub-link-active svg {
            color: var(--primary-color) !important;
        }
        .sidebar-nav-parent-active {
            background-color: #f5f5f5 !important;
            color: #171717 !important;
            font-weight: 600;
        }
        .sidebar-nav-parent-active svg {
            color: var(--primary-color) !important;
        }
        .sidebar-nav-parent-active span {
            color: #171717 !important;
            font-weight: 600;
        }
        .sidebar-submenu {
            margin-left: 1.125rem;
            padding-left: 0.75rem;
            border-left: 1px solid #e5e5e5;
        }
        .sidebar-section-label {
            font-size: 0.625rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #a3a3a3;
            padding: 0.75rem 0.75rem 0.375rem;
        }
        .sidebar-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.25rem;
            height: 1.25rem;
            padding: 0 0.375rem;
            border-radius: 9999px;
            font-size: 0.625rem;
            font-weight: 700;
            background-color: #f5f5f5;
            color: #525252;
        }
        .sidebar-badge-active {
            background-color: color-mix(in srgb, var(--primary-color) 15%, white);
            color: var(--primary-color);
        }
        .sidebar-search {
            background-color: #f5f5f5;
            color: #171717;
        }
        .sidebar-search::placeholder { color: #a3a3a3; }
        .sidebar-search:focus {
            outline: none;
            box-shadow: 0 0 0 2px color-mix(in srgb, var(--primary-color) 25%, transparent);
        }

        /* Sidebar — dark */
        .dark .sidebar-nav-link {
            color: #a3a3a3;
        }
        .dark .sidebar-nav-link:hover {
            background-color: rgba(255,255,255,0.06);
            color: #f5f5f5;
        }
        .dark .sidebar-nav-link:hover svg {
            color: #f5f5f5;
        }
        .dark .sidebar-nav-link-active {
            background-color: rgba(255,255,255,0.08) !important;
            color: #fafafa !important;
        }
        .dark .sidebar-nav-link-active svg {
            color: #fafafa !important;
        }
        .dark .sidebar-sub-link {
            color: #737373;
        }
        .dark .sidebar-sub-link:hover {
            background-color: rgba(255,255,255,0.06);
            color: #e5e5e5;
        }
        .dark .sidebar-sub-link-active {
            background-color: rgba(255,255,255,0.08) !important;
            color: #ffffff !important;
        }
        .dark .sidebar-sub-link-active span,
        .dark .sidebar-sub-link-active svg {
            color: #ffffff !important;
        }
        .dark .sidebar-nav-parent-active {
            background-color: rgba(255,255,255,0.08) !important;
            color: #ffffff !important;
        }
        .dark .sidebar-nav-parent-active svg {
            color: #ffffff !important;
        }
        .dark .sidebar-nav-parent-active span {
            color: #ffffff !important;
        }
        .dark .sidebar-submenu {
            border-left-color: rgba(255,255,255,0.1);
        }
        .dark .sidebar-section-label {
            color: #525252;
        }
        .dark .sidebar-badge {
            background-color: rgba(255,255,255,0.08);
            color: #d4d4d4;
        }
        .dark .sidebar-badge-active {
            background-color: color-mix(in srgb, var(--primary-color) 35%, transparent);
            color: #fff;
        }
        .dark .sidebar-search {
            background-color: rgba(255,255,255,0.06);
            color: #f4f4f5;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .dark .sidebar-search::placeholder { color: #737373; }

        /* Theme toggle switch */
        .theme-switch {
            width: 2.75rem;
            height: 1.5rem;
            border-radius: 9999px;
            position: relative;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
        }
        .theme-switch-knob {
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 9999px;
            position: absolute;
            top: 0.1875rem;
            left: 0.1875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .theme-switch.is-dark {
            background: color-mix(in srgb, var(--primary-color) 55%, #262626);
        }
        .theme-switch.is-light {
            background: color-mix(in srgb, var(--primary-color) 35%, #e5e5e5);
        }
        .theme-switch.is-dark .theme-switch-knob {
            transform: translateX(1.25rem);
            background: #171717;
            color: #fafafa;
        }
        .theme-switch.is-light .theme-switch-knob {
            color: #171717;
        }

        /*
         | Dark mode content remaps — pages use light-only utilities.
         | Zinc surface hierarchy: page 950 → card 900 → elevated 800.
         */
        .dark .text-black,
        .dark .text-gray-900,
        .dark .text-neutral-900 {
            color: #fafafa !important;
        }
        .dark .text-gray-800,
        .dark .text-neutral-800 {
            color: #e4e4e7 !important;
        }
        .dark .text-gray-700,
        .dark .text-neutral-700 {
            color: #d4d4d8 !important;
        }
        .dark .text-gray-600,
        .dark .text-neutral-600 {
            color: #a1a1aa !important;
        }
        .dark .text-gray-500,
        .dark .text-neutral-500 {
            color: #a1a1aa !important;
        }
        .dark .text-gray-400,
        .dark .text-neutral-400 {
            color: #71717a !important;
        }
        .dark .text-gray-300 {
            color: #52525b !important;
        }
        .dark .text-indigo-900,
        .dark .hover\:text-indigo-900:hover {
            color: #c7d2fe !important;
        }
        .dark .text-blue-600,
        .dark .hover\:text-blue-800:hover,
        .dark .hover\:text-blue-900:hover {
            color: #93c5fd !important;
        }
        .dark .bg-gray-50,
        .dark .bg-neutral-50,
        .dark .bg-neutral-50\/50 {
            background-color: #27272a !important;
        }
        .dark .bg-gray-100,
        .dark .bg-neutral-100 {
            background-color: #3f3f46 !important;
        }
        /* Content panels that still use raw bg-white (main + header chrome, not sidebar) */
        .dark .flex-1 .bg-white,
        .dark .flex-1 .bg-white\/80,
        .dark main .bg-white\/80 {
            background-color: #18181b !important;
        }
        .dark .border-gray-50,
        .dark .border-gray-100,
        .dark .border-neutral-100 {
            border-color: #27272a !important;
        }
        .dark .border-gray-200,
        .dark .border-neutral-200 {
            border-color: #3f3f46 !important;
        }
        .dark .border-neutral-300,
        .dark .border-gray-300 {
            border-color: #3f3f46 !important;
        }
        .dark .border-black {
            border-color: #52525b !important;
        }
        .dark .divide-gray-100 > :not([hidden]) ~ :not([hidden]),
        .dark .divide-neutral-100 > :not([hidden]) ~ :not([hidden]),
        .dark .divide-gray-200 > :not([hidden]) ~ :not([hidden]),
        .dark .divide-neutral-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #3f3f46;
        }
        .dark .hover\:bg-gray-50:hover,
        .dark .hover\:bg-neutral-50:hover,
        .dark .hover\:bg-gray-100:hover,
        .dark .hover\:bg-neutral-100:hover,
        .dark .hover\:bg-red-50:hover {
            background-color: #27272a !important;
        }
        .dark .shadow-sm,
        .dark .shadow,
        .dark .shadow-md,
        .dark .shadow-lg,
        .dark .shadow-xl {
            --tw-shadow-color: rgba(0,0,0,0.45);
        }
        /* Soft status / alert surfaces */
        .dark .bg-green-50,
        .dark .bg-green-100 { background-color: rgba(22,163,74,0.15) !important; }
        .dark .border-green-200 { border-color: rgba(22,163,74,0.35) !important; }
        .dark .text-green-900 { color: #86efac !important; }
        .dark .bg-red-50,
        .dark .bg-red-100 { background-color: rgba(239,68,68,0.15) !important; }
        .dark .border-red-200 { border-color: rgba(239,68,68,0.35) !important; }
        .dark .text-red-700,
        .dark .text-red-800 { color: #fca5a5 !important; }
        .dark .bg-yellow-50,
        .dark .bg-yellow-100 { background-color: rgba(234,179,8,0.15) !important; }
        .dark .border-yellow-200 { border-color: rgba(234,179,8,0.35) !important; }
        .dark .text-yellow-700,
        .dark .text-yellow-800 { color: #fde047 !important; }
        .dark .bg-blue-50,
        .dark .bg-blue-100 { background-color: rgba(59,130,246,0.15) !important; }
        .dark .border-blue-100,
        .dark .border-blue-200 { border-color: rgba(59,130,246,0.35) !important; }
        .dark .text-blue-700,
        .dark .text-blue-800 { color: #93c5fd !important; }
        .dark .bg-purple-50,
        .dark .bg-purple-100 { background-color: rgba(168,85,247,0.15) !important; }
        .dark .text-purple-800 { color: #d8b4fe !important; }
        .dark .bg-orange-50,
        .dark .bg-orange-100 { background-color: rgba(249,115,22,0.15) !important; }
        .dark .text-orange-800 { color: #fdba74 !important; }
        .dark .bg-emerald-50,
        .dark .bg-emerald-100 { background-color: rgba(16,185,129,0.15) !important; }
        .dark .text-emerald-800 { color: #6ee7b7 !important; }
        /* Forms inside main content */
        .dark main input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="color"]),
        .dark main select,
        .dark main textarea {
            background-color: #27272a !important;
            border-color: #3f3f46 !important;
            color: #fafafa !important;
        }
        .dark main input:disabled,
        .dark main input[readonly],
        .dark main select:disabled,
        .dark main textarea:disabled {
            background-color: #1f1f23 !important;
            color: #71717a !important;
        }
        .dark main input::placeholder,
        .dark main textarea::placeholder {
            color: #71717a !important;
        }
        .dark main option {
            background-color: #18181b;
            color: #fafafa;
        }
        .dark main table thead {
            background-color: #27272a !important;
        }
        .dark main table tbody {
            background-color: transparent !important;
        }
        /* Sticky / frosted bars (settings save bar, etc.) */
        .dark main .sticky[class*="bg-white"],
        .dark main [class*="bg-white/"][class*="backdrop-blur"] {
            background-color: rgba(24, 24, 27, 0.92) !important;
            border-color: #3f3f46 !important;
        }
        /* Pagination */
        .dark main nav[role="navigation"] span,
        .dark main nav[role="navigation"] a {
            border-color: #3f3f46 !important;
        }
        .dark main nav[role="navigation"] .bg-white {
            background-color: #18181b !important;
        }
        /* Modal / dropdown panels rendered outside main */
        .dark [role="dialog"] .bg-white,
        .dark [x-show].bg-white {
            background-color: #18181b !important;
        }

        /* Sidebar width — FOUC-safe, no Alpine required */
        #app-sidebar-desktop {
            width: 16rem;
            min-width: 16rem;
            max-width: 16rem;
            overflow: hidden;
        }
        html.sidebar-collapsed #app-sidebar-desktop {
            width: 4.5rem;
            min-width: 4.5rem;
            max-width: 4.5rem;
            overflow: visible;
            position: relative;
            z-index: 40;
        }
        /* Only animate after explicit user toggle (never on first paint) */
        html.sidebar-anim #app-sidebar-desktop {
            transition: width 0.25s ease, min-width 0.25s ease, max-width 0.25s ease;
        }
        /* Icon-only collapsed layout via CSS (works before Alpine) */
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-brand-text,
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-section-label,
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-label,
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-submenu:not(.sidebar-flyout),
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-nav-link > span,
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-nav-link > .sidebar-badge,
        html.sidebar-collapsed #app-sidebar-desktop button.sidebar-nav-link > span,
        html.sidebar-collapsed #app-sidebar-desktop button.sidebar-nav-link > svg:last-of-type:not(:first-of-type) {
            display: none !important;
        }

        /* Collapsed hover label (name tip) */
        .sidebar-collapsed-tip {
            white-space: nowrap;
            padding: 0.4rem 0.75rem;
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.25;
            border-radius: 0.5rem;
            background: #18181b;
            color: #fafafa;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
            pointer-events: none;
        }
        .dark .sidebar-collapsed-tip {
            background: #27272a;
            color: #f4f4f5;
            border: 1px solid #3f3f46;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
        }

        /* Collapsed click flyout (submenu panel) */
        .sidebar-flyout {
            margin: 0 !important;
            padding: 0.375rem !important;
            border-left: none !important;
            min-width: 13.5rem;
            max-width: 18rem;
            max-height: calc(100vh - 1rem);
            overflow-y: auto;
            border-radius: 0.75rem;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.14);
        }
        .dark .sidebar-flyout {
            background: #18181b;
            border-color: #27272a;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
        }
        .sidebar-flyout .sidebar-sub-link {
            padding-left: 0.625rem !important;
            padding-right: 0.75rem !important;
        }
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-nav-link,
        html.sidebar-collapsed #app-sidebar-desktop button.sidebar-nav-link {
            justify-content: center !important;
            gap: 0 !important;
            padding-left: 0.625rem !important;
            padding-right: 0.625rem !important;
        }
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-brand-wrap {
            justify-content: center !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-brand-wrap a {
            gap: 0 !important;
            justify-content: center;
        }
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-nav-wrap,
        html.sidebar-collapsed #app-sidebar-desktop .sidebar-footer {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        /* Header search */
        .header-search {
            background-color: #f5f5f5;
            color: #171717;
            border: 1px solid transparent;
        }
        .header-search::placeholder { color: #a3a3a3; }
        .header-search:focus {
            outline: none;
            border-color: color-mix(in srgb, var(--primary-color) 35%, transparent);
            box-shadow: 0 0 0 2px color-mix(in srgb, var(--primary-color) 20%, transparent);
        }
        .dark .header-search {
            background-color: rgba(255,255,255,0.06);
            color: #f4f4f5;
            border-color: rgba(255,255,255,0.08);
        }
        .dark .header-search::placeholder { color: #737373; }

        /* Dynamic Theme Overrides */
        .bg-secondary { background-color: var(--secondary-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }
        .border-secondary { border-color: var(--secondary-color) !important; }
    </style>
</head>
<body x-data="{
          mobileMenuOpen: false,
          sidebarCollapsed: false,
          navQuery: '',
          darkMode: document.documentElement.classList.contains('dark'),
          init() {
              const mq = window.matchMedia('(min-width: 768px)');
              this.sidebarCollapsed = mq.matches && localStorage.getItem('sidebarCollapsed') === 'true';
              this.syncSidebarClass();
          },
          syncSidebarClass() {
              const collapsed = this.sidebarCollapsed && window.matchMedia('(min-width: 768px)').matches;
              document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
              window.dispatchEvent(new CustomEvent('sidebar-collapse-changed', { detail: { collapsed } }));
          },
          toggleSidebar() {
              document.documentElement.classList.add('sidebar-anim');
              this.sidebarCollapsed = !this.sidebarCollapsed;
              this.syncSidebarClass();
              if (window.matchMedia('(min-width: 768px)').matches) {
                  localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
              }
          },
          expandSidebar() {
              if (!this.sidebarCollapsed) return;
              document.documentElement.classList.add('sidebar-anim');
              this.sidebarCollapsed = false;
              this.syncSidebarClass();
              if (window.matchMedia('(min-width: 768px)').matches) {
                  localStorage.setItem('sidebarCollapsed', 'false');
              }
          }
      }"
      x-init="
          $watch('darkMode', value => {
              document.documentElement.classList.toggle('dark', value);
              localStorage.setItem('aihrm-theme', value ? 'dark' : 'light');
          });
          $watch('navQuery', value => {
              if (value && sidebarCollapsed) expandSidebar();
          });
          window.addEventListener('resize', () => {
              if (!window.matchMedia('(min-width: 768px)').matches) {
                  sidebarCollapsed = false;
              } else {
                  sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
              }
              syncSidebarClass();
          });
      "
      class="bg-zinc-100 dark:bg-zinc-950 text-neutral-900 dark:text-zinc-100 antialiased h-screen overflow-hidden flex">
    
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Mobile Header & Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Mobile Header -->
        <div class="md:hidden flex items-center justify-between gap-2 bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-800 px-4 py-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0">
                @if(!empty($companyLogo))
                    <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName ?? 'Logo' }}" class="h-8 w-8 object-contain rounded-lg flex-shrink-0">
                @else
                    <div class="w-8 h-8 bg-neutral-900 dark:bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-white dark:text-neutral-900 font-bold text-sm">AI</span>
                    </div>
                @endif
                <span class="font-semibold text-lg tracking-tight text-neutral-900 dark:text-white truncate">{{ $companyName ?? config('app.name', 'AIHRM') }}</span>
            </a>
            <div class="flex items-center gap-1.5 flex-shrink-0">
                <button type="button"
                        @click="darkMode = !darkMode"
                        class="p-2 rounded-lg text-neutral-500 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-zinc-800"
                        :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="darkMode" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white relative p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-zinc-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(Auth::user() && Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-neutral-900"></span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-white">
                            <span class="text-sm font-semibold text-gray-700">Notifications</span>
                            @if(Auth::user() && Auth::user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifications.markAllRead') }}" class="text-xs text-blue-600 hover:text-blue-800">Mark all read</a>
                            @endif
                        </div>

                        <div class="max-h-64 overflow-y-auto w-80 bg-white">
                            @if(Auth::user())
                                @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                    <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50">
                                        <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        No new notifications
                                    </div>
                                @endforelse
                            @endif
                        </div>

                        <div class="px-4 py-2 border-t border-gray-100 text-center bg-gray-50">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
                        </div>
                    </x-slot>
                </x-dropdown>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center p-1 rounded-full hover:bg-neutral-100 dark:hover:bg-zinc-800 transition-colors">
                        <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-xs">
                            {{ substr(Auth::user()?->name ?? 'U', 0, 2) }}
                        </div>
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

                <button @click="sidebarCollapsed = false; syncSidebarClass(); mobileMenuOpen = !mobileMenuOpen" class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-zinc-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Top Bar (Desktop) -->
        <header class="hidden md:flex bg-white dark:bg-zinc-900 border-b border-neutral-200 dark:border-zinc-800 h-16 items-center justify-between px-6 md:px-8 gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <button type="button"
                        @click="toggleSidebar()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-neutral-500 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-zinc-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color-mix(in_srgb,var(--primary-color)_35%,transparent)] transition-colors duration-150"
                        :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                        :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                        :aria-expanded="(!sidebarCollapsed).toString()">
                    <svg class="h-5 w-5 transition-transform duration-200 ease-out"
                         :class="sidebarCollapsed ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                    </svg>
                </button>
            </div>
            
            <div class="flex items-center gap-3 flex-shrink-0">
                {{-- Nav search --}}
                <div class="relative hidden sm:block">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-neutral-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="search"
                           x-model="navQuery"
                           @keydown.escape="navQuery = ''"
                           placeholder="Search menu..."
                           class="header-search w-52 lg:w-64 rounded-full pl-9 pr-3 py-2 text-sm">
                </div>

                {{-- Theme toggle --}}
                <button type="button"
                        @click="darkMode = !darkMode"
                        class="flex items-center gap-2 p-1.5 rounded-lg text-neutral-500 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-zinc-800"
                        :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
                    <span class="theme-switch" :class="darkMode ? 'is-dark' : 'is-light'">
                        <span class="theme-switch-knob">
                            <svg x-show="!darkMode" class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <svg x-show="darkMode" x-cloak class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </span>
                    </span>
                </button>

                <!-- Notification Dropdown -->
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="text-neutral-500 dark:text-neutral-400 hover:text-black dark:hover:text-white relative p-2 rounded-lg hover:bg-neutral-100 dark:hover:bg-zinc-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(Auth::user() && Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-zinc-900"></span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-white">
                            <span class="text-sm font-semibold text-gray-700">Notifications</span>
                            @if(Auth::user() && Auth::user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifications.markAllRead') }}" class="text-xs text-blue-600 hover:text-blue-800">Mark all read</a>
                            @endif
                        </div>

                        <div class="max-h-64 overflow-y-auto w-80 bg-white">
                            @if(Auth::user())
                                @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                    <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50">
                                        <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        No new notifications
                                    </div>
                                @endforelse
                            @endif
                        </div>

                        <div class="px-4 py-2 border-t border-gray-100 text-center bg-gray-50">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
                        </div>
                    </x-slot>
                </x-dropdown>

                <!-- User Profile Dropdown -->
                <div class="relative ml-1" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 p-1 rounded-full hover:bg-neutral-100 dark:hover:bg-zinc-800 transition-colors">
                        <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-xs">
                            {{ substr(Auth::user()?->name ?? 'U', 0, 2) }}
                        </div>
                        <span class="hidden lg:block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ Auth::user()?->name ?? 'User' }}</span>
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
        <main class="flex-1 min-h-0 overflow-y-auto bg-zinc-100 dark:bg-zinc-950">
            <div class="app-page min-h-full w-full px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </div>
        </main>
    </div>
    

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
            fetch("{{ route('notifications.poll') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (response.status === 401) return null; // Session expired
                    return response.json();
                })
                .then(data => {
                    if (data && data.id && data.id !== lastNotificationId) {
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
            fetch("{{ route('announcements.unreadCount') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (response.status === 401) return null; // Session expired
                    return response.json();
                })
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
    @stack('scripts')
</body>
</html>


