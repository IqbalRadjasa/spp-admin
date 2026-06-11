<header x-data="{ openProfile: false }" class="header h-16 flex items-center justify-between px-4 py-3">

    <div class="ml-auto flex relative h-full cursor-pointer">

        <button class="md:hidden" @click="mobileSidebarOpen = true">
            <i class="ri-menu-line text-2xl"></i>
        </button>


        {{-- Theme Toggle --}}
        <div class="flex items-center">
            <button class="px-3 py-2 rounded bg-gray-200">
                🌙
            </button>
        </div>

        {{-- Profile Button --}}
        <button @click="openProfile = !openProfile"
            class="flex items-center px-3 py-2 rounded-[8px] bg-[var(--bg-color)] not-active ml-5">

            <img src="{{ asset('profil-dev.jpeg') }}" class="w-8 h-8 rounded-full" />

            <div class="flex flex-col items-start mx-3">

                @auth
                    <span class="text-sm font-semibold text-primary-light">
                        {{ Auth::user()->name }}
                    </span>
                @endauth

            </div>

            <svg class="w-4 transition" viewBox="0 0 20 20">

                <path d="M5.5 7.5L10 12l4.5-4.5" stroke="currentColor" stroke-width="2" fill="#113F67" />

            </svg>
        </button>

        {{-- Dropdown --}}
        <div x-cloak x-show="openProfile" x-transition @click.outside="openProfile = false"
            class="absolute right-0 mt-12 w-40 bg-[var(--white)] rounded-md shadow-lg p-2 animate-in fade-in slide-in-from-top-1">

            <button class="w-full px-3 py-2 text-left rounded hover:bg-[#6c757d]/10 transition text-[#113F67]">

                <span class="regular-text text-[var(--text-secondary-light)]">
                    Profil Saya
                </span>

            </button>

            <hr class="my-1" />

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="w-full px-3 py-2 text-base text-left text-red-600 rounded hover:bg-red-50 transition">

                    <span class="regular-text">
                        Logout
                    </span>

                </button>
            </form>

        </div>

    </div>
</header>
