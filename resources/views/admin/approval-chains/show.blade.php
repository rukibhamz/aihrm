<x-app-layout>
    <div class="mb-8 flex justify-between items-start">
        <div>
            <a href="{{ route('admin.approval-chains.index') }}" class="text-sm font-medium text-neutral-500 hover:text-black mb-4 inline-block">&larr; Back to Workflows</a>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">{{ $approvalChain->name }}</h1>
            <p class="mt-1 text-sm text-neutral-500">Configure level-by-level approvals for <strong>{{ class_basename($approvalChain->module_type) }}</strong></p>
        </div>
        <div class="flex gap-3">
             <!-- Modal Trigger (Simple Modal logic or just a separate form) -->
             <button onclick="document.getElementById('step-modal').classList.remove('hidden')" class="btn-primary">
                Add Approval Level
             </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Steps List -->
        <div class="lg:col-span-2 space-y-4">
            <h3 class="text-lg font-bold text-neutral-900 mb-4">Approval Sequence</h3>
            
            @forelse($approvalChain->steps as $step)
                <div class="card p-6 border-l-4 border-black relative group">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-neutral-900 text-white rounded-full flex items-center justify-center font-bold">
                                {{ $step->step_order }}
                            </div>
                            <div>
                                <h4 class="font-bold text-neutral-900">
                                    @if($step->approver_type === 'role')
                                        Role: {{ $roles->find($step->approver_id)->name ?? 'Unknown' }}
                                    @elseif($step->approver_type === 'specific_user')
                                        User: {{ $users->find($step->approver_id)->name ?? 'Unknown' }}
                                    @else
                                        Line Manager
                                    @endif
                                </h4>
                                <p class="text-xs text-neutral-500 uppercase tracking-wider font-semibold">{{ str_replace('_', ' ', $step->approver_type) }}</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.approval-chains.steps.destroy', $step) }}" method="POST" onsubmit="return confirm('Remove this step?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-neutral-400 hover:text-red-600 transition opacity-0 group-hover:opacity-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    @if($step->min_amount > 0 || $step->max_amount > 0)
                        <div class="mt-4 pt-4 border-t border-dotted border-neutral-200 flex gap-4">
                            @if($step->min_amount > 0)
                                <div class="text-xs px-2 py-1 bg-neutral-50 rounded">Min: ₦{{ number_format($step->min_amount, 2) }}</div>
                            @endif
                            @if($step->max_amount > 0)
                                <div class="text-xs px-2 py-1 bg-neutral-50 rounded">Max: ₦{{ number_format($step->max_amount, 2) }}</div>
                            @endif
                        </div>
                    @endif
                </div>
                
                @if(!$loop->last)
                    <div class="flex justify-center -my-2">
                        <svg class="h-6 w-6 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                @endif
            @empty
                <div class="card p-12 text-center border-dashed border-2">
                    <p class="text-neutral-500">No approval levels defined yet. This request will be auto-approved currently.</p>
                </div>
            @endforelse
        </div>

        <!-- Sidebar / Details -->
        <div class="space-y-6">
            <div class="card p-6 bg-neutral-50">
                <h3 class="font-bold mb-4">Chain Details</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Module:</span>
                        <span class="font-medium">{{ class_basename($approvalChain->module_type) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Levels:</span>
                        <span class="font-medium">{{ $approvalChain->steps->count() }}</span>
                    </div>
                    <div class="pt-3 border-t border-neutral-200">
                        <span class="text-neutral-500 block mb-1">Description:</span>
                        <p class="text-neutral-700 leading-relaxed">{{ $approvalChain->description ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Step Modal -->
    <div id="step-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add Approval Level</h3>
                <button onclick="document.getElementById('step-modal').classList.add('hidden')" class="text-neutral-400 hover:text-black">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.approval-chains.steps.store', $approvalChain) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="step_order" value="{{ $approvalChain->steps->count() + 1 }}">

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Approver Type</label>
                    <select name="approver_type" id="approver_type" required onchange="toggleApproverSource(this.value)"
                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm bg-neutral-50 focus:bg-white transition">
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="role-select" class="approver-source">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Select Role</label>
                    <select name="approver_id" class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="user-select" class="approver-source hidden">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Select Specific User</label>
                    <select name="approver_id_user" class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-4 border-neutral-100">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Min Amount (Optional)</label>
                        <input type="number" step="0.01" name="min_amount" placeholder="0.00"
                            class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Max Amount (Optional)</label>
                        <input type="number" step="0.01" name="max_amount" placeholder="0.00"
                            class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="document.getElementById('step-modal').classList.add('hidden')" class="flex-1 btn-secondary py-3">Cancel</button>
                    <button type="submit" class="flex-1 btn-primary py-3">Add Level</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleApproverSource(type) {
            const roleEl = document.getElementById('role-select');
            const userEl = document.getElementById('user-select');
            const roleSelect = roleEl.querySelector('select');
            const userSelect = userEl.querySelector('select');

            roleEl.classList.add('hidden');
            userEl.classList.add('hidden');
            roleSelect.name = 'ignore_role';
            userSelect.name = 'ignore_user';

            if (type === 'role') {
                roleEl.classList.remove('hidden');
                roleSelect.name = 'approver_id';
            } else if (type === 'specific_user') {
                userEl.classList.remove('hidden');
                userSelect.name = 'approver_id';
            }
        }
        
        // Initialize
        toggleApproverSource(document.getElementById('approver_type').value);
    </script>
</x-app-layout>
