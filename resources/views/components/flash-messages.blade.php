@props([
    'successClass' => 'mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded',
    'errorClass' => 'mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded',
    'withIcons' => false,
])

@if(session('success'))
    <div class="{{ $successClass }}">
        @if($withIcons)
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @else
            {{ session('success') }}
        @endif
    </div>
@endif

@if(session('error'))
    <div class="{{ $errorClass }}">
        @if($withIcons)
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @else
            {{ session('error') }}
        @endif
    </div>
@endif
