<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Edit Asset') }}
        </h2>
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.assets.index') }}" class="text-sm text-gray-500 hover:text-black flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Assets
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Edit Asset: {{ $asset->name }}</h2>
    </div>

<div class="card max-w-4xl mx-auto">
    <form action="{{ route('admin.assets.update', $asset) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Basic Info -->
            <div class="col-span-full">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Basic Information</h3>
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Asset Name *</label>
                <input type="text" name="name" value="{{ old('name', $asset->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                @error('serial_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                    <option value="hardware" {{ old('type', $asset->type) == 'hardware' ? 'selected' : '' }}>Hardware</option>
                    <option value="software" {{ old('type', $asset->type) == 'software' ? 'selected' : '' }}>Software</option>
                    <option value="license" {{ old('type', $asset->type) == 'license' ? 'selected' : '' }}>License</option>
                    <option value="furniture" {{ old('type', $asset->type) == 'furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="other" {{ old('type', $asset->type) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                    <option value="available" {{ old('status', $asset->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="assigned" {{ old('status', $asset->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="retired" {{ old('status', $asset->status) == 'retired' ? 'selected' : '' }}>Retired</option>
                    <option value="lost" {{ old('status', $asset->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>

            <!-- Financials -->
            <div class="col-span-full mt-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Purchase & Financials</h3>
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Cost</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" step="0.01" name="purchase_cost" value="{{ old('purchase_cost', $asset->purchase_cost) }}" class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                </div>
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Warranty Expiry</label>
                <input type="date" name="warranty_expiry" value="{{ old('warranty_expiry', $asset->warranty_expiry?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                    <option value="">-- No Assignment --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $asset->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Status will auto-update to 'Assigned' if a user is selected.</p>
            </div>

            <!-- Notes -->
            <div class="col-span-full">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">{{ old('notes', $asset->notes) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.assets.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Asset</button>
        </div>
    </form>
</div>
</x-app-layout>
