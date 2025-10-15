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
                        <button id="start-capture-btn" class="w-full bg-gradient-to-r from-camture-beige to-camture-rose text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 text-xl shadow-lg hover:shadow-xl hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed">
                            Mulai Sesi Foto!
                        </button>
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
                    
                    // Remove previous filter class from video
                    webcamPreview.classList.remove(currentFilter);
                    
                    // Remove active style from all buttons
                    filterOptions.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active-filter'));
                    
                    // Add new filter and active style
                    currentFilter = filter;
                    webcamPreview.classList.add(currentFilter);
                    e.target.classList.add('active-filter');

                    // Add/Remove Vignette effect for Lomo
                    if (filter === 'filter-lomo') {
                        cameraContainer.classList.add('vignette');
                    } else {
                        cameraContainer.classList.remove('vignette');
                    }
                }
            });

            // === CAPTURE PROCESS ===
            startCaptureBtn.addEventListener('click', startCaptureProcess);

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
                    statusText.textContent = 'Sesi selesai! Mengarahkan ke halaman hasil...';
                    // Di sini kita akan mengirim data ke server
                    saveAndRedirect();
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