<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            @if(isset($regenerated) && $regenerated)
                New Recovery Codes Generated
            @else
                Recovery Codes
            @endif
        </h2>
        <p class="text-sm text-gray-600 mt-2">
            Store these recovery codes in a secure location. They can be used to access your account if you lose your authenticator device.
        </p>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <!-- Warning -->
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-sm text-yellow-900 mb-1">Important!</h3>
                    <p class="text-sm text-yellow-800">
                        Each recovery code can only be used once. Save these codes now - you won't be able to see them again.
                    </p>
                </div>
            </div>
        </div>

        <!-- Recovery Codes -->
        <div class="mb-6">
            <div class="grid grid-cols-2 gap-3">
                @foreach($recoveryCodes as $code)
                    <div class="bg-gray-50 p-3 rounded border border-gray-200 text-center">
                        <code class="text-sm font-mono font-semibold">{{ $code }}</code>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <button 
                onclick="copyRecoveryCodes()"
                class="btn-secondary flex-1"
            >
                📋 Copy All
            </button>
            <button 
                onclick="downloadRecoveryCodes()"
                class="btn-secondary flex-1"
            >
                💾 Download
            </button>
        </div>

        <!-- Continue Button -->
        <div class="mt-4">
            <a href="{{ route('profile.edit') }}" class="btn-primary w-full text-center block">
                Continue to Profile
            </a>
        </div>
    </div>

    <script>
        function copyRecoveryCodes() {
            const codes = @json($recoveryCodes);
            const text = codes.join('\n');
            navigator.clipboard.writeText(text).then(() => {
                alert('Recovery codes copied to clipboard!');
            });
        }

        function downloadRecoveryCodes() {
            const codes = @json($recoveryCodes);
            const text = 'AIHRM Recovery Codes\n' +
                        'Generated: ' + new Date().toLocaleString() + '\n\n' +
                        codes.join('\n') + '\n\n' +
                        'Keep these codes in a safe place. Each code can only be used once.';
            
            const blob = new Blob([text], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'aihrm-recovery-codes.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    </script>
</x-guest-layout>
