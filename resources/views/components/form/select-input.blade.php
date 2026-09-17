@props([
    'disabled' => false,
])

<select @disabled($disabled)
    {{ $attributes->merge([
        'class' => '
                    border-[#91AC67] focus:border-[#91AC67]/40
                    focus:ring-0
                    rounded-md
                    shadow-sm
                ',
    ]) }}>
    {{ $slot }}
</select>
