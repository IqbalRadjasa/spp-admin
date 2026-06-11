 @if (auth()->user()->isSuperAdmin())
     <x-sidebar.sidebar-link :href="route('dashboard')" icon="ri-dashboard-line" :active="request()->routeIs('dashboard')">
         Dashboard
     </x-sidebar.sidebar-link>
 @endif

 <x-sidebar.sidebar-link :href="route('students.index')" icon="ri-graduation-cap-line" :active="request()->routeIs('students.*')">
     Student Records
 </x-sidebar.sidebar-link>

 <x-sidebar.sidebar-link :href="route('bills.index')" icon="ri-file-list-3-line" :active="request()->routeIs('bills.index')">
     List Bills
 </x-sidebar.sidebar-link>

 <x-sidebar.sidebar-link :href="route('bills.generate.form')" icon="ri-file-settings-line" :active="request()->routeIs('bills.generate.form')">
     Generate Bills
 </x-sidebar.sidebar-link>

 <x-sidebar.sidebar-dropdown title="Reports" icon="ri-book-2-line" :active="request()->routeIs('reports.*')">

     <x-sidebar.sidebar-dropdown-link :href="route('reports.overdue-report')" :active="request()->routeIs('reports.overdue-report')">
         Overdue Report
     </x-sidebar.sidebar-dropdown-link>

     <x-sidebar.sidebar-dropdown-link :href="route('reports.payment-report')" :active="request()->routeIs('reports.payment-report')">
         Payment Report
     </x-sidebar.sidebar-dropdown-link>

 </x-sidebar.sidebar-dropdown>
