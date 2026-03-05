<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Pending Approvals</h1>
        <p class="mt-1 text-sm text-neutral-500">Review and act on requests requiring your authorization</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-neutral-50 border-b border-neutral-200">
                        <th class="px-6 py-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Request Type</th>
                        <th class="px-6 py-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Current Level</th>
                        <th class="px-6 py-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Date Submitted</th>
                        <th class="px-6 py-4 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($pendingRequests as $request)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                    {{ class_basename($request->approvable_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-neutral-200 rounded-full flex items-center justify-center text-xs font-bold font-neutral-600">
                                        {{ substr($request->approvable->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-neutral-900">{{ $request->approvable->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-neutral-600">Level {{ $request->current_step_order }}</span>
                                    <span class="text-xs text-neutral-400">/ {{ $request->chain->steps->count() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-500">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('approvals.show', $request) }}" class="text-sm font-bold text-black hover:underline">
                                    Review &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-neutral-500 italic">
                                No pending approvals found in your inbox.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
