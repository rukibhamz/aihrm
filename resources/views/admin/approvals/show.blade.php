<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('approvals.index') }}" class="text-sm font-medium text-neutral-500 hover:text-black mb-4 inline-block">&larr; Back to Inbox</a>
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Review Request</h1>
        <p class="mt-1 text-sm text-neutral-500">Authorization Level {{ $request->current_step_order }} for {{ class_basename($request->approvable_type) }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Data (Generic Display) -->
            <div class="card p-8 bg-white border-2 border-neutral-900 border-opacity-5">
                <h3 class="text-xl font-bold mb-6 text-neutral-900">Request Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($request->approvable->getAttributes() as $key => $value)
                        @if(!in_array($key, ['id', 'created_at', 'updated_at', 'user_id', 'status', 'approved_by']))
                            <div class="border-b border-neutral-100 pb-2">
                                <label class="block text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-1">{{ str_replace('_', ' ', $key) }}</label>
                                <span class="text-sm font-semibold text-neutral-800">{{ $value }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Approval Form -->
            <div class="card p-8 bg-neutral-900 text-white shadow-xl">
                <h3 class="text-xl font-bold mb-4">Your Decision</h3>
                <form action="{{ route('approvals.act', $request) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-2 opaity-80 text-neutral-300">Comments / Reason</label>
                        <textarea name="comments" rows="3" class="w-full px-4 py-3 bg-neutral-800 border border-neutral-700 rounded-lg focus:ring-2 focus:ring-white focus:border-white transition text-sm text-white" placeholder="Add a note..."></textarea>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <button type="submit" name="action" value="rejected" class="flex-1 px-6 py-4 bg-red-600 hover:bg-red-700 rounded-xl font-bold transition">
                            Reject Request
                        </button>
                        <button type="submit" name="action" value="approved" class="flex-1 px-6 py-4 bg-white text-neutral-900 hover:bg-neutral-200 rounded-xl font-bold transition">
                            Approve Level {{ $request->current_step_order }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar / History -->
        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-bold mb-4 text-neutral-900">Timeline</h3>
                <div class="space-y-6">
                    @forelse($request->logs as $log)
                        <div class="relative pl-6 border-l-2 {{ $log->action === 'approved' ? 'border-green-500' : 'border-red-500' }}">
                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $log->action === 'approved' ? 'bg-green-500' : 'bg-red-500' }} ring-4 ring-white"></div>
                            <div class="text-sm">
                                <span class="font-bold">{{ $log->user->name }}</span>
                                <span class="text-neutral-500">{{ $log->action }} this level</span>
                                <div class="text-xs text-neutral-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</div>
                                @if($log->comments)
                                    <p class="mt-2 text-xs italic text-neutral-600 bg-neutral-50 p-2 rounded">"{{ $log->comments }}"</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500 italic">No activity yet. This is the first step.</p>
                    @endforelse
                </div>
            </div>

            <div class="card p-6 border-dashed border-2">
                <h3 class="font-bold mb-3 text-neutral-900">Approver Chain</h3>
                <div class="space-y-3">
                    @foreach($request->chain->steps as $step)
                        <div class="flex items-center gap-3 {{ $step->step_order == $request->current_step_order ? 'opacity-100' : 'opacity-40' }}">
                            <div class="w-6 h-6 rounded-full {{ $step->step_order < $request->current_step_order ? 'bg-green-500 text-white' : ($step->step_order == $request->current_step_order ? 'bg-black text-white' : 'bg-neutral-200 text-neutral-500') }} flex items-center justify-center text-[10px] font-bold">
                                {{ $step->step_order }}
                            </div>
                            <span class="text-xs font-semibold">{{ str_replace('_', ' ', $step->approver_type) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
