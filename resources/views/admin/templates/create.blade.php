<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Tambah Template Baru

        </h2>

    </x-slot>



    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">

                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            <div>

                                <div>

                                    <x-input-label for="name" :value="__('Nama Template')" />

                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />

                                </div>

                                <div class="mt-4">

                                    <x-input-label for="capture_slots" :value="__('Jumlah Slot Foto (Otomatis Terisi)')" />

                                    <x-text-input id="capture_slots" class="block mt-1 w-full bg-gray-100" type="number" name="capture_slots" value="0" required readonly />

                                </div>

                                <div class="mt-4">

                                    <x-input-label for="image" :value="__('Gambar Template (Wajib PNG Transparan)')" />

                                    <input id="image" class="block mt-1 w-full" type="file" name="image" required />

                                </div>

                                <div class="mt-4">

                                    <x-input-label for="slot_positions" :value="__('Posisi Slot (JSON - Otomatis)')" />

                                    <textarea id="slot_positions" name="slot_positions" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-gray-100" rows="5" readonly></textarea>

                                </div>

                            </div>

                            <div>

                                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">

                                    <h4 class="font-bold text-blue-800">Petunjuk Penggunaan:</h4>

                                    <ul class="list-disc list-inside text-sm text-blue-700 mt-1">

                                        <li>Klik <strong>Tambah Slot</strong> untuk membuat kotak foto baru.</li>

                                        <li><strong>Klik & geser</strong> kotak untuk memindahkan posisinya.</li>

                                        <li><strong>Klik & geser lingkaran merah</strong> di pojok kanan bawah untuk mengubah ukuran.</li>

                                        <li>Klik <strong>Reset</strong> untuk menghapus semua slot.</li>

                                    </ul>

                                </div>



                                <h3 class="font-bold mb-2">Alat Penentu Posisi Slot</h3>

                                <div id="visual-editor-container" class="relative bg-gray-200 border-2 border-dashed rounded-lg max-w-xl mx-auto">

                                    <img id="template-preview" src="" class="w-full h-auto relative z-0">

                                </div>

                                <div class="mt-4 flex gap-4">

                                    <button type="button" id="add-slot-btn" class="px-4 py-2 bg-blue-200 text-black rounded hover:bg-blue-700 hover:text-white">Tambah Slot</button>

                                    <button type="button" id="reset-slots-btn" class="px-4 py-2 bg-red-200 text-black rounded hover:bg-red-700 hover:text-white">Reset</button>

                                </div>

                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8">

                            <x-primary-button>

                                {{ __('Simpan Template') }}

                            </x-primary-button>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- ====================================================================== --}}
    {{-- ### LOGIKA JAVASCRIPT YANG SUDAH DIPERBAIKI ### --}}
    {{-- ====================================================================== --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('image');
        const templatePreview = document.getElementById('template-preview');
        const editorContainer = document.getElementById('visual-editor-container');
        const addSlotBtn = document.getElementById('add-slot-btn');
        const resetSlotsBtn = document.getElementById('reset-slots-btn');
        const slotPositionsTextarea = document.getElementById('slot_positions');
        const captureSlotsInput = document.getElementById('capture_slots');
        let slotCounter = 0;

        imageInput.addEventListener('change', function(event) {
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    templatePreview.src = e.target.result;
                    // Beri sedikit waktu agar gambar dirender sebelum update JSON
                    setTimeout(updateJsonOutput, 100); 
                }
                reader.readAsDataURL(event.target.files[0]);
                resetSlotsBtn.click();
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
            resizer.className = 'absolute -bottom-2 -right-2 w-4 h-4 bg-red-500 cursor-se-resize rounded-full border-2 border-white z-20';
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

        // Fungsi ini tidak diubah, karena sudah benar
        function makeDraggableAndResizable(element) {
            let pos = { x: 0, y: 0, w: 0, h: 0 };
            let initial = { x: 0, y: 0 };
            const resizer = element.querySelector('.cursor-se-resize');

            element.onmousedown = function(e) {
                if (e.target === resizer) return;
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

        // ### INI BAGIAN PALING PENTING YANG DIPERBAIKI ###
        function updateJsonOutput() {
            const slots = [];
            // Menggunakan 'editorContainer' (DIV) sebagai referensi ukuran, bukan 'templatePreview' (IMG)
            const containerWidth = editorContainer.offsetWidth;
            const containerHeight = editorContainer.offsetHeight;
            const slotDivs = editorContainer.querySelectorAll('.slot-box');
            
            if (containerWidth === 0 || containerHeight === 0) {
                console.log("Menunggu container render...");
                return; // Jangan lakukan kalkulasi jika container belum terlihat
            }

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

        // Panggil updateJsonOutput saat ukuran window berubah untuk menjaga akurasi
        window.onresize = updateJsonOutput;
    });
    </script>
    @endpush
</x-admin-layout>