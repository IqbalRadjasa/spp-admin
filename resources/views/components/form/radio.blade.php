@props(['label', 'id', 'value', 'checked' => false])

<label for="{{ $id }}" class="inline-flex items-center cursor-pointer select-none group">

    <input id="{{ $id }}" type="radio" value="{{ $value }}" @checked($checked)
        {{ $attributes->merge([
            'class' => '
                        rounded-full
                        border-stone-300
                        text-[#597928]
                        shadow-2xs
                        focus:ring-[#597928]/30
                        focus:ring-2
                        focus:ring-offset-0
                        transition-colors
                        cursor-pointer
                    ',
        ]) }}>

    <span class="ms-2.5 text-sm font-medium text-stone-700 group-hover:text-stone-900 transition-colors">
        {{ $label }}
    </span>

</label>
