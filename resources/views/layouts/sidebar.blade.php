<!-- Sidebar -->
<aside @click="if (!sidebarOpen) sidebarOpen = true" :class="sidebarOpen ? 'w-60' : 'w-16'"
    class="bg-stone-50/90 sticky top-0 h-screen overflow-y-auto transition-all duration-100 hidden md:block">

    <!-- Header -->
    <div :class="sidebarOpen ? 'justify-between' : 'justify-center'"
        class="flex h-16 items-center px-4 bg-gradient-to-r from-[#597928] to-[#91AC67] text-white">

        <span x-show="sidebarOpen" class="font-bold text-lg">
            SkolaPayd
        </span>

        <button type="button" @click.stop="sidebarOpen = !sidebarOpen"
            class="flex h-10 w-10 items-center justify-center rounded-lg transition hover:bg-gray-100 hover:text-[#91AC67]">
            <i class="ri-layout-left-2-line text-xl"></i>
        </button>

    </div>

    <!-- Menu -->
    <nav class="mt-4 space-y-2">

        @include('components.sidebar.menu')

    </nav>

</aside>

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
        bg-stone-50
        z-50
        md:hidden
        overflow-y-auto
    ">
    <div class="flex items-center justify-between p-4 bg-[#597928] border-b border-gray-300 text-white">

        <span class="font-bold text-lg">
            SPP Admin
        </span>

        <button @click="mobileSidebarOpen = false" class="text-2xl">
            <i class="ri-close-line"></i>
        </button>

    </div>
    <nav class="mt-4 space-y-2">
        @include('components.sidebar.menu')
    </nav>
</aside>

<div x-cloak x-show="mobileSidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/50 z-40 md:hidden"
    @click="mobileSidebarOpen = false">
</div>
