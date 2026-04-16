<x-app-layout>
    <div class="mb-8 text-center sm:text-left">
        <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Add New Office Location</h1>
        <p class="mt-1 text-sm text-neutral-500">Define the geographic boundaries for employee clock-ins</p>
    </div>

    <div class="max-w-3xl mx-auto sm:mx-0">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-sm border border-neutral-100 p-8">
                <form action="{{ route('admin.locations.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-neutral-700">Location Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-primary transition text-sm" placeholder="e.g. Headquarters, Lagos Office">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Latitude</label>
                            <input type="number" step="any" name="latitude" id="lat" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-primary transition text-sm font-mono" placeholder="6.5244">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Longitude</label>
                            <input type="number" step="any" name="longitude" id="lng" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-primary transition text-sm font-mono" placeholder="3.3792">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-neutral-700">Radius (Meters)</label>
                        <div class="relative">
                            <input type="number" name="radius_meters" value="100" required class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-primary transition text-sm" placeholder="100">
                            <span class="absolute right-4 top-2.5 text-xs text-neutral-400 font-medium">meters</span>
                        </div>
                        <p class="text-[11px] text-neutral-400 italic">Employees must be within this distance to clock in.</p>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                        <input type="hidden" name="is_default" value="0">
                        <input type="checkbox" name="is_default" value="1" class="w-4 h-4 text-primary border-neutral-300 rounded focus:ring-primary">
                        <label class="text-sm font-medium text-neutral-700">Set as primary office location</label>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="btn-primary flex-1 sm:flex-none">Save Location</button>
                        <a href="{{ route('admin.locations.index') }}" class="text-sm font-medium text-neutral-500 hover:text-neutral-700">Cancel</a>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="bg-primary rounded-xl p-8 text-white shadow-lg">
                    <h3 class="font-bold text-lg mb-4">Location Helper</h3>
                    <p class="text-sm text-indigo-100 mb-6">Need the coordinates of your current location to set up a new boundary?</p>
                    <button type="button" onclick="getCurrentLocation()" class="w-full py-3 bg-white text-primary font-bold rounded-lg hover:bg-neutral-50 transition shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Get My Current Location
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;
                }, (error) => {
                    alert('Error getting location: ' + error.message);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
    </script>
</x-app-layout>

