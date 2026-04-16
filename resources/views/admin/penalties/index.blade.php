<x-app-layout>
    <x-slot name="header">Penalties Management</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Employee Penalties</h1>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn-primary">
            + Add Penalty
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="card p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-neutral-200">
                    <th class="text-left py-3 px-4 font-semibold text-sm">Employee</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Reason</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Amount</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Month</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Added By</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penalties as $penalty)
                    <tr class="border-b border-neutral-100 hover:bg-neutral-50">
                        <td class="py-3 px-4">{{ $penalty->user->name }}</td>
                        <td class="py-3 px-4 font-medium text-red-600">{{ $penalty->reason }}</td>
                        <td class="py-3 px-4 font-semibold text-red-600">₦{{ number_format($penalty->amount, 2) }}</td>
                        <td class="py-3 px-4">{{ date('F Y', mktime(0, 0, 0, $penalty->month, 1, $penalty->year)) }}</td>
                        <td class="py-3 px-4 text-sm text-neutral-500">{{ $penalty->creator->name ?? 'System' }}</td>
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.penalties.destroy', $penalty) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Delete this penalty? If payroll is already run, you must regenerate it for this to take effect.')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-neutral-500">No penalties found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $penalties->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-primary/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">Add New Penalty</h2>
            <form action="{{ route('admin.penalties.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Employee</label>
                    <select name="user_id" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Reason</label>
                    <input type="text" name="reason" required placeholder="e.g. Late Arrival, Damage" class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" min="0.01" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Month</label>
                        <select name="month" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Year</label>
                        <input type="number" name="year" value="{{ date('Y') }}" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    </div>
                </div>
                
                <div class="bg-neutral-50 border border-neutral-200 p-3 rounded text-sm text-neutral-600 mt-2">
                    Note: This penalty will automatically be deducted from the employee's next payroll matching the selected month and year.
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn-primary flex-1">Add Penalty</button>
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn-secondary flex-1">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

