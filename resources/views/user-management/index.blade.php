<x-app-layout>
    <div class="py-6 space-y-6">

        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-id-card-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Manajemen Pengguna</h1>
                    </div>
                </div>

                <x-link-button.primary-link :href="route('users.create')" icon="ri-add-line" class="">
                    Tambah Pengguna
                </x-link-button.primary-link>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl">
            <div class="p-6">
                {{-- <form class="flex flex-col md:flex-row md:flex-wrap gap-3 mb-6">
                    <x-form.text-input type="text" name="search" :value="request('search')" placeholder="Find a student..."
                        class="w-full md:w-80" />

                    <x-form.select-input name="major" id="major-filter">
                        <option value="">All</option>

                        @foreach ($majors as $major)
                            <option value="{{ $major->id }}" @selected(request('major') == $major->id)>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </x-form.select-input>

                    <x-button.primary-button class="w-full md:w-auto">
                        Filter
                    </x-button.primary-button>
                </form> --}}

                <x-table :headers="['Nama', 'Email', 'Role', 'Status', 'Action']" :empty="$users->isEmpty()">
                    @foreach ($users as $user)
                        <x-table.tr>
                            <x-table.td>
                                <x-table.avatar-cell :initials="$user->initials" :name="$user->name" />
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>{{ $user->email }}</x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                <x-table.badge variant="olive" class="capitalize">
                                    {{ $user->role->value == 'tu_staff' ? 'TU Staff' : $user->role }}
                                </x-table.badge>
                            </x-table.td>

                            <x-table.td>
                                @switch($user->status)
                                    @case('pending')
                                        <x-table.badge variant="gray" class="capitalize">
                                            {{ $user->status }}
                                        </x-table.badge>
                                    @break

                                    @case('suspended')
                                        <x-table.badge variant="danger" class="capitalize">
                                            {{ $user->status . ' / Ditangguhkan' }}
                                        </x-table.badge>
                                    @break

                                    @default
                                        <x-table.badge variant="olive" class="capitalize">
                                            {{ $user->status }}
                                        </x-table.badge>
                                @endswitch
                            </x-table.td>

                            {{-- Action Dropdown --}}
                            <x-table.td>
                                <x-dropdown.dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 hover:bg-stone-100 hover:text-stone-800 transition-colors">
                                            <i class="ri-more-2-fill text-lg"></i>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <form action="{{ route('users.status', $user->id) }}" method="POST"
                                            onsubmit="event.preventDefault(); confirmAction(() => this.submit(), {confirmButtonColor: '#C0524E' });">
                                            @csrf

                                            {{-- <input type="text" name="status"
                                                value="{{ $user->status === 'active' ? 'suspended' : 'active' }}"
                                                hidden> --}}
                                            <x-dropdown.dropdown-button type="submit" class="text-[#C0524E]">
                                                {{ $user->status === 'active' ? 'Suspend' : 'Aktifkan' }}
                                            </x-dropdown.dropdown-button>
                                        </form>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="event.preventDefault(); confirmAction(() => this.submit(), { text: 'Pengguna ini akan dinonaktifkan/dihapus.', confirmButtonText: 'Hapus', confirmButtonColor: '#C0524E' });">
                                            @csrf
                                            @method('DELETE')

                                            <x-dropdown.dropdown-button type="submit" class="text-[#C0524E]">
                                                Hapus
                                            </x-dropdown.dropdown-button>
                                        </form>
                                    </x-slot>
                                </x-dropdown.dropdown>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>
                {{ $users->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</x-app-layout>
