@props([
    'for' => null,
    'value' => null,
    'required' => false,
])

<label
    @if ($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => 'block text-sm font-medium text-neutral-700 mb-1']) }}
>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="text-red-600">*</span>
    @endif
</label>
