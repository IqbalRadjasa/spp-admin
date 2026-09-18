<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#C0524E] border border-transparent rounded-md font-semibold text-xs text-white capitalize tracking-widest hover:bg-[#C0524E]/80 active:bg-[#C0524E]/90 focus:outline-none focus:ring-2 focus:ring-[#C0524E]/80 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
