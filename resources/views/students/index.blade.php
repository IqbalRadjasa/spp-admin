<x-app-layout>
    <div class="py-6">
        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Student Records</h1>

            <a href="{{ route('students.create') }}"
                class="bg-gray-700 text-white px-5 py-2 rounded transition hover:bg-gray-600">
                <i class="ri-add-line"></i>
                Tambah Siswa
            </a>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
