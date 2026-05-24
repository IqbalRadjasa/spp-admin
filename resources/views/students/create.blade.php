<x-app-layout>
    <div class="py-6">

        <div class="flex items-center pb-6 justify-between">
            <h1 class="font-semibold text-xl">Add Student</h1>
        </div>

        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('students.store') }}" method="POST">
                        @csrf

                        <div class="flex gap-4">
                            <div class="input-group w-1/4">
                                <label>Nama</label>
                                <input type="text" name="name"
                                    class="border-gray-700 focus:border-gray-300 focus:ring-0 rounded">
                            </div>

                            <div class="input-group w-1/4">
                                <label>NIS</label>
                                <input type="text"
                                    name="nis"class="border-gray-700 focus:border-gray-300 focus:ring-0 rounded">
                            </div>

                            <div class="input-group w-1/4">
                                <label>Kelas</label>
                                <input type="text"
                                    name="class"class="border-gray-700 focus:border-gray-300 focus:ring-0 rounded">
                            </div>

                            <div class="input-group w-1/4">
                                <label>No HP Orang Tua</label>
                                <input type="text"
                                    name="parent_phone"class="border-gray-700 focus:border-gray-300 focus:ring-0 rounded">
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 gap-2">
                            <a class="btn-secondary" href="{{ url()->previous() }}">
                                Back
                            </a>
                            <button type="submit" class="btn-primary">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
