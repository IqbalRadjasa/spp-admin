@props([
    'disabled' => false,
])

<textarea @disabled($disabled)
    {{ $attributes->merge([
        'class' => '
                border-gray-700
                focus:border-gray-300
                focus:ring-0
                rounded-md
                shadow-sm
            ',
    ]) }}>{{ $slot }}</textarea>
