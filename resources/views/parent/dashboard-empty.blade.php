<x-app-layout>
    <div class="py-12 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-stone-200 p-8 text-center space-y-6">

            <!-- Icon Illustration -->
            <div
                class="w-16 h-16 bg-[#FCECD8] text-stone-700 rounded-full flex items-center justify-center mx-auto text-2xl shadow-inner">
                <i class="ri-user-unfollow-line"></i>
            </div>

            <!-- Message Heading -->
            <div class="space-y-2">
                <h1 class="text-xl font-bold text-stone-900">Akses Belum Tersedia</h1>
                <p class="text-sm text-stone-600 max-w-md mx-auto">
                    {{ $message ?? 'Akun Anda belum terhubung dengan data profil orang tua atau data siswa di sistem sekolah.' }}
                </p>
            </div>

            <!-- Helpful Information Box -->
            <div class="bg-stone-50 border border-stone-200 rounded-lg p-4 text-left text-xs text-stone-600 space-y-1">
                <p class="font-semibold text-stone-700">Apa yang harus dilakukan?</p>
                <p>Silakan hubungi administrator atau bagian tata usaha sekolah untuk menghubungkan akun email Anda
                    dengan data siswa yang bersangkutan.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-center gap-3 pt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button.secondary-button type="submit">
                        <i class="ri-logout-box-line mr-1.5"></i> Keluar
                    </x-button.secondary-button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
