<x-app-layout>
    @section('title', 'Hasil Foto')

    {{-- Style khusus untuk editor stiker --}}
    <style>
        .sticker-panel { display: none; }
        .editor-active .sticker-panel { display: block; }
        .editor-active .static-image { display: none; }
        .canvas-container { margin: 0 auto; } /* Agar canvas di tengah */
        .sticker-thumb { cursor: pointer; transition: transform 0.2s; }
        .sticker-thumb:hover { transform: scale(1.1); }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id="editor-wrapper" class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        {{-- Kolom Kiri: Kanvas Editor --}}
                        <div class="md:col-span-2">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Hasil Karyamu!</h2>
                            
                            {{-- Tampilan Awal (Gambar Statis) --}}
                            <div class="static-image">
                                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Hasil Foto" class="rounded-lg shadow-md w-full">
                            </div>

                            {{-- Kanvas untuk Editor (Tersembunyi Awalnya) --}}
                            <div class="canvas-wrapper relative hidden">
                                <canvas id="editor-canvas"></canvas>
                                <button id="delete-sticker-btn" class="hidden absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg">&times;</button>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Aksi & Pilihan Stiker --}}
                        <div class="md:col-span-1">
                            {{-- Form Ganti Judul --}}
                            <form action="{{ route('photo.update_title', $photo) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <x-input-label for="title" value="Judul Foto" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="$photo->title" />
                                <x-primary-button class="mt-2">Simpan Judul</x-primary-button>
                            </form>
                            @if(session('title_success'))
                                <p class="text-sm text-green-600 mt-2">{{ session('title_success') }}</p>
                            @endif

                            <hr class="my-6">

                            {{-- Tombol Aksi --}}
                            <div class="space-y-3">
                                <a href="{{ asset('storage/' . $photo->file_path) }}" download="{{ $photo->title ?? 'camture-photo' }}.jpg" class="w-full text-center block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold">Download Foto</a>
                                <button id="edit-mode-btn" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 font-semibold">Hias dengan Stiker</button>
                            </div>

                            {{-- Panel Stiker & Tombol Simpan (tersembunyi awalnya) --}}
                            <div class="sticker-panel mt-6">
                                <h3 class="font-bold text-lg text-gray-700">Pilih Stiker</h3>
                                <div id="sticker-list" class="grid grid-cols-4 gap-4 mt-2 bg-gray-100 p-4 rounded-lg">
                                    {{-- Stiker akan dimuat di sini oleh JS --}}
                                </div>
                                <form id="save-sticker-form" action="{{ route('photo.applyStickers', $photo) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="imageData" id="imageData">
                                    <button type="submit" class="w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold">Simpan Perubahan</button>
                                </form>
                                <button id="cancel-edit-btn" class="w-full mt-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const editModeBtn = document.getElementById('edit-mode-btn');
        const cancelEditBtn = document.getElementById('cancel-edit-btn');
        const editorWrapper = document.getElementById('editor-wrapper');
        const canvasWrapper = document.querySelector('.canvas-wrapper');
        
        let canvas;

        const stickerSources = [
            '{{ asset('stickers/1.png') }}',
            '{{ asset('stickers/2.png') }}',
            '{{ asset('stickers/3.png') }}',
            '{{ asset('stickers/4.png') }}',
            '{{ asset('stickers/5.png') }}',
            '{{ asset('stickers/6.png') }}',
            '{{ asset('stickers/7.png') }}',
            '{{ asset('stickers/8.png') }}',
            '{{ asset('stickers/9.png') }}',
            '{{ asset('stickers/10.png') }}',
            '{{ asset('stickers/11.png') }}',
            '{{ asset('stickers/12.png') }}',
            '{{ asset('stickers/april.png') }}',
        ];

        // Muat thumbnail stiker
        const stickerList = document.getElementById('sticker-list');
        stickerSources.forEach(src => {
            if (!src.endsWith('/')) { // Cek jika path valid
                const img = document.createElement('img');
                img.src = src;
                img.className = 'sticker-thumb p-1 bg-white rounded shadow';
                img.onclick = () => addSticker(src);
                stickerList.appendChild(img);
            }
        });

        // Masuk ke mode edit
        editModeBtn.addEventListener('click', () => {
            editorWrapper.classList.add('editor-active');
            canvasWrapper.style.display = 'block';
            initCanvas();
        });

        // Keluar dari mode edit
        cancelEditBtn.addEventListener('click', () => {
            editorWrapper.classList.remove('editor-active');
            canvasWrapper.style.display = 'none';
            document.querySelector('.static-image').style.display = 'block';
            if (canvas) {
                canvas.dispose();
                canvas = null;
            }
        });

        function initCanvas() {
            canvas = new fabric.Canvas('editor-canvas');
            const photoUrl = '{{ asset('storage/' . $photo->file_path) }}' + '?t=' + new Date().getTime(); // Cache busting

            fabric.Image.fromURL(photoUrl, (img) => {
                canvas.setWidth(img.width);
                canvas.setHeight(img.height);
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    originX: 'left',
                    originY: 'top',
                });
            }, { crossOrigin: 'anonymous' });

            // Kontrol hapus stiker
            const deleteBtn = document.getElementById('delete-sticker-btn');
            canvas.on('selection:created', (e) => {
                deleteBtn.style.display = 'block';
            });
            canvas.on('selection:cleared', (e) => {
                deleteBtn.style.display = 'none';
            });
            deleteBtn.onclick = () => {
                const activeObject = canvas.getActiveObject();
                if (activeObject) {
                    canvas.remove(activeObject);
                    canvas.discardActiveObject().renderAll();
                }
            };
        }

        function addSticker(src) {
            if (!canvas) return;
            fabric.Image.fromURL(src, (img) => {
                img.scaleToWidth(150); // Ukuran awal stiker
                img.set({
                    top: canvas.height / 2,
                    left: canvas.width / 2,
                    originX: 'center',
                    originY: 'center',
                    cornerColor: 'red',
                    cornerSize: 10,
                    transparentCorners: false,
                });
                canvas.add(img);
                canvas.setActiveObject(img);
                canvas.renderAll();
            }, { crossOrigin: 'anonymous' });
        }

        // Simpan hasil
        document.getElementById('save-sticker-form').addEventListener('submit', (e) => {
            e.preventDefault();
            // Deselect object agar tidak ada kontrol yang ikut tersimpan
            canvas.discardActiveObject().renderAll(); 
            const imageData = canvas.toDataURL({ format: 'jpeg', quality: 0.9 });
            document.getElementById('imageData').value = imageData;
            e.target.submit();
        });
    });
    </script>
    @endpush
</x-app-layout>