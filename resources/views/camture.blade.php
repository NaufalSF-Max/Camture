<x-app-layout>
    @section('title', 'Photobooth')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-camture-green-dark leading-tight">
            Camture Photobooth
        </h2>
    </x-slot>

    {{-- Penambahan CSS kustom untuk filter yang lebih kompleks --}}
    <style>
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
        /* 1. Gingham: Efek film klasik, sedikit pudar */
        .filter-gingham { filter: contrast(90%) brightness(110%); }

        /* 2. Clarendon: Mencerahkan dan meningkatkan warna biru */
        .filter-clarendon { filter: contrast(120%) saturate(135%); }

        /* 3. Dreamy: Efek lembut dengan sentuhan pink */
        .camera-container.filter-dreamy::before {
            background-color: #f3d7e2;
            mix-blend-mode: screen; /* Kunci efeknya ada di sini */
            opacity: 0.4;
        }
        .filter-dreamy { filter: brightness(110%) contrast(110%); }

        /* 4. Neo Noir: Kontras tinggi dengan nuansa merah gelap */
        .camera-container.filter-neo-noir::before {
            background: linear-gradient(to top right, rgba(255, 0, 0, 0.4), rgba(0, 0, 255, 0.4));
            mix-blend-mode: screen;
            opacity: 0.6;
        }
        .filter-neo-noir { filter: grayscale(100%) contrast(150%) brightness(90%); }

        /* 5. Sunrise: Efek hangat keemasan */
        .camera-container.filter-sunrise::before {
            background: linear-gradient(to bottom, #ffcda4, #ff93a2);
            mix-blend-mode: overlay;
            opacity: 0.5;
        }
        .filter-sunrise { filter: saturate(140%) contrast(110%); }
        .filter-lomo { 
            filter: contrast(1.4) saturate(1.4) sepia(30%); 
            position: relative;
        }
        .filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #EA9AB2; /* Amaranth Pink */
            border-radius: 9999px; /* Pill shape */
            font-weight: 600;
            color: #E27396; /* Rose Pompadour */
            background-color: white;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .filter-btn:hover {
            background-color: #FFDBE5; /* Mimi Pink on hover */
        }
        .filter-btn.active-filter {
            background-color: #E27396; /* Rose Pompadour */
            color: white;
            border-color: #E27396;
        }
        .camera-container {
            /* Pastikan container ini relative agar overlay berfungsi */
            position: relative; 
        }
        .camera-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            pointer-events: none; /* Agar tidak menghalangi video */
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .camera-container::after { /* Efek Vignette untuk Lomo */
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border-radius: 0.5rem; /* sesuaikan dengan radius container */
            box-shadow: inset 0 0 80px rgba(0,0,0,0.7);
            pointer-events: none; /* agar tidak mengganggu interaksi video */
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .camera-container.vignette::after {
            opacity: 1;
        }
        .flash-effect {
            animation: flash 0.5s ease-out;
        }
        @keyframes flash {
            0% { background-color: rgba(255, 255, 255, 0); }
            50% { background-color: rgba(255, 255, 255, 0.8); }
            100% { background-color: rgba(255, 255, 255, 0); }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Overlay untuk Hitung Mundur -->
            <div id="countdown-overlay" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
                <span id="countdown-text" class="text-white font-bold text-9xl animate-ping"></span>
            </div>

            <!-- Kontainer Utama Photobooth -->
            <div class="bg-camture-pink-bg overflow-hidden shadow-2xl sm:rounded-2xl p-8 flex flex-col md:flex-row gap-8">

                <!-- Kolom Kiri: Preview Kamera -->
                <div class="w-full md:w-2/3 flex flex-col items-center justify-center">
                    <div id="camera-container" class="relative w-full max-w-2xl aspect-[4/3] rounded-lg overflow-hidden bg-camture-green-dark border-4 border-camture-rose shadow-lg transition-all duration-300">
                        <video id="webcam-preview" class="w-full h-full object-cover" autoplay playsinline></video>
                        <div id="flash-overlay" class="absolute inset-0 pointer-events-none"></div>
                    </div>
                    <p id="status-text" class="font-medium text-center mt-4 text-camture-green-dark h-6"></p>
                </div>

                <!-- Kolom Kanan: Kontrol & Hasil Jepretan -->
                <div class="w-full md:w-1/3 flex flex-col">
                    
                    <!-- Kontrol Awal -->
                    <div id="initial-controls">
                        <p class="text-camture-green-dark mb-4">Layout Terpilih: 
                            <strong class="text-camture-rose font-bold">{{ $template->name }}</strong> ({{ $template->capture_slots }} foto)
                        </p>

                        <div class="mb-4">
                            <label for="countdown-time" class="block font-medium text-camture-green-dark mb-1">Waktu Mundur</label>
                            <select id="countdown-time" class="w-full rounded-md border-camture-beige shadow-sm focus:border-camture-rose focus:ring focus:ring-camture-rose focus:ring-opacity-50 text-camture-green-dark">
                                <option value="3">3 Detik</option>
                                <option value="5" selected>5 Detik</option>
                                <option value="10">10 Detik</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block font-medium text-camture-green-dark mb-2">Pilih Filter</label>
                            <div id="filter-options" class="flex flex-wrap justify-start gap-2">
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
                    
                    <!-- Panel Hasil Jepretan -->
                    <div id="preview-panel" class="mt-6 flex-grow">
                        <h3 class="font-bold text-lg text-camture-rose">Hasil Jepretan:</h3>
                        <div id="preview-thumbnails" class="grid grid-cols-2 gap-4 mt-2">
                            {{-- Thumbnails akan ditambahkan di sini oleh JavaScript --}}
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-auto pt-6 text-center">
                        <button id="start-capture-btn" class="w-full bg-camture-rose hover:bg-camture-rose-hover text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 text-xl shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            Mulai Sesi Foto!
                        </button>
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
            // === DOM ELEMENTS ===
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
            const cameraContainer = document.getElementById('camera-container');
            
            // === STATE VARIABLES ===
            let stream;
            let currentFilter = 'filter-none';
            const CAPTURE_SLOTS = {{ $template->capture_slots }};
            let capturedImages = [];
            let captureCount = 0;

            // === INITIALIZE WEBCAM ===
            async function initWebcam() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: { width: 1280, height: 720 }, audio: false });
                    webcamPreview.srcObject = stream;
                    statusText.textContent = "Kamera siap!";
                } catch (err) {
                    console.error("Error accessing webcam: ", err);
                    statusText.textContent = "Error: Tidak dapat mengakses kamera.";
                    startCaptureBtn.disabled = true;
                }
            }

            // === FILTER LOGIC ===
            filterOptions.addEventListener('click', function(e) {
                if (e.target.classList.contains('filter-btn')) {
                    const filter = e.target.dataset.filter;
                    
                    // Hapus semua kelas filter sebelumnya dari video dan container
                    webcamPreview.className = 'w-full h-full object-cover'; // Reset kelas video
                    cameraContainer.classList.remove('vignette', 'filter-dreamy', 'filter-neo-noir', 'filter-sunrise'); // Hapus semua kelas overlay
                    
                    // Hapus style aktif dari semua tombol
                    filterOptions.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active-filter'));
                    
                    // Terapkan filter baru
                    currentFilter = filter;
                    webcamPreview.classList.add(currentFilter);
                    e.target.classList.add('active-filter');

                    // Logika khusus untuk filter yang menggunakan overlay atau efek pada container
                    if (filter === 'filter-lomo') {
                        cameraContainer.classList.add('vignette');
                    } else if (filter === 'filter-dreamy' || filter === 'filter-neo-noir' || filter === 'filter-sunrise') {
                        // Untuk filter overlay, tambahkan kelas ke container, bukan hanya ke video
                        cameraContainer.classList.add(filter);
                    }
                }
            });

            // === CAPTURE PROCESS ===
            startCaptureBtn.addEventListener('click', startCaptureProcess);

            saveBtn.addEventListener('click', () => {
                statusText.textContent = 'Menyimpan...';
                saveAndRedirect();
            });

            retakeBtn.addEventListener('click', () => {
                // Reset semua state
                capturedImages = [];
                captureCount = 0;
                previewThumbnails.innerHTML = '';
                statusText.textContent = 'Kamera siap!';
                
                // Tampilkan/sembunyikan tombol yang benar
                reviewControls.classList.add('hidden');
                startCaptureBtn.classList.remove('hidden');
                startCaptureBtn.disabled = false;
                startCaptureBtn.textContent = 'Mulai Sesi Foto!';
            });

            function startCaptureProcess() {
                startCaptureBtn.disabled = true;
                startCaptureBtn.textContent = 'Bersiap...';
                captureCount = 0;
                capturedImages = [];
                previewThumbnails.innerHTML = ''; // Clear previous thumbnails
                takePhotoLoop();
            }

            function takePhotoLoop() {
                if (captureCount >= CAPTURE_SLOTS) {
                    statusText.textContent = 'Sesi selesai! Silakan review fotomu.';
                    startCaptureBtn.classList.add('hidden'); // Sembunyikan tombol mulai
                    reviewControls.classList.remove('hidden'); // Tampilkan tombol review
                    return;
                }

                captureCount++;
                const countdownTime = parseInt(document.getElementById('countdown-time').value, 10);
                
                statusText.textContent = `Pose ke-${captureCount} dari ${CAPTURE_SLOTS}...`;
                
                let count = countdownTime;
                countdownOverlay.classList.remove('hidden');
                countdownText.textContent = count;
                
                const countdownInterval = setInterval(() => {
                    count--;
                    countdownText.textContent = count > 0 ? count : '📸';
                    if (count <= 0) {
                        clearInterval(countdownInterval);
                        captureImage();
                        setTimeout(() => {
                            countdownOverlay.classList.add('hidden');
                            // Jeda sebelum foto berikutnya
                            setTimeout(takePhotoLoop, 2000); 
                        }, 1000);
                    }
                }, 1000);
            }

            function captureImage() {
                flashOverlay.classList.add('flash-effect');
                setTimeout(() => flashOverlay.classList.remove('flash-effect'), 500);

                const canvas = document.createElement('canvas');
                canvas.width = webcamPreview.videoWidth;
                canvas.height = webcamPreview.videoHeight;
                const ctx = canvas.getContext('2d');
                
                // Terapkan filter ke canvas
                ctx.filter = window.getComputedStyle(webcamPreview).filter;
                ctx.drawImage(webcamPreview, 0, 0, canvas.width, canvas.height);
                
                const dataUrl = canvas.toDataURL('image/jpeg');
                capturedImages.push(dataUrl);

                // Tampilkan thumbnail
                const thumb = document.createElement('img');
                thumb.src = dataUrl;
                thumb.className = 'w-full h-full object-cover rounded-md';
                const thumbWrapper = document.createElement('div');
                thumbWrapper.className = 'aspect-square bg-camture-pink-bg rounded-lg overflow-hidden shadow-md p-1';
                thumbWrapper.appendChild(thumb);
                previewThumbnails.appendChild(thumbWrapper);
            }

            // === SAVE AND REDIRECT ===
            async function saveAndRedirect() {
                try {
                    const response = await fetch('{{ route("camture.capture") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            images: capturedImages,
                            template_id: {{ $template->id }}
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const result = await response.json();

                    if (result.success && result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        statusText.textContent = 'Error: ' + (result.message || 'Gagal menyimpan foto.');
                        startCaptureBtn.disabled = false;
                        startCaptureBtn.textContent = 'Coba Lagi';
                    }
                } catch (error) {
                    console.error('Error saving photos:', error);
                    statusText.textContent = 'Error: Terjadi kesalahan saat menyimpan.';
                    startCaptureBtn.disabled = false;
                    startCaptureBtn.textContent = 'Coba Lagi';
                }
            }

            // === START ===
            initWebcam();
        });
    </script>
    @endpush
</x-app-layout>