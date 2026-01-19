<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Enable Two-Factor Authentication</h2>
        <p class="text-sm text-gray-600 mt-2">Scan the QR code below with your authenticator app to get started.</p>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <!-- QR Code -->
        <div class="flex justify-center mb-6">
            <div class="p-4 bg-white border-2 border-gray-200 rounded-lg">
                {!! $qrCode !!}
            </div>
        </div>

        <!-- Manual Entry -->
        <div class="mb-6">
            <p class="text-sm text-gray-600 mb-2">Can't scan? Enter this code manually:</p>
            <div class="bg-gray-50 p-3 rounded border border-gray-200">
                <code class="text-sm font-mono">{{ $secret }}</code>
            </div>
        </div>

        <!-- Verification Form -->
        <form method="POST" action="{{ route('two-factor.confirm') }}">
            @csrf

            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Enter the 6-digit code from your app
                </label>
                <input 
                    type="text" 
                    id="code" 
                    name="code" 
                    maxlength="6"
                    pattern="[0-9]{6}"
                    required
                    autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black text-center text-2xl tracking-widest font-mono"
                    placeholder="000000"
                >
                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">
                    Verify & Enable
                </button>
                <a href="{{ route('profile.edit') }}" class="btn-secondary flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Instructions -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-semibold text-sm text-blue-900 mb-2">📱 Recommended Apps:</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Google Authenticator</li>
                <li>• Microsoft Authenticator</li>
                <li>• Authy</li>
                <li>• 1Password</li>
            </ul>
        </div>
    </div>
</x-guest-layout>
