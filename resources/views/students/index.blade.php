<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Daftar Siswa</h1>

                    <a href="{{ route('students.create') }}" class="underline">
                        Tambah Siswa
                    </a>

                    <table id="studentsTable" class="min-w-full">

                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->class }}</td>
                                    <td>
                                        <div class="flex flex-row gap-2">
                                            <a class="bg-gray-200 px-5 py-2 rounded transition hover:bg-gray-300"
                                                href="{{ route('students.edit', $student->id) }}">
                                                Edit
                                            </a>

                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="bg-gray-200 px-5 py-2 rounded transition hover:bg-gray-300"
                                                    type="submit">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
