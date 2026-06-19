@props(['title', 'icon' => null, 'active' => false])

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

    {{-- Parent Menu --}}
    <button
        @click="
            if (!sidebarOpen) {
                sidebarOpen = true;
                return;
            }

            open = !open;
        "
        type="button"
        class="
            w-full
            flex
            items-center
            px-4
            py-3
            rounded-lg
            transition
            {{ $active ? 'bg-gray-700 text-white' : 'text-gray-700 hover:bg-gray-200' }}
        "
        :class="sidebarOpen ? 'justify-between' : 'justify-center'">

        <div class="flex items-center gap-3">

            {{-- Icon --}}
            @if ($icon)
                <i class="{{ $icon }}"></i>
            @endif

            {{-- Title --}}
            <span x-show="sidebarOpen">
                {{ $title }}
            </span>

        </div>

        {{-- Arrow --}}
        <i x-show="sidebarOpen"
            class="
                ri-arrow-down-s-line
                transition-transform
                duration-300
            "
            :class="open && sidebarOpen ? 'rotate-180' : ''"></i>

    </button>

    {{-- Submenu --}}
    <div x-show="open && sidebarOpen" x-collapse class="ml-6 space-y-2">

        {{ $slot }}

    </div>

</div>
