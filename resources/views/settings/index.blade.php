<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Company Settings</h1>
        <p class="mt-1 text-sm text-neutral-500">Configure your company information and branding</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="list-disc list-inside text-sm text-red-800">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form -->
        <div class="lg:col-span-2">
            <div class="card p-8">
                <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Company Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Company Name *</label>
                                <input type="text" name="company_name" required value="{{ old('company_name', $settings['company_name']) }}"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
                                <input type="email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Phone</label>
                                <input type="text" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Address</label>
                                <textarea name="company_address" rows="3"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">{{ old('company_address', $settings['company_address']) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-lg font-semibold mb-4">Branding</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Company Logo</label>
                            @if($settings['company_logo'])
                                <div class="mb-3">
                                    <img src="{{ Storage::url($settings['company_logo']) }}" alt="Company Logo" class="h-16 border border-neutral-200 rounded">
                                </div>
                            @endif
                            <input type="file" name="company_logo" accept="image/*"
                                class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <p class="mt-1 text-xs text-neutral-500">Recommended: Square image, max 2MB</p>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-6 border-t border-neutral-200">
                        <button type="submit" class="btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold mb-3">About Settings</h3>
                <p class="text-sm text-neutral-600">
                    Configure your company information here. This will be displayed throughout the system and in communications.
                </p>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold mb-3">System Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Version</span>
                        <span class="font-medium">1.0.0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Laravel</span>
                        <span class="font-medium">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">PHP</span>
                        <span class="font-medium">{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
