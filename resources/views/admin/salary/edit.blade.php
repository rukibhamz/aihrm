<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Edit Salary Structure</h1>
            <p class="mt-1 text-sm text-neutral-500">For {{ $user->name }} ({{ $user->employee->designation->title ?? 'Employee' }})</p>
        </div>
        <a href="{{ route('admin.salary.index') }}" class="btn-secondary">Back</a>
    </div>

    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.salary.update', $user) }}" class="card p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Base Earnings -->
            <div>
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400 api-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Earnings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Base Salary</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₦</span>
                            </div>
                            <input type="number" name="base_salary" value="{{ old('base_salary', $structure->base_salary) }}" class="focus:ring-black focus:border-black block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md py-2.5">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Housing Allowance</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₦</span>
                            </div>
                            <input type="number" name="housing_allowance" value="{{ old('housing_allowance', $structure->housing_allowance) }}" class="focus:ring-black focus:border-black block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md py-2.5">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Transport Allowance</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₦</span>
                            </div>
                            <input type="number" name="transport_allowance" value="{{ old('transport_allowance', $structure->transport_allowance) }}" class="focus:ring-black focus:border-black block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md py-2.5">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Other Allowances</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₦</span>
                            </div>
                            <input type="number" name="other_allowances" value="{{ old('other_allowances', $structure->other_allowances) }}" class="focus:ring-black focus:border-black block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md py-2.5">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Deductions -->
            <div>
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-red-600">
                    <svg class="w-5 h-5 api-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Deductions (Monthly)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Pension (Employee)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₦</span>
                            </div>
                            <input type="number" name="pension_employee" value="{{ old('pension_employee', $structure->pension_employee) }}" class="focus:ring-black focus:border-black block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md py-2.5">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">PAYE Tax</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₦</span>
                            </div>
                            <input type="number" name="tax_paye" value="{{ old('tax_paye', $structure->tax_paye) }}" class="focus:ring-black focus:border-black block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md py-2.5">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="btn-primary px-8">Save & Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
