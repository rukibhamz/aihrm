<x-app-layout>
    <x-slot name="header">Audit Logs</x-slot>

    <div class="mb-6">
        <h1 class="text-2xl font-bold">System Audit Trail</h1>
        <p class="text-sm text-gray-600 mt-1">Track all changes made to critical system data</p>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2">User</label>
                <select name="user_id" class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Model</label>
                <select name="auditable_type" class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    <option value="">All Models</option>
                    @foreach($models as $model)
                        <option value="{{ $model['value'] }}" {{ request('auditable_type') == $model['value'] ? 'selected' : '' }}>
                            {{ $model['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Event</label>
                <select name="event" class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    <option value="">All Events</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
            </div>

            <div class="md:col-span-5 flex gap-3">
                <button type="submit" class="btn-primary">Apply Filters</button>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="card p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-neutral-200">
                    <th class="text-left py-3 px-4 font-semibold text-sm">Timestamp</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">User</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Event</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Model</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Changes</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits as $audit)
                    <tr class="border-b border-neutral-100 hover:bg-neutral-50">
                        <td class="py-3 px-4 text-sm">
                            {{ $audit->created_at->format('M d, Y H:i:s') }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ $audit->user?->name ?? 'System' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $audit->event === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $audit->event === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $audit->event === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucfirst($audit->event) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ class_basename($audit->auditable_type) }}
                            <span class="text-gray-500">#{{ $audit->auditable_id }}</span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            @if($audit->event === 'created')
                                <span class="text-green-600">{{ count($audit->new_values) }} fields</span>
                            @elseif($audit->event === 'updated')
                                <span class="text-blue-600">{{ count($audit->old_values) }} fields changed</span>
                            @else
                                <span class="text-red-600">Record deleted</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">
                            {{ $audit->ip_address }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-neutral-500">No audit logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $audits->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
