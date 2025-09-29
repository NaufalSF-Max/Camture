<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Camture Photobooth
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="countdown-overlay" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
                <p id="countdown-text" class="text-white font-bold text-9xl animate-ping"></p>
            </div>

            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg p-8 flex flex-col md:flex-row gap-8">

                <div class="w-full md:w-1/2 flex items-center justify-center">
                    <div id="camera-container">
                        <video id="webcam-preview" autoplay playsinline></video>
                    </div>
                </div>

                <div class="w-full md:w-1/2 flex flex-col">
                    
                    <div id="initial-controls">
                        <p class="mb-2">Layout Terpilih: <strong>{{ $template->name }} ({{ $template->capture_slots }} foto)</strong></p>
                        <input type="hidden" id="selected_template_id" value="{{ $template->id }}">
                        <input type="hidden" id="capture_slots_count" value="{{ $template->capture_slots }}">

                        <div class="mt-4">
                            <label for="countdown_time" class="mr-2 font-medium text-gray-700">Waktu Mundur:</label>
                            <select id="countdown_time" class="rounded-md border-gray-300 shadow-sm">
                                <option value="3">3 detik</option>
                                <option value="5">5 detik</option>
                            </select>
                        </div>
                        <div class="mt-6">
                            <p class="mb-2 font-medium text-gray-700">Pilih Filter:</p>
                            <div id="filter-options" class="flex flex-wrap justify-start gap-2">
                                <button data-filter="none" class="filter-btn active">No Filter</button>
                                <button data-filter="grayscale(100%)" class="filter-btn">B&W</button>
                                <button data-filter="sepia(100%)" class="filter-btn">Sepia</button>
                                <button data-filter="saturate(2)" class="filter-btn">Vivid</button>
                                <button data-filter="contrast(150%)" class="filter-btn">Noir</button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="preview-panel" class="hidden">
                        <h3 class="font-bold text-lg mb-4">Hasil Jepretan:</h3>
                        <div id="preview-thumbnails" class="grid grid-cols-2 gap-4">
                            </div>
                    </div>

                    <div class="mt-auto pt-6">
                        <button id="start-capture-btn" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-lg transition-colors text-xl shadow">
                            Start Capture ({{ $template->capture_slots }} foto)
                        </button>
                        <p id="status-text" class="text-center mt-4 text-gray-600 font-medium"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <canvas id="hidden-canvas" class="hidden"></canvas>

    <style>
        #camera-container {
            width: 100%;
            max-width: 480px; 
            aspect-ratio: 1 / 1; 
            border-radius: 0.5rem;
            overflow: hidden;
            background-color: #111827; 
            border: 4px solid #e5e7eb;
        }
        #webcam-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .filter-btn { padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 20px; cursor: pointer; background-color: white; font-size: 14px; }
        .filter-btn.active { background-color: #3b82f6; color: white; border-color: #3b82f6; }
    </style>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    
        // Inisialisasi semua elemen yang dibutuhkan
        const webcamPreview = document.getElementById('webcam-preview');
        const startCaptureBtn = document.getElementById('start-capture-btn');
        const hiddenCanvas = document.getElementById('hidden-canvas');
        const filterOptions = document.getElementById('filter-options');
        const countdownOverlay = document.getElementById('countdown-overlay');
        const countdownText = document.getElementById('countdown-text');
        const initialControls = document.getElementById('initial-controls');
        const previewPanel = document.getElementById('preview-panel');
        const previewThumbnails = document.getElementById('preview-thumbnails');
        const statusText = document.getElementById('status-text');
        
        const ctx = hiddenCanvas.getContext('2d');
        let capturedFrames = [];

        const delay = ms => new Promise(res => setTimeout(res, ms));

        async function startWebcam() {
            if (!webcamPreview) {
                console.error("Elemen video #webcam-preview tidak ditemukan!");
                return;
            }
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                webcamPreview.srcObject = stream;
                webcamPreview.onloadedmetadata = () => {
                    hiddenCanvas.width = webcamPreview.videoWidth;
                    hiddenCanvas.height = webcamPreview.videoHeight;
                };
            } catch (err) {
                console.error("Error accessing webcam:", err);
                alert("Tidak bisa mengakses kamera.");
            }
        }

        function applyFilter(filterValue) {
            webcamPreview.style.filter = filterValue;
        }

        async function startCaptureSequence() {
            startCaptureBtn.disabled = true;
            initialControls.classList.add('hidden'); // Sembunyikan kontrol awal
            previewPanel.classList.remove('hidden'); // Tampilkan panel preview
            previewThumbnails.innerHTML = ''; // Kosongkan preview sebelumnya
            
            const slots = parseInt(document.getElementById('capture_slots_count').value);
            const countdownTime = parseInt(document.getElementById('countdown_time').value);
            capturedFrames = [];

            for (let i = 0; i < slots; i++) {
                statusText.innerText = `Bersiap untuk foto ${i + 1}...`;
                countdownOverlay.classList.remove('hidden');
                for (let j = countdownTime; j > 0; j--) {
                    countdownText.innerText = j;
                    await delay(1000);
                }
                countdownText.innerText = 'SMILE!';
                await delay(500);
                
                ctx.filter = webcamPreview.style.filter;
                ctx.drawImage(webcamPreview, 0, 0, hiddenCanvas.width, hiddenCanvas.height);
                const imageDataUrl = hiddenCanvas.toDataURL('image/jpeg');
                capturedFrames.push(imageDataUrl);

                // TAMBAHKAN THUMBNAIL KE PANEL PREVIEW (DENGAN PEMBUNGKUS)
                const thumbWrapper = document.createElement('div');
                thumbWrapper.className = 'aspect-square bg-gray-100 rounded-lg overflow-hidden shadow-md';

                const img = document.createElement('img');
                img.src = imageDataUrl;
                img.className = 'w-full h-full object-cover';

                thumbWrapper.appendChild(img);
                previewThumbnails.appendChild(thumbWrapper);
                
                countdownOverlay.classList.add('hidden');
                statusText.innerText = `Foto ${i + 1} dari ${slots} terambil...`;
                await delay(1000);
            }
            
            statusText.innerText = "Mengirim data ke server...";
            sendDataToServer();
        }
        
        async function sendDataToServer() {
            const templateId = document.getElementById('selected_template_id').value;

            try {
                const response = await fetch("{{ route('camture.capture') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        template_id: templateId,
                        frames: capturedFrames
                    })
                });

                if (!response.ok) throw new Error('Server error');
                const result = await response.json();
                
                // Mengarahkan langsung ke halaman hasil yang baru
                window.location.href = result.show_url;

            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses foto.');
                
                const slotsCount = document.getElementById('capture_slots_count').value;
                startCaptureBtn.disabled = false;
                startCaptureBtn.innerText = `Start Capture (${slotsCount} foto)`;
                // Kembalikan UI ke keadaan semula jika gagal
                previewPanel.classList.add('hidden');
                initialControls.classList.remove('hidden');
                statusText.innerText = "";
            }
        }
        
        startCaptureBtn.addEventListener('click', startCaptureSequence);
        
        filterOptions.addEventListener('click', (e) => {
            if (e.target.classList.contains('filter-btn')) {
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                e.target.classList.add('active');
                applyFilter(e.target.dataset.filter);
            }
        });
        
        startWebcam();
    });
    </script>
    @endpush
</x-app-layout>