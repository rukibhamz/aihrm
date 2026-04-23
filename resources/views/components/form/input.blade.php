@props([
    'disabled' => false,
])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm']) }}
>
