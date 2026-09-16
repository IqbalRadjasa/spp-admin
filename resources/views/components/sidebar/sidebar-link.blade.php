@props([
    'active' => false,
    'icon' => null,
])

<a {{ $attributes->merge([
    'class' =>
        '
            flex
            items-center
            gap-3
            px-4
            py-3
            rounded-lg
            transition
            ' .
        ($active
            ? 'bg-gradient-to-r from-[#91AC67] via-[#91AC67]/90 to-[#91AC67]/60 to-black text-white'
            : 'text-black hover:bg-[#91AC67]/30'),
]) }}
    :class="sidebarOpen ? 'justify-start' : 'justify-center'">

    {{-- Icon --}}
    @if ($icon)
        <span class="text-lg">
            <i class="{{ $icon }}"></i>
        </span>
    @endif

    {{-- Label --}}
    <span x-show="sidebarOpen">
        {{ $slot }}
    </span>

</a>
