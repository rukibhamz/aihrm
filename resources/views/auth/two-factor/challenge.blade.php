<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Two-Factor Authentication</h2>
        <p class="text-sm text-gray-600 mt-2">Enter the 6-digit code from your authenticator app.</p>
    </div>

    <form method="POST" action="{{ route('two-factor.challenge') }}" x-data="{ useRecovery: false }">
        @csrf

        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <!-- TOTP Code Input -->
            <div x-show="!useRecovery" class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Authentication Code
                </label>
                <input 
                    type="text" 
                    id="code" 
                    name="code" 
                    maxlength="6"
                    pattern="[0-9]{6}"
                    x-bind:required="!useRecovery"
                    autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black text-center text-2xl tracking-widest font-mono"
                    placeholder="000000"
                >
            </div>

            <!-- Recovery Code Input -->
            <div x-show="useRecovery" class="mb-4" x-cloak>
                <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-2">
                    Recovery Code
                </label>
                <input 
                    type="text" 
                    id="recovery_code" 
                    name="code" 
                    maxlength="10"
                    x-bind:required="useRecovery"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black text-center text-lg tracking-wider font-mono uppercase"
                    placeholder="XXXX-XXXX"
                >
                <input type="hidden" name="recovery" x-bind:value="useRecovery ? '1' : '0'">
            </div>

            @error('code')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                    {{ $message }}
                </div>
            @enderror

            <!-- Toggle Recovery Code -->
            <div class="mb-4">
                <button 
                    type="button" 
                    @click="useRecovery = !useRecovery"
                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"
                >
                    <span x-show="!useRecovery">Use a recovery code instead</span>
                    <span x-show="useRecovery" x-cloak>Use authentication code</span>
                </button>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary w-full">
                Verify
            </button>

            <!-- Back to Login -->
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Back to login
                </a>
            </div>
        </div>
    </form>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-guest-layout>
