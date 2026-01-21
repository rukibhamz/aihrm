<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Announcements') }}
            </h2>
            @if($unreadCount > 0)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    {{ $unreadCount }} unread
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @forelse ($announcements as $announcement)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ !$announcement->isReadBy(Auth::user()) ? 'border-l-4 border-blue-500' : '' }}">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
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
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('announcements.show', $announcement) }}" class="hover:text-indigo-600">
                                            {{ $announcement->title }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 mt-2 line-clamp-2">
                                        {{ Str::limit(strip_tags($announcement->content), 200) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>By {{ $announcement->author?->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $announcement->published_at?->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('announcements.show', $announcement) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Read more →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No announcements yet</h3>
                            <p class="text-gray-500">Check back later for company updates and news.</p>
                        </div>
                    </div>
                @endforelse

                <div class="mt-4">
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
