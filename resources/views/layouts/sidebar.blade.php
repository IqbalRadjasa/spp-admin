@php
    $active = 'bg-gray-700 text-white';
    $inactive = 'hover:bg-gray-200';
@endphp

<div x-data="{ sidebarOpen: true }" class="flex min-h-screen">

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-60' : 'w-16'" class="sidebar transition-all duration-100">

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

            <a href="{{ route('dashboard') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('dashboard') ? $active : $inactive }}">
                <span><i class="ri-dashboard-line"></i></span>

                <span x-show="sidebarOpen">
                    Dashboard
                </span>

            </a>

            <a href="{{ route('students.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('students.*') ? $active : $inactive }}">

                <span><i class="ri-graduation-cap-line"></i></span>

                <span x-show="sidebarOpen">
                    Student Records
                </span>

            </a>

            <a href="{{ route('bills.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('bills.index') ? $active : $inactive }}">

                <span><i class="ri-file-list-3-line"></i></span>

                <span x-show="sidebarOpen">
                    List Bills
                </span>

            </a>

            <a href="{{ route('bills.generate.form') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('bills.generate.form') ? $active : $inactive }}">

                <span><i class="ri-file-settings-line"></i></span>

                <span x-show="sidebarOpen">
                    Generate Bills
                </span>

            </a>

        </nav>

    </aside>
</div>
