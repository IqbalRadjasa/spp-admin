<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Tambah Siswa</h1>

                    <form action="{{ route('students.store') }}" method="POST" class="mb-5">
                        @csrf

                        <div>
                            <label>Nama</label>
                            <input type="text" name="name">
                        </div>

                        <div>
                            <label>NIS</label>
                            <input type="text" name="nis">
                        </div>

                        <div>
                            <label>Kelas</label>
                            <input type="text" name="class">
                        </div>

                        <div>
                            <label>No HP Orang Tua</label>
                            <input type="text" name="parent_phone">
                        </div>

                        <button type="submit" class="bg-gray-200 p-4">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
