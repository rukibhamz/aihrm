<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">My Bonuses</h1>
            <p class="mt-1 text-sm text-neutral-500">View your performance, sales, and end-of-year bonuses</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-md border border-green-200">
        {{ session('success') }}
    </div>
    @endif

    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Date Granted</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Period</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-100">
                @forelse ($bonuses as $bonus)
                <tr class="hover:bg-neutral-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                        {{ \Carbon\Carbon::parse($bonus->created_at)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $bonus->type === 'performance' ? 'bg-blue-100 text-blue-800' : 
                               ($bonus->type === 'sales' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800') }}">
                            {{ ucfirst(str_replace('-', ' ', $bonus->type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-neutral-600 max-w-xs truncate">
                        {{ $bonus->description ?: 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-600">
                        {{ date('M', mktime(0, 0, 0, $bonus->month, 10)) }} {{ $bonus->year }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-neutral-900 border-l border-neutral-100">
                        ₦{{ number_format($bonus->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-neutral-500">
                        <svg class="mx-auto h-12 w-12 text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        No bonuses found in your history.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $bonuses->links() }}
    </div>
</x-app-layout>
