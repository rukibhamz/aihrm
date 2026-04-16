<x-app-layout>
    <x-slot name="header">Tax Brackets Management</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Statutory Tax Brackets</h1>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn-primary">
            + Add Tax Bracket
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
                    <th class="text-left py-3 px-4 font-semibold text-sm">Bracket Name</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Min Salary</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Max Salary</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Tax Rate (%)</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Fixed Addition</th>
                    <th class="text-left py-3 px-4 font-semibold text-sm">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brackets as $bracket)
                    <tr class="border-b border-neutral-100 hover:bg-neutral-50">
                        <td class="py-3 px-4 font-medium">{{ $bracket->name }}</td>
                        <td class="py-3 px-4">${{ number_format($bracket->min_salary, 2) }}</td>
                        <td class="py-3 px-4">{{ $bracket->max_salary ? '$' . number_format($bracket->max_salary, 2) : 'And above' }}</td>
                        <td class="py-3 px-4 font-semibold">{{ $bracket->rate_percentage }}%</td>
                        <td class="py-3 px-4">${{ number_format($bracket->fixed_amount_addition, 2) }}</td>
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.tax-brackets.destroy', $bracket) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Delete this tax bracket? This will affect future payroll calculations.')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-neutral-500">No tax brackets configured. Payroll will run with zero standard tax unless fixed amounts are set on employee structures.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-primary/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">Add Tax Bracket</h2>
            <form action="{{ route('admin.tax-brackets.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Bracket Name</label>
                    <input type="text" name="name" required placeholder="e.g. Standard Rate" class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Salary</label>
                        <input type="number" name="min_salary" step="0.01" min="0" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Max Salary (Leave blank for Infinity)</label>
                        <input type="number" name="max_salary" step="0.01" min="0" class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Tax Rate (%)</label>
                        <input type="number" name="rate_percentage" step="0.01" min="0" max="100" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Fixed Addition</label>
                        <input type="number" name="fixed_amount_addition" step="0.01" min="0" value="0" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg">
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn-primary flex-1">Save Bracket</button>
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn-secondary flex-1">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

