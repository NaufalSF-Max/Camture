<x-admin-layout>
    @section('title', 'Edit Template')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Template: {{ $template->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Form untuk update, menggunakan method PATCH --}}
            <form method="POST" action="{{ route('admin.templates.update', $template) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="space-y-8">

                    {{-- Pesan Peringatan jika template sudah digunakan --}}
                    @if ($isUsed)
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-md shadow-sm" role="alert">
                            <p class="font-bold">Perhatian!</p>
                            <p>Template ini sudah digunakan oleh foto yang ada. Anda hanya dapat mengubah nama. Untuk mengubah gambar atau posisi slot, harap buat template baru.</p>
                        </div>
                    @endif

                    {{-- Card 1: Detail Template --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold mb-6 text-camture-green-dark">1. Detail Template</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="name" :value="__('Nama Template')" class="font-semibold" />
                                    {{-- Mengisi value dengan data template yang ada --}}
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $template->name)" required autofocus />
                                </div>
                                <div>
                                    <x-input-label for="capture_slots" :value="__('Jumlah Slot Foto (Otomatis)')" class="font-semibold" />
                                    <x-text-input id="capture_slots" class="block mt-1 w-full bg-gray-100 cursor-not-allowed" type="number" name="capture_slots" :value="old('capture_slots', $template->capture_slots)" required readonly />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="image" :value="__('Ganti Gambar Template (Opsional)')" class="font-semibold" />
                                    {{-- Menonaktifkan input file jika template sudah digunakan --}}
                                    <input id="image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none disabled:bg-gray-200 disabled:cursor-not-allowed" type="file" name="image" {{ $isUsed ? 'disabled' : '' }} />
                                    @if($isUsed) <p class="text-xs text-gray-500 mt-1">Mengganti gambar dinonaktifkan karena template sedang digunakan.</p> @endif
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="slot_positions" :value="__('Posisi Slot (JSON)')" class="font-semibold" />
                                    <textarea id="slot_positions" name="slot_positions" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-gray-100 font-mono text-xs" rows="5" readonly>{{ old('slot_positions', $template->slot_positions) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Visual Editor --}}
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl {{ $isUsed ? 'opacity-50' : '' }}">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold mb-6 text-camture-green-dark">2. Visual Editor</h3>
                            @if($isUsed)
                                <p class="text-sm text-center -mt-4 mb-4 text-red-600 font-semibold">Editor dinonaktifkan karena template sedang digunakan.</p>
                            @endif
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                                <div class="lg:col-span-2">
                                    <div id="visual-editor-container" class="relative bg-camture-pink-bg border-2 border-dashed border-camture-amaranth rounded-lg max-w-xl mx-auto">
                                        {{-- Menampilkan gambar template yang sudah ada --}}
                                        <img id="template-preview" src="{{ asset('storage/' . $template->image_path) }}" class="w-full h-auto relative z-0">
                                        {{-- Slot akan digambar di sini oleh JavaScript --}}
                                    </div>
                                </div>
                                <div class="lg:col-span-1 bg-camture-peach bg-opacity-30 border border-camture-rose rounded-lg p-4">
                                    <h4 class="font-bold text-camture-rose">Petunjuk Penggunaan:</h4>
                                    <ul class="list-disc list-inside text-sm text-camture-green-dark mt-2 space-y-1">
                                         <li>Klik <strong>Tambah Slot</strong> untuk membuat area foto baru.</li>
                                         <li><strong>Klik & geser</strong> kotak untuk memindahkannya.</li>
                                         <li>Gunakan <strong>lingkaran merah</strong> untuk mengubah ukuran.</li>
                                         <li>Klik <strong>Reset</strong> untuk menghapus semua slot.</li>
                                    </ul>
                                    <div class="mt-4 flex flex-col gap-3">
                                        {{-- Menonaktifkan tombol jika template sudah digunakan --}}
                                        <button type="button" id="add-slot-btn" class="w-full text-center px-4 py-2 bg-camture-rose text-white rounded-md hover:bg-camture-rose-hover font-semibold shadow disabled:opacity-50 disabled:cursor-not-allowed" {{ $isUsed ? 'disabled' : '' }}>Tambah Slot</button>
                                        <button type="button" id="reset-slots-btn" class="w-full text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-semibold disabled:opacity-50 disabled:cursor-not-allowed" {{ $isUsed ? 'disabled' : '' }}>Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi Form --}}
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.templates.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <x-primary-button>
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    {{-- LOGIKA JAVASCRIPT LENGKAP YANG DISESUAIKAN UNTUK EDIT --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const isUsed = {{ $isUsed ? 'true' : 'false' }};
        // Hentikan semua fungsi editor jika template sudah digunakan
        if (isUsed) return; 

        const imageInput = document.getElementById('image');
        const templatePreview = document.getElementById('template-preview');
        const editorContainer = document.getElementById('visual-editor-container');
        const addSlotBtn = document.getElementById('add-slot-btn');
        const resetSlotsBtn = document.getElementById('reset-slots-btn');
        const slotPositionsTextarea = document.getElementById('slot_positions');
        const captureSlotsInput = document.getElementById('capture_slots');
        let slotCounter = 0;

        // FUNGSI BARU: Untuk menggambar slot yang sudah ada dari JSON
        function renderSlotsFromJSON() {
            // Hapus slot lama jika ada, untuk menghindari duplikasi
            editorContainer.querySelectorAll('.slot-box').forEach(el => el.remove());
            slotCounter = 0;

            try {
                // Jangan lakukan apa-apa jika textarea kosong
                if (!slotPositionsTextarea.value.trim()) return;

                const slots = JSON.parse(slotPositionsTextarea.value);
                
                // Pastikan gambar sudah dimuat dan memiliki dimensi
                if (templatePreview.offsetWidth === 0) {
                    setTimeout(renderSlotsFromJSON, 100); // Coba lagi setelah 100ms
                    return;
                }

                slots.forEach(slotData => {
                    slotCounter++;
                    const slotDiv = document.createElement('div');
                    slotDiv.className = 'slot-box absolute border-2 border-dashed border-red-500 cursor-move hover:bg-red-500 hover:bg-opacity-20 z-10';
                    
                    // Konversi persentase dari JSON kembali ke piksel untuk ditampilkan di editor
                    slotDiv.style.left = `${(slotData.x / 100) * templatePreview.offsetWidth}px`;
                    slotDiv.style.top = `${(slotData.y / 100) * templatePreview.offsetHeight}px`;
                    slotDiv.style.width = `${(slotData.width / 100) * templatePreview.offsetWidth}px`;
                    slotDiv.style.height = `${(slotData.height / 100) * templatePreview.offsetHeight}px`;
                    slotDiv.dataset.id = slotCounter;
                    
                    const resizer = document.createElement('div');
                    resizer.className = 'absolute -bottom-3 -right-3 w-6 h-6 bg-red-500 cursor-se-resize rounded-full border-2 border-white z-20';
                    slotDiv.appendChild(resizer);
                    
                    editorContainer.appendChild(slotDiv);
                    makeDraggableAndResizable(slotDiv);
                });
                updateJsonOutput();
            } catch (e) {
                console.error("Invalid JSON for slot positions:", e);
            }
        }
        
        // Panggil fungsi render saat halaman dimuat
        renderSlotsFromJSON();
        // Panggil lagi jika ukuran window berubah untuk menjaga akurasi
        window.onresize = renderSlotsFromJSON;

        // SISA SCRIPT SAMA SEPERTI create.blade.php
        imageInput.addEventListener('change', function(event) {
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    templatePreview.src = e.target.result;
                    resetSlotsBtn.click();
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        });

        addSlotBtn.addEventListener('click', function() {
            slotCounter++;
            const slotDiv = document.createElement('div');
            slotDiv.className = 'slot-box absolute border-2 border-dashed border-red-500 cursor-move hover:bg-red-500 hover:bg-opacity-20 z-10';
            slotDiv.style.left = '10px';
            slotDiv.style.top = '10px';
            slotDiv.style.width = '100px';
            slotDiv.style.height = '150px';
            slotDiv.dataset.id = slotCounter;
            
            const resizer = document.createElement('div');
            resizer.className = 'absolute -bottom-3 -right-3 w-6 h-6 bg-red-500 cursor-se-resize rounded-full border-2 border-white z-20';
            slotDiv.appendChild(resizer);
            
            editorContainer.appendChild(slotDiv);
            makeDraggableAndResizable(slotDiv);
            updateJsonOutput();
        });
        
        resetSlotsBtn.addEventListener('click', function() {
            editorContainer.querySelectorAll('.slot-box').forEach(el => el.remove());
            slotCounter = 0;
            updateJsonOutput();
        });

        function makeDraggableAndResizable(element) {
            let pos = { x: 0, y: 0, w: 0, h: 0 };
            let initial = { x: 0, y: 0 };
            const resizer = element.querySelector('.cursor-se-resize');

            element.onmousedown = function(e) {
                if (e.target.classList.contains('cursor-se-resize')) return;
                e.preventDefault();
                initial.x = e.clientX;
                initial.y = e.clientY;
                document.onmousemove = dragElement;
                document.onmouseup = closeEvents;
            };

            resizer.onmousedown = function(e) {
                e.stopPropagation(); 
                e.preventDefault();
                initial.x = e.clientX;
                initial.y = e.clientY;
                document.onmousemove = resizeElement;
                document.onmouseup = closeEvents;
            };

            function dragElement(e) {
                e.preventDefault();
                pos.x = element.offsetLeft - (initial.x - e.clientX);
                pos.y = element.offsetTop - (initial.y - e.clientY);
                initial.x = e.clientX;
                initial.y = e.clientY;
                element.style.left = pos.x + 'px';
                element.style.top = pos.y + 'px';
                updateJsonOutput();
            }

            function resizeElement(e) {
                e.preventDefault();
                pos.w = element.offsetWidth + (e.clientX - initial.x);
                pos.h = element.offsetHeight + (e.clientY - initial.y);
                initial.x = e.clientX;
                initial.y = e.clientY;
                element.style.width = pos.w + 'px';
                element.style.height = pos.h + 'px';
                updateJsonOutput();
            }

            function closeEvents() {
                document.onmouseup = null;
                document.onmousemove = null;
            }
        }

        function updateJsonOutput() {
            const slots = [];
            const containerWidth = templatePreview.offsetWidth;
            const containerHeight = templatePreview.offsetHeight;
            const slotDivs = editorContainer.querySelectorAll('.slot-box');
            
            if (containerWidth === 0 || containerHeight === 0) return;

            slotDivs.forEach(div => {
                slots.push({
                    x: (div.offsetLeft / containerWidth) * 100,
                    y: (div.offsetTop / containerHeight) * 100,
                    width: (div.offsetWidth / containerWidth) * 100,
                    height: (div.offsetHeight / containerHeight) * 100
                });
            });

            slotPositionsTextarea.value = JSON.stringify(slots, null, 2);
            captureSlotsInput.value = slots.length;
        }
    });
    </script>
    @endpush
</x-admin-layout>