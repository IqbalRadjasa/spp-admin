@props([
    'active' => false
])

<a
    {{ $attributes->merge([
        'class' => '
            block
            px-4
            py-2
            rounded-lg
            transition
            text-sm
            ' . ($active
                ? 'bg-gray-700 text-white'
                : 'text-gray-600 hover:bg-gray-200')
        ])
    }}
>
    {{ $slot }}
</a>
