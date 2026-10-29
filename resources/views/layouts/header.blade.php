@php
    $user = auth()->user();

    $name = $user->name ?? 'User';
    $words = explode(' ', trim($name));
    $initials = strtoupper(
        count($words) >= 2 ? substr($words[0], 0, 1) . substr(end($words), 0, 1) : substr($name, 0, 2),
    );

    $activeAcademicYear = activeAcademicYear();
    $schoolProfile = schoolProfile();

    $roleValue = is_object($user?->role) ? $user->role->value : $user?->role;
@endphp

<header x-data="{ openProfile: false }"
    class="bg-stone-50/90 backdrop-blur-md border-b border-stone-200/80 h-16 flex items-center justify-between px-4 py-3 sticky z-40">

    <div class="flex items-center gap-3">
        <button class="md:hidden px-3 py-2 rounded bg-gray-200 flex items-center" @click="mobileSidebarOpen = true">
            <i class="ri-menu-line text-2xl"></i>
        </button>

        @if ($user && $roleValue === 'parent')
            @php
                $parentChildren = $user->children ?? collect();
                $activeChildId = session('active_child_id', $parentChildren->first()?->id);
                $currentActiveChild = $parentChildren->where('id', $activeChildId)->first() ?? $parentChildren->first();
            @endphp

            @if ($parentChildren->count() > 0)
                <div
                    class="flex items-center bg-[#FCECD8]/60 border border-[#91AC67]/30 rounded-xl px-3 py-1.5 shadow-sm">
                    <div
                        class="w-7 h-7 rounded-full bg-[#597928] text-[#FCECD8] flex items-center justify-center font-bold text-xs mr-2 shadow-inner">
                        <i class="ri-user-heart-line"></i>
                    </div>
                    <form action="{{ route('parent.switch-child') }}" method="POST" class="flex items-center gap-1.5">
                        @csrf
                        <span
                            class="text-xs font-semibold text-stone-700 uppercase tracking-wider hidden sm:inline">Anak:</span>
                        <select name="child_id" onchange="this.form.submit()"
                            class="bg-transparent text-xs font-bold text-stone-900 border-none focus:ring-0 py-0 pl-1 pr-6 cursor-pointer">
                            @foreach ($parentChildren as $child)
                                <option value="{{ $child->id }}"
                                    {{ $currentActiveChild?->id == $child->id ? 'selected' : '' }}>
                                    {{ $child->fullname ?? $child->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif
        @endif

        @if (auth()->user()->isAdmin() || auth()->user()->isTuStaff())
            <div
                class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-[#FCECD8]/60 border border-[#91AC67]/30 rounded-xl text-xs font-semibold text-stone-700 shadow-2xs">
                <i class="ri-school-line text-[#597928] text-base"></i>
                <span>Sekolah:</span>
                <span class="text-[#597928] font-bold">
                    {{ $schoolProfile ? $schoolProfile->school_name : '-' }}
                </span>
            </div>

            <div
                class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-[#FCECD8]/60 border border-[#91AC67]/30 rounded-xl text-xs font-semibold text-stone-700 shadow-2xs">
                <i class="ri-calendar-line text-[#597928] text-base"></i>
                <span>Tahun Akademik:</span>
                <span class="text-[#597928] font-bold">
                    {{ $activeAcademicYear ? $activeAcademicYear->name : 'Belum Atur Aktif' }}
                </span>
            </div>
        @endif
    </div>

    <div class="">
        <x-dropdown.dropdown align="right" width="48">
            <x-slot name="trigger">
                <button @click="openProfile = !openProfile" type="button"
                    class="flex items-center gap-3 px-3 py-1.5 rounded-xl border border-stone-200/80 bg-white hover:bg-[#FCECD8]/40 transition-all duration-200 shadow-2xs group focus:outline-none focus:ring-2 focus:ring-[#597928]/30"
                    class:list="{ 'bg-[#FCECD8]/50 border-[#91AC67]/50': openProfile }">

                    {{-- Avatar with Accent Ring --}}
                    <div class="relative shrink-0">
                        <div
                            class="w-8 h-8 rounded-full bg-[#597928] text-white font-bold text-xs flex items-center justify-center ring-2 ring-[#91AC67]/40 shadow-2xs group-hover:scale-105 transition-transform duration-200">
                            {{ $initials ?? 'AD' }}
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
                        @endauth
                    </div>

                    {{-- Rotating Chevron Arrow --}}
                    <i class="ri-arrow-down-s-line text-lg text-stone-400 group-hover:text-[#597928] transition-transform duration-300 ml-0.5"
                        :class="openProfile ? 'rotate-180 text-[#597928]' : ''"></i>

                </button>
            </x-slot>

            <x-slot name="content">
                {{-- Optional Mobile View Academic Year Item Inside Dropdown --}}
                <div class="block md:hidden px-4 py-2 text-xs font-medium text-stone-500 border-b border-stone-100">
                    TA Aktif: <span
                        class="font-bold text-[#597928]">{{ $activeAcademicYear ? $activeAcademicYear->name : '-' }}</span>
                </div>

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
