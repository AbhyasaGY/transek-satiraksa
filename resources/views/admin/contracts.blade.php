<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Validasi Kontrak Kemitraan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div
                class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm font-semibold">
                ✅ {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Antrean Dokumen Kontrak Reseller</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Mitra</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    File Kontrak</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status Saat Ini</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tindakan Admin</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse($resellers as $reseller)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {{ $reseller->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $reseller->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($reseller->contract_file)
                                    <a href="{{ asset('storage/' . $reseller->contract_file) }}" target="_blank"
                                        class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-bold underline">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Buka Kontrak (PDF)
                                    </a>
                                    @else
                                    <span class="text-gray-400 italic">Belum mengunggah</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($reseller->contract_status === 'Disetujui')
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                    @elseif($reseller->contract_status === 'Ditolak')
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                    @else
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">Menunggu
                                        Review</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('admin.contracts.process', $reseller->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI kemitraan ini?');">
                                            @csrf
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit"
                                                class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-bold disabled:opacity-40"
                                                {{ $reseller->contract_status === 'Disetujui' ? 'disabled' : '' }}>
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.contracts.process', $reseller->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK dokumen kontrak ini?');">
                                            @csrf
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-bold disabled:opacity-40"
                                                {{ $reseller->contract_status === 'Ditolak' ? 'disabled' : '' }}>
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                                    Tidak ada berkas kontrak kemitraan dalam antrean review.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
