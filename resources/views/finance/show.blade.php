<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Financial Request #{{ $financialRequest->id }}</h1>
            <p class="mt-1 text-sm text-neutral-500">Submitted {{ $financialRequest->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        <a href="{{ route('finance.index') }}" class="btn-secondary text-sm">← Back to Requests</a>
    </div>

    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Status Banner --}}
        <div class="rounded-xl p-4 flex items-center gap-3
            {{ $financialRequest->status === 'paid' ? 'bg-green-50 border border-green-200' :
               ($financialRequest->status === 'rejected' ? 'bg-red-50 border border-red-200' :
               ($financialRequest->status === 'approved_by_manager' || $financialRequest->status === 'approved_by_finance' ? 'bg-blue-50 border border-blue-200' :
               'bg-yellow-50 border border-yellow-200')) }}">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                {{ $financialRequest->status === 'paid' ? 'bg-green-100 text-green-600' :
                   ($financialRequest->status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                @if($financialRequest->status === 'paid') ✓
                @elseif($financialRequest->status === 'rejected') ✕
                @else ⏳
                @endif
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-neutral-400">Status</div>
                <div class="text-sm font-bold text-neutral-900">{{ ucfirst(str_replace('_', ' ', $financialRequest->status)) }}</div>
            </div>
        </div>

        {{-- Request Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 divide-y divide-neutral-100">
            <div class="p-6">
                <h2 class="text-lg font-bold text-neutral-900 mb-4">Request Details</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Category</dt>
                        <dd class="mt-1 text-sm font-medium text-neutral-900">{{ $financialRequest->category->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Amount</dt>
                        <dd class="mt-1 text-2xl font-black text-neutral-900">₦{{ number_format($financialRequest->amount, 2) }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Description</dt>
                        <dd class="mt-1 text-sm text-neutral-700 leading-relaxed">{{ $financialRequest->description }}</dd>
                    </div>
                </dl>
            </div>

            @if($financialRequest->attachment_path)
            <div class="p-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-3">Attachment</h3>
                <a href="{{ asset('storage/' . $financialRequest->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Attachment
                </a>
            </div>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <h2 class="text-lg font-bold text-neutral-900 mb-4">Timeline</h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 mt-2 rounded-full bg-neutral-300"></div>
                    <div>
                        <div class="text-sm font-medium text-neutral-900">Request Submitted</div>
                        <div class="text-xs text-neutral-500">{{ $financialRequest->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @if($financialRequest->manager_approved_at ?? false)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 mt-2 rounded-full bg-blue-400"></div>
                    <div>
                        <div class="text-sm font-medium text-neutral-900">Manager Approved</div>
                        <div class="text-xs text-neutral-500">{{ $financialRequest->manager_approved_at }}</div>
                    </div>
                </div>
                @endif
                @if($financialRequest->status === 'paid')
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 mt-2 rounded-full bg-green-400"></div>
                    <div>
                        <div class="text-sm font-medium text-neutral-900">Marked as Paid</div>
                        <div class="text-xs text-neutral-500">{{ $financialRequest->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
