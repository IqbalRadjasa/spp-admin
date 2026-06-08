@props(['label', 'id', 'value', 'checked' => false])

<label for="{{ $id }}" class="inline-flex items-center cursor-pointer">

    <input id="{{ $id }}" type="radio" value="{{ $value }}" @checked($checked)
        {{ $attributes->merge([
            'class' => '
                        rounded
                        border-gray-300
                        text-gray-600
                        shadow-sm
                        focus:ring-gray-500
                    ',
        ]) }}>

    <span class="ms-2 text-sm text-gray-600">
        {{ $label }}
    </span>

</label>
