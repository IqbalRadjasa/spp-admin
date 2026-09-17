<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-[#91AC67] border border-transparent rounded-md font-semibold text-sm text-white capitalize tracking-widest hover:bg-[#91AC67]/80 focus:bg-[#91AC67]/80 active:bg-[#91AC67]/90 focus:outline-none focus:ring-2 focus:ring-[#91AC67]/60 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
