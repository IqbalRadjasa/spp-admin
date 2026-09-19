@props([
    'active' => false,
])

@php
    $classes = $active
        ? 'bg-[#FCECD8] text-[#597928] font-medium border-l-4 border-[#597928] shadow-2xs'
        : 'text-stone-600 hover:bg-[#FCECD8]/50 hover:text-stone-900 font-medium border-l-4 border-transparent';
@endphp

<a
    {{ $attributes->merge([
        'class' =>
            '
                block
                px-4
                py-2
                rounded-lg
                transition
                text-sm
                ' . $classes,
    ]) }}>
    {{ $slot }}
</a>
