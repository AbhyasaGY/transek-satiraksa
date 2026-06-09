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
                <div class="mt-6 border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Status Kemitraan Anda</h3>

                    @if(session('success'))
                    <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded-lg font-bold">
                        {{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg font-bold">⚠️ {{ session('error') }}
                    </div>
                    @endif

                    @if(Auth::user()->contract_status === 'Disetujui')
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                        <span
                            class="inline-block bg-green-500 text-white font-extrabold px-4 py-1 rounded-full mb-3">AKTIF
                            & DISETUJUI</span>
                        <p class="text-green-800">Selamat! Kontrak kemitraan Anda telah divalidasi. Anda kini memiliki
                            akses penuh ke sistem grosir B2B.</p>
                        <a href="{{ route('reseller.belanja') }}"
                            class="mt-4 inline-block bg-indigo-600 text-white font-bold px-6 py-2 rounded-lg shadow hover:bg-indigo-700 transition">Ke
                            Katalog Grosir</a>
                    </div>
                    @elseif(Auth::user()->contract_status === 'Menunggu Validasi')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                        <span
                            class="inline-block bg-yellow-500 text-white font-extrabold px-4 py-1 rounded-full mb-3">MENUNGGU
                            VALIDASI</span>
                        <p class="text-yellow-800">Dokumen Anda sedang ditinjau oleh Admin. Mohon cek kembali secara
                            berkala.</p>

                        @if(isset($contract))
                        <a href="{{ route('reseller.contract.downloadFile', $contract->id) }}" target="_blank"
                            class="mt-4 inline-block text-indigo-600 underline font-bold">Lihat Dokumen Terkirim</a>
                        @endif

                    </div>
                    @else
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        @if(Auth::user()->contract_status === 'Ditolak')
                        <span
                            class="inline-block bg-red-500 text-white font-extrabold px-4 py-1 rounded-full mb-3">DITOLAK</span>
                        <p class="text-red-800 mb-4">Dokumen sebelumnya ditolak. Harap unduh ulang kontrak, tanda
                            tangani, dan unggah dokumen yang benar.</p>
                        @else
                        <span
                            class="inline-block bg-gray-500 text-white font-extrabold px-4 py-1 rounded-full mb-3">BELUM
                            ADA KONTRAK</span>
                        <p class="text-gray-700 mb-4">Silakan unduh dokumen kontrak, tanda tangani (boleh digital), lalu
                            unggah kembali dalam format PDF.</p>
                        @endif

                        <div class="flex flex-col sm:flex-row gap-4 mb-6">
                            <a href="{{ route('reseller.contract.generate') }}"
                                class="inline-flex justify-center items-center px-4 py-2 bg-gray-900 rounded-lg font-bold text-white hover:bg-black transition">
                                1. Unduh Kontrak PDF
                            </a>
                        </div>

                        <form action="{{ route('reseller.uploadContract') }}" method="POST"
                            enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-end">
                            @csrf
                            <div class="w-full sm:w-auto flex-1">
                                <label class="block text-sm font-bold text-gray-700 mb-1">2. Unggah Kontrak Tertanda
                                    Tangan</label>
                                <input type="file" name="contract_file" accept=".pdf"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-lg"
                                    required>
                            </div>
                            <button type="submit"
                                class="bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition">Unggah</button>
                        </form>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
