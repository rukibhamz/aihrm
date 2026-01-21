<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Notification Preferences</h1>
        <p class="mt-1 text-sm text-neutral-500">Manage how you receive notifications.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card overflow-hidden max-w-3xl">
        <form action="{{ route('notifications.preferences.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notification Type</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">In-App</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($settings as $type => $config)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $config['label'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="preferences[{{ $type }}][email]" class="form-checkbox h-5 w-5 text-black rounded border-gray-300 focus:ring-black" {{ $config['email'] ? 'checked' : '' }}>
                                </label>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="preferences[{{ $type }}][database]" class="form-checkbox h-5 w-5 text-black rounded border-gray-300 focus:ring-black" {{ $config['database'] ? 'checked' : '' }}>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="submit" class="btn-primary">Save Preferences</button>
            </div>
        </form>
    </div>
</x-app-layout>
