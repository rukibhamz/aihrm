<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Assign Salary Structure</h1>
        <p class="mt-1 text-sm text-neutral-500">Set up compensation for an employee</p>
    </div>

    <div class="card p-8 max-w-3xl">
        <form method="POST" action="{{ route('admin.salary.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Employee *</label>
                <select name="user_id" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->user_id }}">{{ $emp->user->name }} ({{ $emp->designation->title ?? 'N/A' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <h3 class="text-sm font-semibold text-neutral-900 mb-3 uppercase tracking-wider">Earnings</h3>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Base Salary *</label>
                    <input type="number" step="0.01" name="base_salary" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Housing Allowance</label>
                    <input type="number" step="0.01" name="housing_allowance" value="0" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Transport Allowance</label>
                    <input type="number" step="0.01" name="transport_allowance" value="0" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Other Allowances</label>
                    <input type="number" step="0.01" name="other_allowances" value="0" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>

                <div class="md:col-span-2 pt-4 border-t border-neutral-100">
                    <h3 class="text-sm font-semibold text-neutral-900 mb-3 uppercase tracking-wider">Deductions</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Pension (Employee)</label>
                    <input type="number" step="0.01" name="pension_employee" value="0" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Tax (PAYE)</label>
                    <input type="number" step="0.01" name="tax_paye" value="0" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>
            </div>

            <div class="flex gap-3 pt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary">Save Structure</button>
                <a href="{{ route('admin.salary.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
