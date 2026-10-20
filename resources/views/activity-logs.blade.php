<x-app-layout>
    <div class="py-6 space-y-6">

        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FCECD8] via-[#FCECD8]/70 to-white p-6 border border-[#91AC67]/20 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 text-xl rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center shadow-md shadow-[#597928]/20">
                        <i class="ri-chat-history-line"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-[#6E3511]">Log Aktivitas</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl">
            <div class="p-6">
                <x-table :headers="['Pengguna', 'Aksi', 'Entitas', 'Deksripsi', 'Dibuat pada']" :empty="$activityLogs->isEmpty()">
                    @foreach ($activityLogs as $activityLog)
                        <x-table.tr>
                            <x-table.td>
                                {{ $activityLog->user->name }}
                            </x-table.td>

                            <x-table.td>
                                {{ $activityLog->action }}
                            </x-table.td>

                            <x-table.td>
                                <x-table.code-pill>
                                    {{ $activityLog->entity_type }}
                                </x-table.code-pill>
                            </x-table.td>

                            <x-table.td>
                                {{ $activityLog->description }}
                            </x-table.td>

                            <x-table.td>
                                {{ $activityLog->created_at }}
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table>

                {{ $activityLogs->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</x-app-layout>
