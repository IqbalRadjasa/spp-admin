@props([
    'active' => false,
    'icon' => null,
])

@php
    $classes = $active
        ? 'bg-[#FCECD8] text-[#597928] font-medium border-l-4 border-[#597928] shadow-2xs'
        : 'text-stone-600 hover:bg-[#FCECD8]/50 hover:text-stone-900 font-medium border-l-4 border-transparent';
@endphp

<a {{ $attributes->merge([
    'class' => "flex items-center gap-3 px-4 py-2.5 rounded-r-lg transition-all duration-200 {$classes}",
]) }}
    :class="sidebarOpen ? 'justify-start' : 'justify-center'">

    {{-- Icon --}}
    @if ($icon)
        <span class="text-lg transition-transform duration-200"
            :class="{ 'scale-110': {{ $active ? 'true' : 'false' }} }">
            <i class="{{ $icon }}"></i>
        </span>
    @endif

    {{-- Label --}}
    <span x-show="sidebarOpen" class="text-sm">
        {{ $slot }}
    </span>
</a>
