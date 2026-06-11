@php
    $active = 'bg-gray-700 text-white';
    $inactive = 'hover:bg-gray-200';
@endphp

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-60' : 'w-16'"
        class="sidebar sticky top-0 h-screen overflow-y-auto transition-all duration-100 hidden md:block">

        <!-- Header -->
        <div :class="sidebarOpen ? 'justify-between' : 'justify-center'" class="h-16 flex items-center px-4">

            <span x-show="sidebarOpen" class="font-bold text-lg">
                SPP Admin
            </span>

            <button @click="sidebarOpen = !sidebarOpen">
                ☰
            </button>

        </div>

        <!-- Menu -->
        <nav class="mt-4 space-y-2">
            @include('components.sidebar.menu')
        </nav>

    </aside>
</div>

<aside x-cloak x-show="mobileSidebarOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="
        fixed
        top-0
        left-0
        h-screen
        w-64
        bg-white
        z-50
        md:hidden
        overflow-y-auto
    ">
    <div class="flex items-center justify-between p-4 border-b">
        <span class="font-bold text-lg">
            SPP Admin
        </span>

        <button @click="mobileSidebarOpen = false" class="text-2xl">
            ✕
        </button>

    </div>
    <nav class="mt-4 space-y-2">
        @include('components.sidebar.menu')
    </nav>
</aside>

<div x-cloak x-show="mobileSidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 md:hidden"
    @click="mobileSidebarOpen = false">
</div>
