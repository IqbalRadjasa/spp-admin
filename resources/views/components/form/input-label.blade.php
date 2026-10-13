@props([
    'value' => null,
    'isRequired' => false,
])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-stone-700']) }}>
    {{ $value ?? $slot }}
    @if ($isRequired)
        <span class="text-red-500">*</span>
    @endif
</label>
