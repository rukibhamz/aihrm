<!-- Mobile Sidebar (Slide-over) -->
<div x-show="mobileMenuOpen"
     class="fixed inset-0 flex z-50 md:hidden"
     role="dialog"
     aria-modal="true"
     x-cloak>
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-neutral-900/40 dark:bg-black/60 backdrop-blur-[2px]"
         @click="mobileMenuOpen = false"
         aria-hidden="true"></div>

    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative flex-1 flex flex-col max-w-[17.5rem] w-full bg-white dark:bg-zinc-900 text-neutral-700 dark:text-zinc-200 border-r border-neutral-200 dark:border-zinc-800 shadow-2xl transition-colors">

        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button type="button" @click="mobileMenuOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full bg-white/10 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        @include('layouts.sidebar-content')
    </div>
    <div class="flex-shrink-0 w-14" aria-hidden="true"></div>
</div>

<!-- Static Sidebar for Desktop — width set via #app-sidebar-desktop CSS (FOUC-safe) -->
<div id="app-sidebar-desktop"
     class="hidden md:flex md:flex-col flex-shrink-0 h-full bg-white dark:bg-zinc-900 text-neutral-700 dark:text-zinc-200 border-r border-neutral-200 dark:border-zinc-800">
    @include('layouts.sidebar-content')
</div>
