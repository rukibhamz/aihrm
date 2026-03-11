<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.overtime-policies.index') }}" class="text-neutral-500 hover:text-neutral-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Create Overtime Policy</h1>
            </div>
            <p class="text-sm text-neutral-500 ml-8">Define how overtime hours are converted to pay.</p>
        </div>
    </div>

    <div class="max-w-3xl">
        <div class="card p-6">
            <form action="{{ route('admin.overtime-policies.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <x-form.label for="name" value="Policy Name" required />
                    <x-form.input id="name" name="name" type="text" value="{{ old('name') }}" required placeholder="e.g. Standard Overtime Rule" />
                    <x-form.error for="name" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-form.label for="standard_daily_hours" value="Standard Daily Hours" required />
                        <x-form.input id="standard_daily_hours" name="standard_daily_hours" type="number" step="0.5" min="0" max="24" value="{{ old('standard_daily_hours', '8') }}" required />
                        <p class="text-xs text-neutral-500 mt-1">Hours above this limit on a normal day are considered overtime.</p>
                        <x-form.error for="standard_daily_hours" />
                    </div>
                </div>

                <div class="border-t border-neutral-200 pt-6 mb-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Payout Multipliers</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-form.label for="weekday_multiplier" value="Weekday Multiplier" required />
                            <x-form.input id="weekday_multiplier" name="weekday_multiplier" type="number" step="0.1" min="1" value="{{ old('weekday_multiplier', '1.5') }}" required />
                            <p class="text-xs text-neutral-500 mt-1">Multiplier for extra hours on standard work days (e.g. 1.5x).</p>
                            <x-form.error for="weekday_multiplier" />
                        </div>

                        <div>
                            <x-form.label for="weekend_multiplier" value="Weekend Multiplier" required />
                            <x-form.input id="weekend_multiplier" name="weekend_multiplier" type="number" step="0.1" min="1" value="{{ old('weekend_multiplier', '2.0') }}" required />
                            <p class="text-xs text-neutral-500 mt-1">Multiplier for hours worked on weekends (e.g. 2.0x).</p>
                            <x-form.error for="weekend_multiplier" />
                        </div>

                        <div>
                            <x-form.label for="holiday_multiplier" value="Holiday Multiplier" required />
                            <x-form.input id="holiday_multiplier" name="holiday_multiplier" type="number" step="0.1" min="1" value="{{ old('holiday_multiplier', '2.0') }}" required />
                            <p class="text-xs text-neutral-500 mt-1">Multiplier for hours worked on public holidays.</p>
                            <x-form.error for="holiday_multiplier" />
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <x-form.label for="is_active" value="Set as Active Policy" />
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" class="rounded border-neutral-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" checked>
                            <span class="ml-2 text-sm text-neutral-600">Make this the active organization-wide policy. (This will deactivate any currently active policy).</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <a href="{{ route('admin.overtime-policies.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Create Policy</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
