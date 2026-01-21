<div class="card p-6 h-full flex flex-col">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <h3 class="font-semibold text-gray-900">Announcements</h3>
            @if($unreadAnnouncementCount > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $unreadAnnouncementCount }} new
                </span>
            @endif
        </div>
        <a href="{{ route('announcements.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all →</a>
    </div>

    @if($latestAnnouncements->count() > 0)
        <div class="space-y-3">
            @foreach($latestAnnouncements as $announcement)
                <a href="{{ route('announcements.show', $announcement) }}" 
                   class="block p-3 rounded-lg border {{ !$announcement->isReadBy(Auth::user()) ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-100' }} hover:bg-gray-100 transition-colors">
                    <div class="flex items-start gap-2">
                        @if($announcement->pinned)
                            <span class="flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a.75.75 0 01.75.75v.01l-.001 6.073 4.378-2.528a.75.75 0 01.75 1.3l-4.378 2.528 2.706 4.687a.75.75 0 01-1.3.75L10 10.874l-2.905 4.687a.75.75 0 01-1.3-.75l2.706-4.687L4.123 7.596a.75.75 0 01.75-1.3l4.378 2.528V2.75A.75.75 0 0110 2z"/>
                                </svg>
                            </span>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $announcement->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $announcement->published_at?->diffForHumans() }}</p>
                        </div>
                        @if(!$announcement->isReadBy(Auth::user()))
                            <span class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-6 text-gray-500">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <p class="text-sm">No announcements yet</p>
        </div>
    @endif
</div>
