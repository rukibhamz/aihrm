<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Audit Logs') }}
        </h2>
    </x-slot>

    <div class="mb-6">
        <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-black">
            Audit Logs
        </h2>
        <p class="text-sm text-gray-500">Track system activity and user actions.</p>
    </div>

<!-- Filters -->
<div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
    <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">User</label>
            <select name="user_id" class="w-full text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Event</label>
            <select name="event" class="w-full text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                <option value="">All Events</option>
                <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                <option value="restored" {{ request('event') == 'restored' ? 'selected' : '' }}>Restored</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Module (Model)</label>
            <input type="text" name="auditable_type" value="{{ request('auditable_type') }}" placeholder="e.g. Employee" class="w-full text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary w-full justify-center">Filter</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($audits as $audit)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($audit->user)
                            <div class="flex items-center">
                                <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold mr-2">
                                    {{ substr($audit->user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $audit->user->name }}</span>
                            </div>
                        @else
                            <span class="text-xs text-gray-400 italic">System / Guest</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $colors = [
                                'created' => 'bg-green-100 text-green-800',
                                'updated' => 'bg-blue-100 text-blue-800',
                                'deleted' => 'bg-red-100 text-red-800',
                                'restored' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $color = $colors[$audit->event] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                            {{ ucfirst($audit->event) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ class_basename($audit->auditable_type) }} <span class="text-xs text-gray-400">#{{ $audit->auditable_id }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                        {{ $audit->ip_address }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $audit->created_at->format('M d, Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.audit-logs.show', $audit->id) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No logs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $audits->withQueryString()->links() }}
</div>
</x-app-layout>
