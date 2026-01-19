<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Two-Factor Authentication
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Add additional security to your account using two-factor authentication.
        </p>
    </header>

    @if(session('success'))
        <div class="mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6 space-y-6">
        @if(auth()->user()->two_factor_enabled)
            <!-- 2FA is Enabled -->
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-semibold text-sm text-green-900">Two-Factor Authentication is Enabled</h3>
                        <p class="text-sm text-green-800 mt-1">
                            Your account is protected with two-factor authentication.
                        </p>
                        <p class="text-xs text-green-700 mt-2">
                            Enabled on {{ auth()->user()->two_factor_confirmed_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('two-factor.recovery-codes') }}" class="btn-secondary text-sm">
                    View Recovery Codes
                </a>
                
                <form method="POST" action="{{ route('two-factor.disable') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition" onclick="return confirm('Are you sure you want to disable two-factor authentication?')">
                        Disable 2FA
                    </button>
                </form>
            </div>

            <div class="mt-4">
                <form method="POST" action="{{ route('two-factor.recovery-codes.regenerate') }}">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" onclick="return confirm('This will invalidate your old recovery codes. Continue?')">
                        Regenerate Recovery Codes
                    </button>
                </form>
            </div>
        @else
            <!-- 2FA is Disabled -->
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="font-semibold text-sm text-gray-900">Two-Factor Authentication is Disabled</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Enable two-factor authentication to add an extra layer of security to your account.
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ route('two-factor.enable') }}" class="btn-primary text-sm">
                    Enable Two-Factor Authentication
                </a>
            </div>

            <!-- How it Works -->
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-semibold text-sm text-blue-900 mb-2">How it works:</h4>
                <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                    <li>Download an authenticator app (Google Authenticator, Authy, etc.)</li>
                    <li>Scan the QR code with your app</li>
                    <li>Enter the 6-digit code to confirm</li>
                    <li>Save your recovery codes in a safe place</li>
                </ol>
            </div>
        @endif
    </div>
</section>
