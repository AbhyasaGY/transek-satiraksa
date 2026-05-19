<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modul Kasir (Point of Sales)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col md:flex-row gap-6">

                <div class="w-full md:w-2/3">
                    <h3 class="text-lg font-bold mb-4">Daftar Produk Satiraksa</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($products as $product)
                        <div class="border rounded-lg p-4 shadow hover:shadow-md transition">
                            <h4 class="font-bold text-gray-800">{{ $product->name }}</h4>
                            <p class="text-sm text-gray-500">SKU: {{ $product->sku }} | Stok: {{ $product->stock }}</p>
                            <p class="text-lg font-bold text-indigo-600 mt-2">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="w-full md:w-1/3 bg-gray-50 p-4 rounded-lg border">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Proses Transaksi</h3>

                    <form id="checkoutForm" action="{{ route('pos.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Pilih Produk</label>
                            <select name="product_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="">-- Pilih --</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Kuantitas</label>
                            <input type="number" name="quantity" min="1" value="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required onchange="toggleTunaiInput()">
                                <option value="Uang Tunai">Uang Tunai</option>
                                <option value="Digital">Digital (Debit/Kredit/E-Wallet via Midtrans)</option>
                            </select>
                        </div>

                        <div class="mb-6" id="tunai_input_div">
                            <label class="block text-sm font-medium text-gray-700">Nominal Uang Diterima (Rp)</label>
                            <input type="number" name="amount_paid" id="amount_paid"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition shadow-md">
                                Proses Checkout
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        // Mengatur tampilan input tunai
        function toggleTunaiInput() {
            var method = document.getElementById("payment_method").value;
            var tunaiDiv = document.getElementById("tunai_input_div");
            var amountInput = document.getElementById("amount_paid");

            if (method === "Digital") {
                tunaiDiv.style.display = "none";
                amountInput.required = false;
            } else {
                tunaiDiv.style.display = "block";
                amountInput.required = true;
            }
        }

        // Mencegah form reload saat bayar digital (Single Page action)
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            var method = document.getElementById("payment_method").value;

            if (method === "Digital") {
                e.preventDefault(); // Tahan form agar tidak pindah halaman

                var formData = new FormData(this);

                // Kirim data secara asinkron (AJAX)
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json' // Beri tahu server kita minta JSON
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snapToken) {
                        // Panggil Pop-Up Snap secara instan
                        window.snap.pay(data.snapToken, {
                            onSuccess: function(result){
                                // Arahkan ke halaman Sukses jika berhasil
                                window.location.href = "{{ route('pos.success') }}";
                            },
                            onPending: function(result){
                                alert("Menunggu pembayaran Anda!");
                            },
                            onError: function(result){
                                alert("Pembayaran Gagal!");
                            },
                            onClose: function(){
                                alert('Anda menutup pop-up sebelum menyelesaikan pembayaran');
                            }
                        });
                    } else {
                        alert("Gagal memuat sistem pembayaran.");
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
</x-app-layout>
