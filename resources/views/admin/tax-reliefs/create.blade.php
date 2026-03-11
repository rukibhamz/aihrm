<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.tax-reliefs.index') }}" class="text-neutral-500 hover:text-neutral-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Create Tax Relief</h1>
            </div>
            <p class="text-sm text-neutral-500 ml-8">Configure a new tax relief policy to apply to employees.</p>
        </div>
    </div>

    <div class="max-w-4xl">
        <div class="card p-6">
            <form action="{{ route('admin.tax-reliefs.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-form.label for="name" value="Relief Name" required />
                        <x-form.input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Housing Allowance" />
                        <x-form.error for="name" />
                    </div>

                    <div>
                        <x-form.label for="type" value="Relief Type" required />
                        <select id="type" name="type" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="fixed_amount" {{ old('type') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount (₦)</option>
                            <option value="percentage_of_gross" {{ old('type') == 'percentage_of_gross' ? 'selected' : '' }}>Percentage of Gross Pay (%)</option>
                        </select>
                        <x-form.error for="type" />
                    </div>

                    <div>
                        <x-form.label for="amount" value="Amount/Percentage" required />
                        <x-form.input id="amount" name="amount" type="number" step="0.01" value="{{ old('amount') }}" required placeholder="0.00" />
                        <x-form.error for="amount" />
                    </div>

                    <div>
                        <x-form.label for="is_active" value="Status" />
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" class="rounded border-neutral-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" checked>
                                <span class="ml-2 text-sm text-neutral-600">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <x-form.label for="description" value="Description" />
                    <textarea id="description" name="description" rows="3" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Optional details about this tax relief.">{{ old('description') }}</textarea>
                    <x-form.error for="description" />
                </div>

                <div class="mb-8">
                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 border-b pb-2 mb-2 border-neutral-200">Assign to Employees</h3>
                            <p class="text-sm text-neutral-500 pt-1">Select the employees who should receive this tax relief.</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 border border-neutral-200 rounded-lg max-h-96 overflow-y-auto p-4 bg-neutral-50">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($users as $user)
                                <label class="flex items-start space-x-3 p-3 bg-white border border-neutral-200 rounded-md cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-neutral-300 rounded" {{ (is_array(old('user_ids')) && in_array($user->id, old('user_ids'))) ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-neutral-900">{{ $user->name }}</span>
                                        <span class="text-xs text-neutral-500">{{ $user->employee->designation->title ?? 'Employee' }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @if($users->isEmpty())
                            <p class="text-center text-neutral-500 text-sm py-4">No active employees found.</p>
                        @endif
                    </div>
                    <x-form.error for="user_ids" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <a href="{{ route('admin.tax-reliefs.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Create Tax Relief</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
