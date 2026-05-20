<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 text-center border-t-4 border-green-500">

                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Berhasil!</h2>
                <p class="text-gray-500 text-sm mb-6">Terima kasih atas pesanan Anda. Sistem kami sedang memperbarui
                    status transaksi dan menyiapkan produk Anda secara otomatis.</p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('purchase.history') }}"
                        class="inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 rounded-xl font-bold text-white hover:bg-indigo-700 transition shadow-md text-sm">
                        📁 Lihat Riwayat Pembelian
                    </a>
                    <a href="{{ url('/dashboard') }}"
                        class="inline-flex justify-center items-center px-5 py-2.5 bg-gray-100 rounded-xl font-bold text-gray-700 hover:bg-gray-200 transition text-sm">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>