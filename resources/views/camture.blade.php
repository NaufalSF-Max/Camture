{{-- Menggunakan layout utama aplikasi --}}
<x-app-layout>
    {{-- Menetapkan judul halaman --}}
    @section('title', 'Photobooth')
    {{-- Mengisi slot header di layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-camture-green-dark leading-tight">
            Camture Photobooth
        </h2>
    </x-slot>

    {{-- Penambahan CSS kustom untuk filter --}}
    <style>
        /* --- Definisi Kelas Filter CSS --- */
        .filter-none { filter: none; }
        .filter-grayscale { filter: grayscale(100%); }
        .filter-sepia { filter: sepia(100%); }
        .filter-invert { filter: invert(100%); }
        .filter-bright { filter: brightness(1.3) contrast(1.1); }
        .filter-vintage { filter: sepia(60%) contrast(1.2) brightness(90%) saturate(1.2); }
        .filter-saturate { filter: saturate(200%); }
        .filter-hue-rotate { filter: hue-rotate(90deg); }
        .filter-contrast-high { filter: contrast(160%); }
        .filter-nashville { filter: sepia(20%) contrast(150%) brightness(90%) hue-rotate(-15deg); }
        .filter-gingham { filter: contrast(90%) brightness(110%); }
        .filter-clarendon { filter: contrast(120%) saturate(135%); }

        /* Filter dengan Overlay (Dreamy, Neo Noir, Sunrise) */
        /* Menggunakan pseudo-element ::before pada container kamera */
        .camera-container.filter-dreamy::before {
            /* Gaya overlay pink lembut */
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: #f3d7e2; mix-blend-mode: screen; opacity: 0.4;
            z-index: 10; pointer-events: none; /* Agar tidak menghalangi video */
        }
        .filter-dreamy { filter: brightness(110%) contrast(110%); } /* Filter dasar pada video */

        .camera-container.filter-neo-noir::before {
            /* Gaya overlay gradient merah-biru */
             content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to top right, rgba(255, 0, 0, 0.4), rgba(0, 0, 255, 0.4));
            mix-blend-mode: screen; opacity: 0.6;
             z-index: 10; pointer-events: none;
        }
        .filter-neo-noir { filter: grayscale(100%) contrast(150%) brightness(90%); }

        .camera-container.filter-sunrise::before {
            /* Gaya overlay gradient oranye-pink */
             content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to bottom, #ffcda4, #ff93a2);
            mix-blend-mode: overlay; opacity: 0.5;
             z-index: 10; pointer-events: none;
        }
        .filter-sunrise { filter: saturate(140%) contrast(110%); }

        /* Filter Lomo */
        .filter-lomo {
            filter: contrast(1.4) saturate(1.4) sepia(30%);
            position: relative; /* Diperlukan agar ::after bisa diposisikan */
        }
        /* Efek Vignette (gelap di pinggir) untuk Lomo */
        .camera-container::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            border-radius: 0.5rem; /* Sesuaikan dengan radius container */
            box-shadow: inset 0 0 80px rgba(0,0,0,0.7); /* Efek shadow ke dalam */
            pointer-events: none; opacity: 0; /* Sembunyi default */
            transition: opacity 0.3s ease-in-out;
        }
        .camera-container.vignette::after {
            opacity: 1; /* Tampilkan vignette jika container punya kelas 'vignette' */
        }

        /* --- Styling Tombol Filter & Efek --- */
        .filter-btn { /* Gaya dasar tombol filter */
            padding: 0.5rem 1rem; border: 2px solid #EA9AB2; border-radius: 9999px;
            font-weight: 600; color: #E27396; background-color: white;
            transition: all 0.2s ease-in-out; cursor: pointer;
        }
        .filter-btn:hover { background-color: #FFDBE5; } /* Hover */
        .filter-btn.active-filter { /* Gaya tombol filter aktif */
            background-color: #E27396; color: white; border-color: #E27396;
        }

        /* Container kamera */
        .camera-container { position: relative; }

        /* Efek flash saat foto diambil */
        .flash-effect { animation: flash 0.5s ease-out; }
        @keyframes flash { /* Animasi flash putih */
            0% { background-color: rgba(255, 255, 255, 0); }
            50% { background-color: rgba(255, 255, 255, 0.8); }
            100% { background-color: rgba(255, 255, 255, 0); }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Overlay untuk Hitung Mundur (hidden default) --}}
            <div id="countdown-overlay" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
                <span id="countdown-text" class="text-white font-bold text-9xl animate-ping"></span>
            </div>

            {{-- Kontainer Utama Photobooth --}}
            <div class="bg-camture-pink-bg overflow-hidden shadow-2xl sm:rounded-2xl p-8 flex flex-col md:flex-row gap-8">

                {{-- Kolom Kiri: Preview Kamera --}}
                <div class="w-full md:w-2/3 flex flex-col items-center justify-center">
                    {{-- Container video dengan style filter & overlay --}}
                    <div id="camera-container" class="relative w-full max-w-2xl aspect-[4/3] rounded-lg overflow-hidden bg-camture-green-dark border-4 border-camture-rose shadow-lg transition-all duration-300">
                        {{-- Elemen video untuk menampilkan stream webcam --}}
                        <video id="webcam-preview" class="w-full h-full object-cover" autoplay playsinline></video>
                        {{-- Overlay untuk efek flash --}}
                        <div id="flash-overlay" class="absolute inset-0 pointer-events-none"></div>
                    </div>
                    {{-- Teks status (Kamera siap, Menghitung mundur, dll.) --}}
                    <p id="status-text" class="font-medium text-center mt-4 text-camture-green-dark h-6"></p>
                </div>

                {{-- Kolom Kanan: Kontrol & Hasil Jepretan --}}
                <div class="w-full md:w-1/3 flex flex-col">

                    {{-- Kontrol Awal (sebelum sesi dimulai) --}}
                    <div id="initial-controls">
                        {{-- Info template terpilih --}}
                        <p class="text-camture-green-dark mb-4">Layout Terpilih:
                            <strong class="text-camture-rose font-bold">{{ $template->name }}</strong> ({{ $template->capture_slots }} foto)
                        </p>

                        {{-- Opsi Waktu Mundur --}}
                        <div class="mb-4">
                            <label for="countdown-time" class="block font-medium text-camture-green-dark mb-1">Waktu Mundur</label>
                            <select id="countdown-time" class="w-full rounded-md border-camture-beige shadow-sm focus:border-camture-rose focus:ring focus:ring-camture-rose focus:ring-opacity-50 text-camture-green-dark">
                                <option value="3">3 Detik</option>
                                <option value="5" selected>5 Detik</option>
                                <option value="10">10 Detik</option>
                            </select>
                        </div>

                        {{-- Opsi Filter --}}
                        <div>
                            <label class="block font-medium text-camture-green-dark mb-2">Pilih Filter</label>
                            <div id="filter-options" class="flex flex-wrap justify-start gap-2">
                                {{-- Tombol-tombol filter (data-filter menyimpan nama kelas CSS filter) --}}
                                <button class="filter-btn active-filter" data-filter="filter-none">Normal</button>
                                <button class="filter-btn" data-filter="filter-bright">Cerah</button>
                                <button class="filter-btn" data-filter="filter-vintage">Vintage</button>
                                <button class="filter-btn" data-filter="filter-lomo">Lomo</button>
                                <button class="filter-btn" data-filter="filter-grayscale">B&W</button>
                                <button class="filter-btn" data-filter="filter-sepia">Sepia</button>
                                <button class="filter-btn" data-filter="filter-invert">Invert</button>
                                <button class="filter-btn" data-filter="filter-saturate">Saturasi</button>
                                <button class="filter-btn" data-filter="filter-hue-rotate">Warna-Warni</button>
                                <button class="filter-btn" data-filter="filter-contrast-high">Kontras</button>
                                <button class="filter-btn" data-filter="filter-nashville">Nashville</button>
                                <button class="filter-btn" data-filter="filter-gingham">Gingham</button>
                                <button class="filter-btn" data-filter="filter-clarendon">Clarendon</button>
                                <button class="filter-btn" data-filter="filter-dreamy">Dreamy</button>
                                <button class="filter-btn" data-filter="filter-neo-noir">Neo Noir</button>
                                <button class="filter-btn" data-filter="filter-sunrise">Sunrise</button>
                            </div>
                        </div>
                    </div>

                    {{-- Panel Hasil Jepretan (thumbnail) --}}
                    <div id="preview-panel" class="mt-6 flex-grow">
                        <h3 class="font-bold text-lg text-camture-rose">Hasil Jepretan:</h3>
                        {{-- Grid untuk menampilkan thumbnail --}}
                        <div id="preview-thumbnails" class="grid grid-cols-2 gap-4 mt-2">
                            {{-- Thumbnails akan ditambahkan di sini oleh JavaScript --}}
                        </div>
                    </div>

                    {{-- Tombol Aksi (Mulai/Simpan/Ulangi) --}}
                    <div class="mt-auto pt-6 text-center">
                        {{-- Tombol Mulai Sesi (tampil di awal) --}}
                        <button id="start-capture-btn" class="w-full bg-camture-rose hover:bg-camture-rose-hover text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 text-xl shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            Mulai Sesi Foto!
                        </button>
                        {{-- Kontrol Setelah Sesi Selesai (Simpan/Ulangi - hidden default) --}}
                        <div id="review-controls" class="hidden space-y-3">
                            <button id="save-btn" class="w-full bg-camture-rose hover:bg-camture-rose-hover text-white font-bold py-3 px-6 rounded-lg text-xl shadow-lg">
                                Simpan & Lanjutkan
                            </button>
                            <button id="retake-btn" type="button" class="w-full bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-300">
                                Ulangi Sesi Foto
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Referensi Elemen DOM ===
            const webcamPreview = document.getElementById('webcam-preview');
            const startCaptureBtn = document.getElementById('start-capture-btn');
            const reviewControls = document.getElementById('review-controls');
            const saveBtn = document.getElementById('save-btn');
            const retakeBtn = document.getElementById('retake-btn');
            const countdownOverlay = document.getElementById('countdown-overlay');
            const countdownText = document.getElementById('countdown-text');
            const statusText = document.getElementById('status-text');
            const filterOptions = document.getElementById('filter-options');
            const previewThumbnails = document.getElementById('preview-thumbnails');
            const flashOverlay = document.getElementById('flash-overlay');
            const cameraContainer = document.getElementById('camera-container'); // Container video

            // === Variabel State ===
            let stream; // Stream video dari webcam
            let currentFilter = 'filter-none'; // Filter aktif saat ini
            const CAPTURE_SLOTS = {{ $template->capture_slots }}; // Jumlah foto yang dibutuhkan (dari PHP)
            let capturedImages = []; // Array untuk menyimpan data base64 gambar
            let captureCount = 0; // Penghitung foto yang sudah diambil

            // === Inisialisasi Webcam ===
            async function initWebcam() {
                try {
                    // Minta akses kamera
                    stream = await navigator.mediaDevices.getUserMedia({ video: { width: 1280, height: 720 }, audio: false });
                    webcamPreview.srcObject = stream; // Tampilkan stream di elemen video
                    statusText.textContent = "Kamera siap!";
                } catch (err) {
                    console.error("Error accessing webcam: ", err);
                    statusText.textContent = "Error: Tidak dapat mengakses kamera.";
                    startCaptureBtn.disabled = true; // Nonaktifkan tombol mulai jika kamera error
                }
            }

            // === Logika Pemilihan Filter ===
            filterOptions.addEventListener('click', function(e) {
                // Hanya proses jika yang diklik adalah tombol filter
                if (e.target.classList.contains('filter-btn')) {
                    const filter = e.target.dataset.filter; // Ambil nama kelas filter dari data-attribute

                    // Reset: Hapus semua kelas filter dari video dan container
                    webcamPreview.className = 'w-full h-full object-cover'; // Reset kelas video
                    // Hapus kelas filter khusus (overlay/vignette) dari container
                    cameraContainer.classList.remove('vignette', 'filter-dreamy', 'filter-neo-noir', 'filter-sunrise');

                    // Reset: Hapus style aktif dari semua tombol filter
                    filterOptions.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active-filter'));

                    // Terapkan: Tambahkan kelas filter baru ke video dan tombol
                    currentFilter = filter; // Simpan filter aktif (meski tidak dipakai langsung saat capture)
                    webcamPreview.classList.add(currentFilter); // Terapkan filter ke video
                    e.target.classList.add('active-filter'); // Tandai tombol sebagai aktif

                    // Terapkan (Khusus): Tambahkan kelas ke container jika filter butuh overlay/vignette
                    if (filter === 'filter-lomo') {
                        cameraContainer.classList.add('vignette');
                    } else if (filter === 'filter-dreamy' || filter === 'filter-neo-noir' || filter === 'filter-sunrise') {
                        cameraContainer.classList.add(filter); // Aktifkan pseudo-element CSS
                    }
                }
            });

            // === Proses Pengambilan Foto (Capture) ===
            startCaptureBtn.addEventListener('click', startCaptureProcess); // Mulai saat tombol diklik

            // Tombol Simpan setelah selesai
            saveBtn.addEventListener('click', () => {
                statusText.textContent = 'Menyimpan...';
                saveAndRedirect(); // Kirim data ke backend
            });

            // Tombol Ulangi setelah selesai
            retakeBtn.addEventListener('click', () => {
                // Reset state ke awal
                capturedImages = [];
                captureCount = 0;
                previewThumbnails.innerHTML = ''; // Kosongkan thumbnail
                statusText.textContent = 'Kamera siap!';

                // Tampilkan/sembunyikan tombol yang sesuai
                reviewControls.classList.add('hidden'); // Sembunyikan Simpan/Ulangi
                startCaptureBtn.classList.remove('hidden'); // Tampilkan Mulai Sesi
                startCaptureBtn.disabled = false;
                startCaptureBtn.textContent = 'Mulai Sesi Foto!';
            });

            // Fungsi utama untuk memulai urutan pengambilan foto
            function startCaptureProcess() {
                startCaptureBtn.disabled = true;
                startCaptureBtn.textContent = 'Bersiap...';
                captureCount = 0; // Reset counter
                capturedImages = []; // Kosongkan array gambar
                previewThumbnails.innerHTML = ''; // Kosongkan thumbnail
                takePhotoLoop(); // Mulai loop pengambilan foto
            }

            // Loop untuk mengambil foto sesuai jumlah slot
            function takePhotoLoop() {
                // Hentikan loop jika sudah mencapai jumlah slot
                if (captureCount >= CAPTURE_SLOTS) {
                    statusText.textContent = 'Sesi selesai! Silakan review fotomu.';
                    startCaptureBtn.classList.add('hidden'); // Sembunyikan tombol Mulai
                    reviewControls.classList.remove('hidden'); // Tampilkan tombol Simpan/Ulangi
                    return;
                }

                captureCount++; // Tambah counter foto
                const countdownTime = parseInt(document.getElementById('countdown-time').value, 10); // Ambil waktu mundur

                statusText.textContent = `Pose ke-${captureCount} dari ${CAPTURE_SLOTS}...`; // Update status

                // --- Hitung Mundur ---
                let count = countdownTime;
                countdownOverlay.classList.remove('hidden'); // Tampilkan overlay
                countdownText.textContent = count; // Tampilkan angka awal

                const countdownInterval = setInterval(() => {
                    count--;
                    countdownText.textContent = count > 0 ? count : '📸'; // Tampilkan angka atau ikon kamera
                    if (count <= 0) { // Jika hitungan selesai
                        clearInterval(countdownInterval); // Hentikan interval
                        captureImage(); // Ambil gambar
                        // Tunggu sebentar setelah capture sebelum menyembunyikan overlay & lanjut loop
                        setTimeout(() => {
                            countdownOverlay.classList.add('hidden');
                            // Jeda 2 detik sebelum memulai hitung mundur foto berikutnya
                            setTimeout(takePhotoLoop, 2000);
                        }, 1000); // Overlay tampil 1 detik setelah capture
                    }
                }, 1000); // Interval 1 detik
            }

            // Fungsi untuk mengambil satu frame gambar dari video
            function captureImage() {
                // Efek flash
                flashOverlay.classList.add('flash-effect');
                setTimeout(() => flashOverlay.classList.remove('flash-effect'), 500);

                // Buat canvas sementara
                const canvas = document.createElement('canvas');
                canvas.width = webcamPreview.videoWidth; // Sesuaikan ukuran canvas dengan video
                canvas.height = webcamPreview.videoHeight;
                const ctx = canvas.getContext('2d');

                // TERAPKAN FILTER KE CANVAS!
                // Baca filter CSS yang sedang aktif di elemen video
                ctx.filter = window.getComputedStyle(webcamPreview).filter;
                // Gambar frame video saat ini ke canvas (filter akan ikut tergambar)
                ctx.drawImage(webcamPreview, 0, 0, canvas.width, canvas.height);

                // Konversi canvas ke data URL (base64 JPEG)
                const dataUrl = canvas.toDataURL('image/jpeg');
                capturedImages.push(dataUrl); // Simpan data base64

                // Tampilkan thumbnail
                const thumb = document.createElement('img');
                thumb.src = dataUrl;
                thumb.className = 'w-full h-full object-cover rounded-md';
                const thumbWrapper = document.createElement('div');
                thumbWrapper.className = 'aspect-square bg-camture-pink-bg rounded-lg overflow-hidden shadow-md p-1';
                thumbWrapper.appendChild(thumb);
                previewThumbnails.appendChild(thumbWrapper);
            }

            // === Kirim Data ke Backend & Redirect ===
            async function saveAndRedirect() {
                try {
                    // Kirim data via fetch API
                    const response = await fetch('{{ route("camture.capture") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Jangan lupa CSRF token
                        },
                        body: JSON.stringify({
                            images: capturedImages, // Array data base64 gambar
                            template_id: {{ $template->id }} // ID template
                        })
                    });

                    // Cek jika response tidak OK (status bukan 2xx)
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    // Parse response JSON dari backend
                    const result = await response.json();

                    // Jika backend merespon sukses dan ada URL redirect
                    if (result.success && result.redirect_url) {
                        window.location.href = result.redirect_url; // Redirect ke halaman hasil
                    } else {
                        // Tampilkan pesan error dari backend atau pesan default
                        statusText.textContent = 'Error: ' + (result.message || 'Gagal menyimpan foto.');
                        saveBtn.disabled = false; // Aktifkan kembali tombol jika gagal
                        retakeBtn.disabled = false;
                    }
                } catch (error) {
                    console.error('Error saving photos:', error);
                    statusText.textContent = 'Error: Terjadi kesalahan saat menyimpan.';
                     saveBtn.disabled = false; // Aktifkan kembali tombol jika gagal
                     retakeBtn.disabled = false;
                }
            }

            // === Mulai ===
            initWebcam(); // Inisialisasi webcam saat halaman dimuat
        });
    </script>
    @endpush
</x-app-layout>