<a
    {{ $attributes->merge([
        'class' => '
                            inline-flex
                            items-center
                            justify-center
                            px-4
                            py-2
                            bg-[#EEEEEE]
                            border
                            border-gray-300
                            rounded-md
                            font-semibold
                            text-xs
                            text-gray-700
                            capitalize
                            tracking-widest
                            shadow-sm
                            hover:bg-[#EEEEEE]/70
                            focus:outline-none
                            focus:ring-2
                            focus:ring-[#EEEEEE]
                            focus:ring-offset-2
                            transition
                            ease-in-out
                            duration-150
                        ',
    ]) }}>
    {{ $slot }}
</a>
