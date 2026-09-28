@props([
    'disabled' => false,
])

<textarea @disabled($disabled)
    {{ $attributes->merge([
        'class' =>
            'block w-full text-sm rounded-md border border-[#91AC67] shadow-sm transition duration-150 ease-in-out focus:outline-none focus:border-[#597928] focus:ring-2 focus:ring-[#597928]/30 focus:ring-offset-1 disabled:opacity-60 disabled:bg-stone-100 disabled:cursor-not-allowed',
    ]) }}>{{ $slot }}</textarea>
