@props([
    'active' => false,
    'icon' => null
])

<a
    {{ $attributes->merge([
        'class' => '
            flex
            items-center
            gap-3
            px-4
            py-3
            rounded-lg
            transition
            ' . ($active
                ? 'bg-gray-700 text-white'
                : 'text-gray-700 hover:bg-gray-200')
        ])
    }}
    :class="sidebarOpen ? 'justify-start' : 'justify-center'"
>

    {{-- Icon --}}
    @if($icon)
        <span class="text-lg">
            <i class="{{ $icon }}"></i>
        </span>
    @endif

    {{-- Label --}}
    <span x-show="sidebarOpen">
        {{ $slot }}
    </span>

</a>
