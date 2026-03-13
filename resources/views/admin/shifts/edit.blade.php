<x-app-layout>
    <div class="mb-8 font-primary">
        <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Edit Shift: {{ $shift->name }}</h1>
        <p class="mt-1 text-sm text-neutral-500">Update working hours and late-arrival rules</p>
    </div>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-neutral-100 p-8">
        <form action="{{ route('admin.shifts.update', $shift) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-neutral-700">Shift Name</label>
                <input type="text" name="name" value="{{ old('name', $shift->name) }}" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition text-sm">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-neutral-700">Start Time</label>
                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition text-sm">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-neutral-700">End Time</label>
                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition text-sm">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-neutral-700">Grace Period (Minutes)</label>
                <div class="relative">
                    <input type="number" name="grace_period_minutes" value="{{ old('grace_period_minutes', $shift->grace_period_minutes) }}" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition text-sm">
                    <span class="absolute right-4 top-2.5 text-xs text-neutral-400 font-medium">mins</span>
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                <input type="hidden" name="is_default" value="0">
                <input type="checkbox" name="is_default" value="1" {{ $shift->is_default ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-neutral-300 rounded focus:ring-indigo-500">
                <label class="text-sm font-medium text-neutral-700">Set as default shift for new employees</label>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="btn-primary">Update Shift</button>
                <a href="{{ route('admin.shifts.index') }}" class="text-sm font-medium text-neutral-500 hover:text-neutral-700">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
