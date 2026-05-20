<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Pembelian Saya') }}
            </h2>
            <a href="{{ url('/dashboard') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-bold transition">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Daftar Transaksi Anda</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Invoice</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk Yang Dibeli</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Metode</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Pembayaran</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @forelse($transactions as $trx)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $trx->created_at->format('d M Y, H:i') }} WIB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {{ $trx->invoice_number }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    <ul class="list-disc list-inside space-y-0.5">
                                        @foreach($trx->transaction_details as $detail)
                                        <li>
                                            <span class="font-medium text-gray-900">{{ $detail->product->name }}</span>
                                            <span class="text-gray-400 text-xs">({{ $detail->quantity }}x)</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-600">
                                    {{ $trx->payment->payment_method ?? 'Cash / Digital' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-extrabold text-indigo-600">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($trx->status === 'Success')
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">Sukses</span>
                                    @elseif($trx->status === 'Failed')
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">Gagal</span>
                                    @else
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                                    Anda belum pernah melakukan transaksi apa pun di Satiraksa Store.
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
