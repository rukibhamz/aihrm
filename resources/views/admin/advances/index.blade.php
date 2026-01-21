<x-app-layout>
    <x-slot name="header">Salary Advances</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Employee Salary Advances</h1>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn-primary">
            + Add Advance
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
                    <th class="text-left py-3 px-4 font-semibold text-sm">Amount</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Deduct From</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Status</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advances as $advance)
                    <tr class="border-b border-neutral-100 hover:bg-neutral-50">
                        <td class="py-3 px-4">{{ $advance->user->name }}</td>
                        <td class="py-3 px-4 font-semibold">${{ number_format($advance->amount, 2) }}</td>
                        <td class="py-3 px-4">{{ date('F Y', mktime(0, 0, 0, $advance->deduct_from_month, 1, $advance->deduct_from_year)) }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 {{ $advance->is_deducted ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800' }} rounded text-xs font-medium">
                                {{ $advance->is_deducted ? 'Deducted' : 'Pending' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if(!$advance->is_deducted)
                                <form action="{{ route('admin.advances.destroy', $advance) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Delete this advance?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-neutral-500">No salary advances found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $advances->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">Add Salary Advance</h2>
            <form action="{{ route('admin.advances.store') }}" method="POST" class="space-y-4">
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
                    <label class="block text-sm font-medium mb-2">Advance Amount</label>
                    <input type="number" name="amount" step="0.01" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Deduct From Month</label>
                        <select name="deduct_from_month" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Year</label>
                        <input type="number" name="deduct_from_year" value="{{ date('Y') }}" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Reason</label>
                    <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-neutral-300 rounded-lg"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn-primary flex-1">Add Advance</button>
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn-secondary flex-1">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
