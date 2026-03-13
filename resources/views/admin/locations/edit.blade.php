<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Edit Location: {{ $location->name }}</h1>
        <p class="mt-1 text-sm text-neutral-500">Update geographic boundaries for geofencing</p>
    </div>

    <div class="max-w-3xl bg-white rounded-xl shadow-sm border border-neutral-100 p-8">
        <form action="{{ route('admin.locations.update', $location) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-neutral-700">Location Name</label>
                <input type="text" name="name" value="{{ old('name', $location->name) }}" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-neutral-700">Latitude</label>
                    <input type="number" step="any" name="latitude" id="lat" value="{{ old('latitude', $location->latitude) }}" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition text-sm font-mono">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-neutral-700">Longitude</label>
                    <input type="number" step="any" name="longitude" id="lng" value="{{ old('longitude', $location->longitude) }}" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition text-sm font-mono">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-neutral-700">Radius (Meters)</label>
                <div class="relative">
                    <input type="number" name="radius_meters" value="{{ old('radius_meters', $location->radius_meters) }}" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition text-sm">
                    <span class="absolute right-4 top-2.5 text-xs text-neutral-400 font-medium">meters</span>
                </div>
            </div>

            <div class="flex items-center gap-3 p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                <input type="hidden" name="is_default" value="0">
                <input type="checkbox" name="is_default" value="1" {{ $location->is_default ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-neutral-300 rounded focus:ring-indigo-500">
                <label class="text-sm font-medium text-neutral-700">Set as primary office location</label>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="btn-primary">Update Location</button>
                <a href="{{ route('admin.locations.index') }}" class="text-sm font-medium text-neutral-500 hover:text-neutral-700">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
