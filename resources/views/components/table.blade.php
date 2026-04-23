<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-neutral-200']) }}>
        <thead class="bg-neutral-50">
            <tr>
                {{ $head ?? '' }}
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-100">
            {{ $body ?? $slot }}
        </tbody>
    </table>
</div>
