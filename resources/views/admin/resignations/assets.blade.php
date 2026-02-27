<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Asset Handover Check</h1>
            <p class="mt-1 text-sm text-neutral-500">Resignation #{{ $resignation->id }} &middot; {{ $resignation->user->name ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('admin.resignations.show', $resignation) }}" class="btn-secondary text-sm">← Back to Resignation</a>
    </div>

    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Employee Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <h2 class="text-lg font-bold text-neutral-900 mb-4">Employee Information</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Name</dt>
                    <dd class="mt-1 text-sm font-medium text-neutral-900">{{ $resignation->user->name ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Designation</dt>
                    <dd class="mt-1 text-sm font-medium text-neutral-900">{{ $resignation->user->employee->designation->title ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Resignation Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $resignation->status === 'approved' ? 'bg-green-100 text-green-800' : ($resignation->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($resignation->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Assets List --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold text-neutral-900">Assigned Assets</h2>
                <p class="mt-1 text-sm text-neutral-500">All assets must be returned or accounted for before offboarding completion.</p>
            </div>

            @if($assets->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Asset</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Serial / ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Condition</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($assets as $asset)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">{{ $asset->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">{{ $asset->category ?? 'General' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 font-mono">{{ $asset->serial_number ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">{{ ucfirst($asset->condition ?? 'good') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($asset->status ?? 'assigned') === 'returned' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($asset->status ?? 'assigned') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-neutral-50 border-t border-neutral-200">
                <div class="flex items-center gap-2 text-sm text-neutral-600">
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><strong>{{ $assets->count() }}</strong> asset(s) assigned to this employee.</span>
                </div>
            </div>
            @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <h3 class="mt-2 text-sm font-medium text-neutral-900">No Assets Assigned</h3>
                <p class="mt-1 text-sm text-neutral-500">This employee has no recorded assets to return.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
