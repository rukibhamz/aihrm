<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if(Auth::user()->unreadNotifications->count() > 0)
                <a href="{{ route('notifications.markAllRead') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Mark all as read
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse($notifications as $notification)
                        <div class="flex justify-between items-start py-4 border-b border-gray-100 last:border-0 {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50 -mx-6 px-6' }}">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['message'] ?? 'New Notification' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(!$notification->read_at)
                                <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="text-xs text-blue-600 hover:text-blue-800 ml-4">
                                    Mark as read
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            You have no notifications.
                        </div>
                    @endforelse

                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
