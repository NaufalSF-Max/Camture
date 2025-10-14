<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Template Baru
        </h2>
    </x-slot>

    <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-8">

            {{-- Bagian Bawah: Detail Template --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Detail Template</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Template')" class="font-bold" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Contoh: Polaroid Klasik" />
                        </div>
                        
                        <div>
                            <x-input-label for="capture_slots" :value="__('Jumlah Slot Foto (Otomatis)')" class="font-bold" />
                            <x-text-input id="capture_slots" class="block mt-1 w-full bg-gray-100 cursor-not-allowed" type="number" name="capture_slots" value="0" required readonly />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="image" :value="__('Gambar Template (Wajib PNG Transparan)')" class="font-bold" />
                            <input id="image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="image" required />
                        </div>
                        
                        <div class="md:col-span-2">
                            <x-input-label for="slot_positions" :value="__('Posisi Slot (JSON - Dihasilkan Otomatis)')" class="font-bold" />
                            <textarea id="slot_positions" name="slot_positions" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" rows="5" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Bagian Atas: Visual Editor dan Petunjuk --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Visual Editor</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        
                        <div class="md:col-span-2">
                            <div id="visual-editor-container" class="relative bg-gray-200 border-2 border-dashed rounded-lg w-full max-w-xl mx-auto aspect-[3/4]">
                                <img id="template-preview" src="" class="absolute inset-0 w-full h-full object-contain z-0">
                                {{-- Slot akan ditambahkan di sini oleh JavaScript --}}
                            </div>
                        </div>

                        <div class="md:col-span-1 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-bold text-blue-800">Petunjuk:</h4>
                            <ul class="list-disc list-inside text-sm text-blue-700 mt-2 space-y-1">
                                <li>Klik <strong>Tambah Slot</strong> untuk membuat area foto.</li>
                                <li><strong>Klik & geser</strong> kotak untuk memindahkannya.</li>
                                <li>Gunakan <strong>lingkaran merah</strong> untuk mengubah ukuran.</li>
                                <li>Klik <strong>Reset</strong> untuk menghapus semua slot.</li>
                            </ul>
                            <div class="mt-4 flex gap-4">
                                <button type="button" id="add-slot-btn" class="w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold">Tambah Slot</button>
                                <button type="button" id="reset-slots-btn" class="w-full text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-semibold">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi Form --}}
            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('admin.templates.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                <x-primary-button>
                    {{ __('Simpan Template') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    @push('scripts')
    {{-- Script JavaScript tetap sama dan tidak perlu diubah --}}
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