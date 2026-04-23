<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('For security reasons, you must change your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf

        <!-- Current Password -->
        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <div class="relative mt-1">
                <x-text-input id="current_password" class="block w-full pr-10" type="password" name="current_password" required autofocus />
                <button type="button" class="toggle-password absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700" data-target="current_password" aria-label="Show current password">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('New Password')" />
            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full pr-10" type="password" name="password" required />
                <button type="button" class="toggle-password absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700" data-target="password" aria-label="Show new password">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <div class="relative mt-1">
                <x-text-input id="password_confirmation" class="block w-full pr-10" type="password" name="password_confirmation" required />
                <button type="button" class="toggle-password absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700" data-target="password_confirmation" aria-label="Show password confirmation">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Change Password') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        (function () {
            document.querySelectorAll('.toggle-password').forEach(function (button) {
                button.addEventListener('click', function () {
                    const input = document.getElementById(button.dataset.target);
                    if (!input) return;
                    input.type = input.type === 'password' ? 'text' : 'password';
                });
            });
        })();
    </script>
</x-guest-layout>
