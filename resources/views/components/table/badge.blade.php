@props(['variant' => 'olive'])

@php
    $variants = [
        'olive' => 'bg-[#91AC67]/15 text-[#597928]',
        'cream' => 'bg-[#FCECD8] text-[#6E3511]',
        'gray' => 'bg-stone-100 text-stone-600',
        'danger' => 'bg-rose-100 text-rose-700',
    ];
    $class = $variants[$variant] ?? $variants['olive'];
@endphp

<span
    {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$class}"]) }}>
    {{ $slot }}
</span>
