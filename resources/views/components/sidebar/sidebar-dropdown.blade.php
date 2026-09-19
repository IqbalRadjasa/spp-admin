@props(['title', 'icon' => null, 'active' => false])

@php
    $classes = $active
        ? 'bg-[#FCECD8] text-[#597928] font-medium border-l-4 border-[#597928] shadow-2xs'
        : 'text-stone-600 hover:bg-[#FCECD8]/50 hover:text-stone-900 font-medium border-l-4 border-transparent';
@endphp

<div x-data="{
    active: {{ $active ? 'true' : 'false' }},
    open: {{ $active ? 'true' : 'false' }}
}"
    x-effect="
        if (!sidebarOpen) {
            open = false;
        }

        if (sidebarOpen && active) {
            open = true;
        }
    ">

    {{-- Parent Menu Button --}}
    <button
        @click="
            if (!sidebarOpen) {
                sidebarOpen = true;
                open = true;
                return;
            }
            open = !open;
        "
        type="button"
        class="w-full flex items-center px-4 py-2.5 rounded-r-xl transition-all duration-200 {{ $classes }}"
        :class="sidebarOpen ? 'justify-between' : 'justify-center'">

        <div class="flex items-center gap-3">
            {{-- Icon --}}
            @if ($icon)
                <span class="text-lg transition-transform duration-200" :class="{ 'scale-110': open || active }">
                    <i class="{{ $icon }}"></i>
                </span>
            @endif

            {{-- Title --}}
            <span x-show="sidebarOpen" class="text-sm">
                {{ $title }}
            </span>
        </div>

        {{-- Arrow Chevron --}}
        <i x-show="sidebarOpen" class="ri-arrow-down-s-line text-lg transition-transform duration-300 text-stone-400"
            :class="open && sidebarOpen ? 'rotate-180 text-[#597928]' : ''"></i>
    </button>

    {{-- Submenu Container with Visual Tree Line --}}
    <div x-show="open && sidebarOpen" x-collapse class="pl-4 ml-5 border-l-2 border-stone-200/70 space-y-1 mt-1">
        {{ $slot }}
    </div>

</div>
