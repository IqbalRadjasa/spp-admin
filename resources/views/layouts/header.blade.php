<header x-data="{ openProfile: false }" class="header h-16 flex items-center justify-between px-4 py-3">

    <div class="">

        <button class="md:hidden px-3 py-2 rounded bg-gray-200 flex items-center" @click="mobileSidebarOpen = true">
            <i class="ri-menu-line text-2xl"></i>
        </button>
    </div>


    <div class="">
        {{-- Theme Toggle --}}
        {{-- <div class="flex items-center ml-5">
            <button class="px-3 py-2 rounded bg-gray-200">
                🌙
            </button>
        </div> --}}
        <x-dropdown.dropdown align="right" width="48">
            <x-slot name="trigger">
                <button @click="openProfile = !openProfile"
                    class="flex items-center px-3 py-2 rounded-[8px] bg-[var(--bg-color)] not-active ml-5">

                    <svg class="w-4 transition" viewBox="0 0 20 20">
                        <path d="M5.5 7.5L10 12l4.5-4.5" stroke="currentColor" stroke-width="2" fill="#113F67" />
                    </svg>

                    <div class="hidden sm:flex flex-col items-start mx-3">
                        @auth
                            <span class="text-sm font-semibold text-primary-light">
                                {{ Auth::user()->name }}
                            </span>
                        @endauth
                    </div>
                    <img src="{{ asset('profil-dev.jpeg') }}" class="w-8 h-8 rounded-full" />

                </button>
            </x-slot>

            <x-slot name="content">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown.dropdown-button type="submit" class="text-red-600">
                        Logout
                    </x-dropdown.dropdown-button>
                </form>
            </x-slot>
        </x-dropdown.dropdown>
    </div>

</header>
