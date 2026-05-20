    <div x-data="{ sidebarOpen: true }" class="flex min-h-screen">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-60' : 'w-16'" class="sidebar transition-all duration-100">

            <!-- Header -->
            <div class="h-16 flex items-center justify-between px-4">

                <span x-show="sidebarOpen" class="font-bold text-lg">
                    SPP Admin
                </span>

                <button @click="sidebarOpen = !sidebarOpen">
                    ☰
                </button>

            </div>

            <!-- Menu -->
            <nav class="mt-4 space-y-2">

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'hover:bg-gray-200' }}">
                    <span><i class="ri-dashboard-line"></i></span>

                    <span x-show="sidebarOpen">
                        Dashboard
                    </span>

                </a>

                <a href="{{ route('students.index') }}"
                    class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('students.index') ? 'bg-gray-700 text-white' : 'hover:bg-gray-200' }}">

                    <span><i class="ri-graduation-cap-line"></i></span>

                    <span x-show="sidebarOpen">
                        Student Records
                    </span>

                </a>

                <a href="{{ route('bills.index') }}"
                    class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('bills.index') ? 'bg-gray-700 text-white' : 'hover:bg-gray-200' }}">

                    <span><i class="ri-file-list-3-line"></i></span>

                    <span x-show="sidebarOpen">
                        List Bills
                    </span>

                </a>

                <a href="{{ route('bills.generate.form') }}"
                    class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('bills.generate.form') ? 'bg-gray-700 text-white' : 'hover:bg-gray-200' }}">

                    <span><i class="ri-file-settings-line"></i></span>

                    <span x-show="sidebarOpen">
                        Generate Bills
                    </span>

                </a>

            </nav>

        </aside>
    </div>
