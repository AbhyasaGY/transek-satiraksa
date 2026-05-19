<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dasbor Kemitraan Reseller') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 flex flex-col md:flex-row items-center justify-between">

                <div class="mb-6 md:mb-0">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, Mitra {{ Auth::user()->name }}!
                    </h3>
                    <p class="text-gray-600">Sebagai Reseller resmi, Anda wajib menyetujui dan menyimpan salinan Kontrak
                        Kemitraan.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('reseller.belanja') }}"
                        class="inline-flex justify-center items-center px-6 py-3 bg-gray-900 rounded-md font-semibold text-white hover:bg-black transition shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Pesan Stok Grosir
                    </a>

                    <a href="{{ route('reseller.contract.download') }}"
                        class="inline-flex justify-center items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:bg-red-700 active:bg-red-900 transition ease-in-out duration-150 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh Kontrak
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>