<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dasbor Admin Satiraksa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan (Sukses)
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $totalTransactions }} Transaksi
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Riwayat Semua Transaksi</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pembeli</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($allTransactions as $trx)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $allTransactions->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $trx->invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $trx->user->name ?? 'Pelanggan Umum (POS)' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($trx->status == 'Success')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sukses</span>
                                        @elseif($trx->status == 'Failed')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Gagal</span>
                                        @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="toggleModal('modal-{{ $trx->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 font-bold transition">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $allTransactions->links() }}
                    </div>

                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 text-red-600">Peringatan Stok Menipis
                    </h3>
                    @if($lowStockProducts->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($lowStockProducts as $product)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Sisa: {{ $product->stock }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-sm text-gray-500 mt-4 text-center">Semua stok produk dalam kondisi aman.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @foreach($allTransactions as $trx)
    <div id="modal-{{ $trx->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                onclick="toggleModal('modal-{{ $trx->id }}')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">

                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-lg font-extrabold text-gray-900">Detail Invoice: {{ $trx->invoice_number }}</h3>
                    <button onclick="toggleModal('modal-{{ $trx->id }}')"
                        class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <div class="space-y-3 text-sm text-gray-700">
                    <p><strong>Tanggal Transaksi:</strong> {{ $trx->created_at->format('d M Y, H:i') }} WIB</p>
                    <p><strong>Nama Pembeli:</strong> {{ $trx->user->name ?? 'Pelanggan Umum (POS)' }}</p>
                    <p><strong>Email Pembeli:</strong> {{ $trx->user->email ?? '-' }}</p>
                    <p><strong>Peran Akun:</strong> <span
                            class="px-2 py-0.5 bg-gray-100 rounded text-xs font-semibold text-gray-600">{{ $trx->user->role ?? 'Guest' }}</span>
                    </p>

                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p><strong>Metode Pembayaran:</strong> {{ $trx->payment->payment_method ?? 'Tunai / Cash' }}</p>
                        <p><strong>Status Bayar:</strong>
                            <span
                                class="font-bold {{ ($trx->payment->payment_status ?? '') === 'Paid' || ($trx->payment->payment_status ?? '') === 'Success' ? 'text-green-600' : 'text-red-500' }}">
                                {{ $trx->payment->payment_status ?? 'Paid' }}
                            </span>
                        </p>
                    </div>

                    <div class="mt-4 border-t pt-3">
                        <p class="font-bold text-gray-800 mb-2">Daftar Produk Yang Dibeli:</p>
                        <table class="min-w-full text-xs text-left text-gray-500">
                            <thead class="bg-gray-100 text-gray-700 font-bold uppercase">
                                <tr>
                                    <th class="p-2">Produk</th>
                                    <th class="p-2 text-center">Qty</th>
                                    <th class="p-2 text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trx->transaction_details as $detail)
                                <tr class="border-b">
                                    <td class="p-2 font-medium text-gray-900">{{ $detail->product->name }}</td>
                                    <td class="p-2 text-center">{{ $detail->quantity }}</td>
                                    <td class="p-2 text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right text-base font-extrabold text-indigo-600 pt-3 border-t">
                        Grand Total: Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

    <script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
    </script>
</x-app-layout>