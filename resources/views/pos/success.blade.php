<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaksi Berhasil
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                <div class="text-green-500 mb-4">
                    <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">Terima Kasih!</h3>
                <p class="text-gray-600 mb-8">Pembayaran telah berhasil dikonfirmasi. Pesanan sedang diproses.</p>

                <div class="flex justify-center gap-4">
                    <a href="/dashboard"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition">
                        Kembali ke Dasbor
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
