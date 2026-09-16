@props([
    'active' => false,
])

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
                                    ' .
            ($active
                ? 'bg-gradient-to-r from-[#91AC67] via-[#91AC67]/90 to-[#91AC67]/60 text-white'
                : 'text-gray-600 hover:bg-[#91AC67]/30'),
    ]) }}>
    {{ $slot }}
</a>
