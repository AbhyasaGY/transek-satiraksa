<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penyelesaian Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center border-t-4 border-indigo-600">

                <svg class="w-20 h-20 text-indigo-500 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>

                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pesanan Berhasil Dibuat!</h3>
                <p class="text-gray-500 mb-6">Invoice: <strong>{{ $transaction->invoice_number }}</strong></p>

                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8 inline-block min-w-[250px]">
                    <p class="text-sm text-gray-500 font-bold mb-1">Total Tagihan:</p>
                    <p class="text-3xl font-extrabold text-indigo-600">Rp
                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                </div>

                <div>
                    <button id="pay-button"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg text-lg w-full md:w-auto">
                        Tampilkan Layar Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>

    <script type="text/javascript">
    // Saat tombol diklik, munculkan pop-up Midtrans
    document.getElementById('pay-button').onclick = function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                // Jika sukses dibayar, kembali ke dasbor
                window.location.href = "{{ url('/dashboard') }}";
            },
            onPending: function(result) {
                // Jika pending (misal pilih transfer bank tapi belum transfer), kembali ke dasbor
                window.location.href = "{{ url('/dashboard') }}";
            },
            onError: function(result) {
                alert("Pembayaran gagal!");
                window.location.href = "{{ url('/dashboard') }}";
            },
            onClose: function() {
                alert(
                    'Anda menutup layar sebelum menyelesaikan pembayaran. Silakan selesaikan pembayaran Anda.');
            }
        });
    };

    // Otomatis memicu pop-up saat halaman selesai dimuat (opsional)
    window.onload = function() {
        document.getElementById('pay-button').click();
    };
    </script>
</x-app-layout>