@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'border border-gray-700 px-2 py-2 focus:border-gray-300 focus:ring-0 rounded-md shadow-sm']) }}>
