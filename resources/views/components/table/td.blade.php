@props(['align' => 'left'])

<td {{ $attributes->merge(['class' => 'py-4 px-5 whitespace-nowrap ' . ($align === 'right' ? 'text-right' : '')]) }}>
    {{ $slot }}
</td>
