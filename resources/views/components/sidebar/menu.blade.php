 @if (auth()->user()->isSuperAdmin())
     <x-sidebar.sidebar-link :href="route('dashboard')" icon="ri-dashboard-line" :active="request()->routeIs('dashboard')">
         Dashboard
     </x-sidebar.sidebar-link>
 @endif

 <x-sidebar.sidebar-dropdown title="Students" icon="ri-graduation-cap-line" :active="request()->routeIs('students*')">
     <x-sidebar.sidebar-dropdown-link :href="route('students.index')" :active="request()->routeIs('students.*')">
         Student Records
     </x-sidebar.sidebar-dropdown-link>
     @if (auth()->user()->isSuperAdmin())
         <x-sidebar.sidebar-dropdown-link :href="route('students-promotion.index')" :active="request()->routeIs('students-promotion.*')">
             Student Promotion
         </x-sidebar.sidebar-dropdown-link>
     @endif
 </x-sidebar.sidebar-dropdown>

 <x-sidebar.sidebar-dropdown title="Bills" icon="ri-file-list-3-line" :active="request()->routeIs('bills.*', 'payments.create')">
     <x-sidebar.sidebar-dropdown-link :href="route('bills.index')" :active="request()->routeIs('bills.index', 'bills.detail', 'payments.create')">
         List Bills
     </x-sidebar.sidebar-dropdown-link>

     <x-sidebar.sidebar-dropdown-link :href="route('bills.generate.form')" :active="request()->routeIs('bills.generate.form')">
         Generate Bills
     </x-sidebar.sidebar-dropdown-link>
 </x-sidebar.sidebar-dropdown>

 <x-sidebar.sidebar-dropdown title="Reports" icon="ri-book-2-line" :active="request()->routeIs('reports.*', 'payments.receipt')">
     <x-sidebar.sidebar-dropdown-link :href="route('reports.overdue-report')" :active="request()->routeIs('reports.overdue-report')">
         Overdue Report
     </x-sidebar.sidebar-dropdown-link>

     <x-sidebar.sidebar-dropdown-link :href="route('reports.payment-report')" :active="request()->routeIs('reports.payment-report', 'payments.receipt')">
         Payment Report
     </x-sidebar.sidebar-dropdown-link>
 </x-sidebar.sidebar-dropdown>

 @if (auth()->user()->isSuperAdmin())
     <x-sidebar.sidebar-link :href="route('activity-logs.index')" icon="ri-chat-history-line" :active="request()->routeIs('activity-logs.index')">
         Activity Logs
     </x-sidebar.sidebar-link>
 @endif

 @if (auth()->user()->isSuperAdmin())
     <x-sidebar.sidebar-dropdown title="Settings" icon="ri-settings-3-line" :active="request()->routeIs('settings.*')">
         <x-sidebar.sidebar-dropdown-link :href="route('settings.school.edit')" :active="request()->routeIs('settings.school.edit')">
             School
         </x-sidebar.sidebar-dropdown-link>

         <x-sidebar.sidebar-dropdown-link :href="route('settings.academic-years.index')" :active="request()->routeIs('settings.academic-years.index')">
             Academic Years
         </x-sidebar.sidebar-dropdown-link>

         <x-sidebar.sidebar-dropdown-link :href="route('settings.majors.index')" :active="request()->routeIs('settings.majors.index')">
             Majors
         </x-sidebar.sidebar-dropdown-link>

         <x-sidebar.sidebar-dropdown-link :href="route('settings.classrooms.index')" :active="request()->routeIs('settings.classrooms.*')">
             Classrooms
         </x-sidebar.sidebar-dropdown-link>
     </x-sidebar.sidebar-dropdown>
 @endif
