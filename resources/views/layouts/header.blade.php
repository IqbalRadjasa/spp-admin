@php
    $name = Auth::user()->name ?? 'User';
    $words = explode(' ', trim($name));
    $initials = strtoupper(
        count($words) >= 2 ? substr($words[0], 0, 1) . substr(end($words), 0, 1) : substr($name, 0, 2),
    );
@endphp

<header x-data="{ openProfile: false }"
    class="bg-stone-50/90 backdrop-blur-md border-b border-stone-200/80 h-16 flex items-center justify-between px-4 py-3 sticky z-40">

    <div>
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
                <button @click="openProfile = !openProfile" type="button"
                    class="flex items-center gap-3 px-3 py-1.5 rounded-xl border border-stone-200/80 bg-white hover:bg-[#FCECD8]/40 transition-all duration-200 shadow-2xs group focus:outline-none focus:ring-2 focus:ring-[#597928]/30"
                    :class="{ 'bg-[#FCECD8]/50 border-[#91AC67]/50': openProfile }">

                    {{-- Avatar with Accent Ring --}}
                    <div class="relative shrink-0">
                        <div
                            class="w-8 h-8 rounded-full bg-[#597928] text-white font-bold text-xs flex items-center justify-center ring-2 ring-[#91AC67]/40 shadow-2xs group-hover:scale-105 transition-transform duration-200">
                            {{ $initials }}
                        </div>
                        <span
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>

                    {{-- User Info --}}
                    <div class="hidden sm:flex flex-col items-start text-left">
                        @auth
                            <span class="text-sm font-bold text-stone-800 leading-snug">
                                {{ Auth::user()->name }}
                            </span>
                            {{-- <span class="text-[10px] font-medium text-stone-500 leading-none">
                                {{ Auth::user()->role ?? 'Administrator' }}
                            </span> --}}
                        @endauth
                    </div>

                    {{-- Rotating Chevron Arrow --}}
                    <i class="ri-arrow-down-s-line text-lg text-stone-400 group-hover:text-[#597928] transition-transform duration-300 ml-0.5"
                        :class="openProfile ? 'rotate-180 text-[#597928]' : ''"></i>

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
