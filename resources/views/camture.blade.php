<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Camture Photobooth</h1>

        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2 relative">
                <video id="webcam-preview" autoplay playsinline class="w-full rounded-lg shadow-lg"></video>
                <img id="template-overlay" src="" class="absolute top-0 left-0 w-full h-full pointer-events-none">
            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h3 class="font-bold mb-2">1. Pilih Template</h3>
                <div id="template-options" class="grid grid-cols-3 gap-2 mb-4">
                    @foreach ($templates as $template)
                        <img src="{{ asset('storage/' . $template->image_path) }}" 
                             class="cursor-pointer border-2 border-transparent hover:border-blue-500 rounded"
                             data-template-id="{{ $template->id }}"
                             data-slots="{{ $template->capture_slots }}"
                             data-path="{{ asset('storage/' . $template->image_path) }}">
                    @endforeach
                </div>

                <h3 class="font-bold mb-2">2. Ambil Foto</h3>
                <div id="capture-slots" class="grid grid-cols-4 gap-2 mb-4">
                    </div>

                <button id="capture-btn" class="w-full bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-700 disabled:bg-gray-400" disabled>
                    Ambil Foto
                </button>
                <button id="finish-btn" class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 mt-2 hidden">
                    Selesai & Proses
                </button>
            </div>
        </div>

        <canvas id="hidden-canvas" class="hidden"></canvas>
    </div>

    @push('scripts')
    <script>
        // === Inisialisasi Elemen & Variabel ===
        const webcamPreview = document.getElementById('webcam-preview');
        const templateOverlay = document.getElementById('template-overlay');
        const templateOptions = document.getElementById('template-options');
        const captureSlotsContainer = document.getElementById('capture-slots');
        const captureBtn = document.getElementById('capture-btn');
        const finishBtn = document.getElementById('finish-btn');
        const hiddenCanvas = document.getElementById('hidden-canvas');
        const ctx = hiddenCanvas.getContext('2d');

        let selectedTemplate = null;
        let capturedFrames = [];
        let activeSlot = 0;

        // === Fungsi Utama ===

        // 1. Memulai Webcam
        async function startWebcam() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                webcamPreview.srcObject = stream;
                webcamPreview.onloadedmetadata = () => {
                    hiddenCanvas.width = webcamPreview.videoWidth;
                    hiddenCanvas.height = webcamPreview.videoHeight;
                };
            } catch (err) {
                console.error("Error accessing webcam:", err);
                alert("Tidak bisa mengakses kamera. Mohon izinkan akses kamera di browser Anda.");
            }
        }

        // 2. Merender Slot Foto
        function renderSlots() {
            captureSlotsContainer.innerHTML = '';
            capturedFrames = new Array(selectedTemplate.slots).fill(null);
            for (let i = 0; i < selectedTemplate.slots; i++) {
                const slot = document.createElement('div');
                slot.className = 'w-16 h-16 bg-gray-200 border-2 border-dashed rounded flex items-center justify-center';
                slot.innerHTML = `<span>${i + 1}</span>`;
                slot.dataset.slotIndex = i;
                captureSlotsContainer.appendChild(slot);
            }
            updateActiveSlotUI();
        }

        // 3. Memperbarui UI Slot Aktif
        function updateActiveSlotUI() {
            document.querySelectorAll('#capture-slots > div').forEach((slot, index) => {
                slot.classList.remove('border-blue-500', 'border-4');
                if (index === activeSlot) {
                    slot.classList.add('border-blue-500', 'border-4');
                }
            });
        }

        // 4. Logika Mengambil Gambar
        function captureFrame() {
            if (!selectedTemplate) return;

            // Gambar frame video ke canvas
            ctx.drawImage(webcamPreview, 0, 0, hiddenCanvas.width, hiddenCanvas.height);
            const imageDataUrl = hiddenCanvas.toDataURL('image/jpeg');

            // Simpan data gambar
            capturedFrames[activeSlot] = imageDataUrl;

            // Tampilkan thumbnail di slot
            const slotUI = document.querySelector(`div[data-slot-index='${activeSlot}']`);
            slotUI.innerHTML = `<img src="${imageDataUrl}" class="w-full h-full object-cover rounded">`;

            // Pindah ke slot berikutnya atau selesai
            if (activeSlot < selectedTemplate.slots - 1) {
                activeSlot++;
                updateActiveSlotUI();
            } else {
                captureBtn.disabled = true;
                captureBtn.innerText = "Semua Slot Terisi";
                finishBtn.classList.remove('hidden');
            }
        }

        // 5. Mengirim Data ke Server
        async function finishAndProcess() {
            finishBtn.disabled = true;
            finishBtn.innerText = "Memproses...";

            try {
                const response = await fetch("{{ route('camture.capture') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        template_id: selectedTemplate.id,
                        frames: capturedFrames
                    })
                });

                if (!response.ok) throw new Error('Network response was not ok.');

                const result = await response.json();

                // Arahkan ke halaman hasil (akan kita buat di tahap berikutnya)
                alert("Foto berhasil dibuat! URL: " + result.photo_url);
                window.location.href = result.photo_url; // Redirect ke halaman view foto

            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses foto.');
                finishBtn.disabled = false;
                finishBtn.innerText = "Selesai & Proses";
            }
        }


        // === Event Listeners ===

        // Klik pada pilihan template
        templateOptions.addEventListener('click', (e) => {
            if (e.target.tagName === 'IMG') {
                selectedTemplate = {
                    id: e.target.dataset.templateId,
                    slots: parseInt(e.target.dataset.slots),
                    path: e.target.dataset.path
                };

                // Tampilkan overlay template di atas video
                templateOverlay.src = selectedTemplate.path;

                // Reset state
                activeSlot = 0;
                renderSlots();
                captureBtn.disabled = false;
                captureBtn.innerText = "Ambil Foto";
                finishBtn.classList.add('hidden');
            }
        });

        // Klik tombol capture
        captureBtn.addEventListener('click', captureFrame);

        // Klik tombol Selesai
        finishBtn.addEventListener('click', finishAndProcess);


        // === Inisialisasi Awal ===
        startWebcam();
    </script>
    @endpush
</x-app-layout>