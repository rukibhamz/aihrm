<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('announcements.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $announcement->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    {{-- Meta info --}}
                    <div class="flex items-center gap-2 mb-6">
                        @if($announcement->pinned)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a.75.75 0 01.75.75v.01l-.001 6.073 4.378-2.528a.75.75 0 01.75 1.3l-4.378 2.528 2.706 4.687a.75.75 0 01-1.3.75L10 10.874l-2.905 4.687a.75.75 0 01-1.3-.75l2.706-4.687L4.123 7.596a.75.75 0 01.75-1.3l4.378 2.528V2.75A.75.75 0 0110 2z"/>
                                </svg>
                                Pinned
                            </span>
                        @endif
                        @if($announcement->type === 'department')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $announcement->department?->name }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                All Employees
                            </span>
                        @endif
                    </div>

                    {{-- Author and date --}}
                    <div class="flex items-center mb-6 pb-6 border-b border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold text-sm">
                                    {{ strtoupper(substr($announcement->author?->name ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $announcement->author?->name ?? 'Admin' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Published {{ $announcement->published_at?->format('F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="prose max-w-none">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>
            </div>

            {{-- Back button --}}
            <div class="mt-6">
                <a href="{{ route('announcements.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to all announcements
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
