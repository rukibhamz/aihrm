<x-app-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 overflow-hidden">
             <div class="p-6 border-b border-neutral-100 bg-neutral-50 flex justify-between items-center">
                <h2 class="text-xl font-bold text-neutral-900">Resignation Status</h2>
                <span class="px-3 py-1 text-sm font-bold rounded-full uppercase tracking-wide
                    {{ $resignation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $resignation->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $resignation->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $resignation->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                    {{ ucfirst($resignation->status) }}
                </span>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Submitted On</span>
                        <span class="text-base font-medium text-neutral-900">{{ $resignation->resignation_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Last Working Day</span>
                        <span class="text-base font-medium text-neutral-900">{{ $resignation->last_working_day->format('M d, Y') }}</span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Reason Provided</span>
                    <p class="text-neutral-700 bg-neutral-50 p-4 rounded-lg border border-neutral-100">
                        {{ $resignation->reason }}
                    </p>
                </div>

                @if($resignation->hr_comments)
                <div>
                    <span class="block text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">HR Comments</span>
                    <p class="text-neutral-700 bg-blue-50 p-4 rounded-lg border border-blue-100">
                        {{ $resignation->hr_comments }}
                    </p>
                </div>
                @endif
                
                @if($resignation->status === 'approved')
                <div class="bg-green-50 border border-green-100 p-4 rounded-lg">
                    <h4 class="font-bold text-green-900 mb-2">Next Steps</h4>
                    <ul class="list-disc list-inside text-sm text-green-800 space-y-1">
                        <li>Ensure all assigned assets are returned before your last working day.</li>
                        <li>Complete the exit interview with HR if scheduled.</li>
                        <li>Hand over ongoing tasks to your manager.</li>
                    </ul>
                </div>
                @endif
            </div>
            
            <div class="bg-neutral-50 px-6 py-4 border-t border-neutral-100 text-center text-xs text-neutral-500">
                Resignation Reference #RES-{{ $resignation->id }}
            </div>
        </div>
    </div>
</x-app-layout>
