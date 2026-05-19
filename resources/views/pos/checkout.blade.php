<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penyelesaian Pembayaran (Invoice: {{ $transaction->invoice_number }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">

                <h3 class="text-2xl font-bold mb-2">Total: Rp
                    {{ number_format($transaction->total_amount, 0, ',', '.') }}</h3>
                <p class="text-gray-600 mb-6">Silakan arahkan pelanggan untuk menyelesaikan pembayaran melalui antarmuka
                    berikut.</p>

                <!-- Tombol ini akan memanggil Pop-up Snap -->
                <button id="pay-button"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg text-lg transition">
                    Buka Pop-Up Pembayaran
                </button>

                <p class="mt-4 text-sm text-gray-500">Status Pembayaran saat ini: <strong>Menunggu (Pending)</strong>
                </p>

            </div>
        </div>
    </div>

    <!-- Script Wajib dari Midtrans Snap -->
    <!-- Jika di mode Production, link ini harus diganti (tanpa kata 'sandbox') -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>

    <script type="text/javascript">
    document.getElementById('pay-button').onclick = function() {
        // SnapToken dikirim dari Controller
        snap.pay('{{ $snapToken }}', {
            // Callback jika berhasil
            onSuccess: function(result) {
                alert("Pembayaran Berhasil!");
                window.location.href = "{{ route('pos.index') }}";
            },
            // Callback jika belum selesai
            onPending: function(result) {
                alert("Menunggu pembayaran Anda!");
            },
            // Callback jika gagal
            onError: function(result) {
                alert("Pembayaran Gagal!");
            },
            // Callback jika user menutup popup
            onClose: function() {
                alert('Anda menutup pop-up sebelum menyelesaikan pembayaran');
            }
        });
    };
    </script>
</x-app-layout>