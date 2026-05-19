<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dasbor Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}!</h3>
                        <div class="mb-8 mt-4">
                            <a href="{{ route('pelanggan.belanja') }}"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-lg shadow-indigo-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Mulai Belanja Sekarang
                            </a>
                        </div>
                        <p class="text-gray-500 text-sm">Terdaftar sebagai Pelanggan Retail Satiraksa Store</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4">Riwayat Belanja Anda</h4>
                    <div class="bg-gray-50 p-6 rounded-xl text-center border border-dashed border-gray-200">
                        <p class="text-gray-500 italic text-sm">Belum ada riwayat transaksi retail yang tercatat untuk
                            akun Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>