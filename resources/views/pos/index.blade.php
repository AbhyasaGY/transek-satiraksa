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

                        <div id="tunai_input_div">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nominal Uang Diterima
                                    (Rp)</label>
                                <input type="number" name="amount_paid" id="amount_paid"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required>
                            </div>

                            <div class="mb-4 p-3 border border-dashed border-gray-400 rounded-lg bg-white text-center">
                                <p class="text-xs text-gray-500 mb-2 font-bold">Wajib arahkan uang ke kamera untuk
                                    validasi</p>
                                <div class="flex justify-center mb-2">
                                    <video id="webcam-video" width="100%" height="auto" autoplay muted playsinline
                                        class="rounded border bg-black hidden"></video>
                                </div>
                                <div id="label-container" class="text-xs font-bold text-red-500 mb-2">Kamera belum aktif
                                </div>
                                <button type="button" onclick="mulaiScanner()" id="btn-start-scan"
                                    class="bg-gray-800 hover:bg-black text-white text-xs font-bold py-1.5 px-3 rounded transition w-full">
                                    📷 Buka Kamera AI
                                </button>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" id="btn-submit-utama"
                                class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed transition shadow-md"
                                disabled>
                                Menunggu Validasi Uang
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
    let isTunaiUnlocked = false;
    const btnSubmitUtama = document.getElementById('btn-submit-utama');

    // --- LOGIKA TOGGLE INPUT ---
    function toggleTunaiInput() {
        var method = document.getElementById("payment_method").value;
        var tunaiDiv = document.getElementById("tunai_input_div");
        var amountInput = document.getElementById("amount_paid");

        if (method === "Digital") {
            tunaiDiv.style.display = "none";
            amountInput.required = false;

            // Bebaskan tombol jika Digital
            btnSubmitUtama.disabled = false;
            btnSubmitUtama.className =
                "w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition shadow-md cursor-pointer";
            btnSubmitUtama.innerText = "Proses Checkout (Midtrans)";

            matikanKamera(); // Matikan kamera jika sedang hidup
        } else {
            tunaiDiv.style.display = "block";
            amountInput.required = true;

            // Kunci lagi tombol jika Tunai dan belum discan
            if (!isTunaiUnlocked) {
                btnSubmitUtama.disabled = true;
                btnSubmitUtama.className =
                    "w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed transition shadow-md";
                btnSubmitUtama.innerText = "Menunggu Validasi Uang";
            }
        }
    }

    // Jalankan sekali saat halaman dimuat untuk set status awal (Tunai default)
    toggleTunaiInput();


    // --- LOGIKA AI KAMERA (METODE API RESMI ROBOFLOW BATCH 2) ---
    const video = document.getElementById("webcam-video");
    const labelContainer = document.getElementById("label-container");
    const btnMulaiScan = document.getElementById("btn-start-scan");

    // Kita buat "kanvas tak kasat mata" untuk memotret video
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");

    async function mulaiScanner() {
        btnMulaiScan.style.display = "none";
        video.classList.remove('hidden');
        labelContainer.innerText = "Nyalakan Kamera...";
        labelContainer.className = "text-xs font-bold text-yellow-600 mb-2";

        // 1. Minta Izin Kamera
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                video.srcObject = stream;
            } catch (err) {
                labelContainer.innerText = "Izin kamera ditolak.";
                labelContainer.className = "text-xs font-bold text-red-600 mb-2";
                return;
            }
        }

        labelContainer.innerText = "🤖 Tempelkan Uang ke Kamera";
        labelContainer.className = "text-xs font-bold text-indigo-600 mb-2";

        // 2. Lakukan Pemotretan Setiap 1 Detik
        let scanInterval = setInterval(function() {
            // Hentikan jika sudah sukses atau ganti metode
            if (isTunaiUnlocked || document.getElementById("payment_method").value !== 'Uang Tunai') {
                clearInterval(scanInterval);
                return;
            }

            // Pastikan kamera sudah benar-benar terbuka
            if (video.videoWidth === 0) return;

            // Samakan ukuran kanvas dengan resolusi kamera
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Jepret gambar dari tag <video> ke dalam <canvas>
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Ubah gambar menjadi format Base64 (Hapus awalan header formatnya)
            let base64Image = canvas.toDataURL("image/jpeg").split(",")[1];

            // 3. Kirim ke Server Laravel Sendiri (Bebas CORS!)
            axios.post("{{ route('api.scan-kamera') }}", {
                    image: base64Image
                }, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Kunci gembok wajib Laravel
                    }
                })
                .then(function(response) {
                    let predictions = response.data.predictions;
                    console.log("Log AI: ", predictions); // Cek isi kepala AI di Console

                    // Jika ada uang terdeteksi dengan keyakinan di atas 75% (0.75)
                    if (predictions && predictions.length > 0 && predictions[0].confidence > 0.75) {
                        clearInterval(scanInterval); // Hentikan kamera memotret
                        bukaKunciTunai(predictions[0].class); // Buka gembok kasir
                    }
                })
                .catch(function(error) {
                    console.error("Error Komunikasi Laravel: ", error);
                });

        }, 1000); // 1000 ms = 1 detik sekali agar tidak membebani limit API
    }

    function bukaKunciTunai(namaUang) {
        isTunaiUnlocked = true;
        matikanKamera();
        video.classList.add('hidden');

        labelContainer.innerText = "✅ SAH! (" + namaUang + ")";
        labelContainer.className = "text-sm font-extrabold text-green-600 mb-2";

        btnSubmitUtama.disabled = false;
        btnSubmitUtama.className =
            "w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition shadow-md cursor-pointer";
        btnSubmitUtama.innerText = "Selesaikan Transaksi Tunai";
    }

    function matikanKamera() {
        if (video.srcObject) {
            let stream = video.srcObject;
            let tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        }
    }


    // --- LOGIKA AJAX FORM SUBMIT (ASLI BAWAAN ANDA) ---
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        var method = document.getElementById("payment_method").value;

        if (method === "Digital") {
            e.preventDefault(); // Tahan form untuk AJAX Midtrans
            var formData = new FormData(this);

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snapToken) {
                        window.snap.pay(data.snapToken, {
                            onSuccess: function(result) {
                                window.location.href = "{{ route('pos.success') }}";
                            },
                            onPending: function(result) {
                                alert("Menunggu pembayaran Anda!");
                            },
                            onError: function(result) {
                                alert("Pembayaran Gagal!");
                            },
                            onClose: function() {
                                alert('Anda menutup pop-up sebelum menyelesaikan pembayaran');
                            }
                        });
                    } else {
                        alert("Gagal memuat sistem pembayaran.");
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        // Jika Uang Tunai, biarkan browser melanjutkan submit biasa (tanpa preventDefault)
        // yang akan diarahkan ke controller dan pindah ke halaman sukses.
    });
    </script>
</x-app-layout>
