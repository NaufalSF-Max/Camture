<x-app-layout>
    @section('title', 'Hasil Foto Anda')

    {{-- Style khusus untuk editor stiker --}}
    <style>
        .sticker-panel { display: none; }
        .editor-active .sticker-panel { display: block; }
        .editor-active .static-image-container { display: none; }
        
        .sticker-thumb { cursor: pointer; transition: transform 0.2s; }
        .sticker-thumb:hover { transform: scale(1.1); }

        .canvas-wrapper { display: none; }
        .editor-active .canvas-wrapper { display: block; }

        /* PERBAIKAN UTAMA: Membuat container canvas bisa di-scroll */
        .scrollable-canvas-container {
            width: 100%;
            overflow-x: auto;
            border: 2px dashed #E27396; /* camture-rose */
            border-radius: 0.75rem;
            -webkit-overflow-scrolling: touch; /* Scrolling halus di iOS */
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses --}}
            @if (session('success') || session('title_success'))
                <div class="bg-camture-peach border-l-4 border-camture-rose text-camture-green-dark p-4 mb-6 rounded-md shadow-sm" role="alert">
                    <p class="font-semibold text-camture-rose">Sukses!</p>
                    <p>{{ session('success') ?: session('title_success') }}</p>
                </div>
            @endif

            <div class="bg-camture-pink-bg overflow-hidden shadow-xl sm:rounded-2xl p-6 md:p-10">
                <div id="editor-wrapper">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12 items-start">
                        
                        {{-- Kolom Kiri: Tampilan Foto / Kanvas Editor --}}
                        <div class="lg:col-span-2">
                            <h2 class="text-3xl font-extrabold text-camture-rose mb-6 text-center lg:text-left">Hias Fotomu!</h2>
                            
                            <div class="bg-white p-3 rounded-xl shadow-lg border-2 border-camture-peach">
                                {{-- Tampilan Awal (Gambar Statis) --}}
                                <div class="static-image-container">
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Hasil Foto" class="rounded-lg w-full">
                                </div>

                                {{-- Kanvas untuk Editor (Tersembunyi Awalnya) --}}
                                <div class="canvas-wrapper relative">
                                    {{-- Container baru yang bisa di-scroll --}}
                                    <div class="scrollable-canvas-container">
                                        <canvas id="editor-canvas"></canvas>
                                    </div>
                                    <button id="delete-sticker-btn" class="hidden absolute top-0 -right-2 transform translate-x-full mt-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg hover:bg-red-600 transition">&times;</button>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan (Tidak ada perubahan di sini) --}}
                        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-lg border-2 border-camture-peach">
                            <h3 class="text-2xl font-bold text-camture-green-dark mb-5 text-center">Pengaturan</h3>
                            
                            <form action="{{ route('photo.update_title', $photo) }}" method="POST" class="mb-6 pb-6 border-b border-gray-200">
                                @csrf
                                @method('PATCH')
                                <x-input-label for="title" value="Judul Foto" class="text-camture-green-dark font-semibold mb-2" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="$photo->title" placeholder="Berikan nama karyamu..."/>
                                <x-primary-button class="mt-3 w-full justify-center">Simpan Judul</x-primary-button>
                            </form>

                            <div class="space-y-3">
                                <a href="{{ asset('storage/' . $photo->file_path) }}" download="{{ $photo->title ?? 'camture-photo' }}.jpg" class="w-full text-center flex items-center justify-center px-4 py-3 bg-camture-rose text-white rounded-lg hover:bg-camture-rose-hover font-bold shadow-md transition transform hover:scale-105">Download Foto</a>
                                <button id="share-btn" class="w-full text-center flex items-center justify-center px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-bold shadow-md transition transform hover:scale-105">
                                    Bagikan
                                </button>
                                <button id="edit-mode-btn" class="w-full text-center flex items-center justify-center px-4 py-3 bg-camture-green-dark text-white rounded-lg hover:bg-opacity-90 font-bold shadow-md transition transform hover:scale-105">Hias dengan Stiker</button>
                                <div class="pt-4 border-t border-gray-200">
                                    <form action="{{ route('photo.destroy', $photo) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini secara permanen? Tindakan ini tidak bisa dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-center flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold shadow-md transition transform hover:scale-105">Hapus Foto Ini</button>
                                    </form>
                                </div>
                            </div>

                            <div class="sticker-panel mt-6">
                                <hr class="my-6">
                                <h3 class="font-bold text-xl text-camture-green-dark mb-4 text-center">Pilih Stiker</h3>
                                <div id="sticker-list" class="grid grid-cols-4 gap-3 mt-2 bg-camture-peach p-4 rounded-lg border border-camture-rose shadow-inner max-h-60 overflow-y-auto"></div>
                                <form id="save-sticker-form" action="{{ route('photo.applyStickers', $photo) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="imageData" id="imageData">
                                    <button type="submit" class="w-full mt-4 px-4 py-3 bg-camture-rose text-white rounded-lg hover:bg-camture-rose-hover font-bold shadow-md transition transform hover:scale-105">Simpan Hiasan</button>
                                </form>
                                <button id="cancel-edit-btn" class="w-full mt-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition-colors">Batal</button>
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
        const shareBtn = document.getElementById('share-btn');
        const photoUrl = '{{ asset('storage/' . $photo->file_path) }}';
        const photoTitle = '{{ $photo->title ?? "Foto dari Camture" }}';
        const pageUrl = '{{ route('photo.result', $photo) }}';
        
        // Cek apakah browser mendukung Web Share API
        if (navigator.share) {
            shareBtn.addEventListener('click', async () => {
                try {
                    // Ambil file gambar dari server
                    const response = await fetch(photoUrl);
                    const blob = await response.blob();
                    const file = new File([blob], `${photoTitle}.jpg`, { type: blob.type });

                    // Buka dialog sharing
                    await navigator.share({
                        title: photoTitle,
                        text: 'Lihat fotoku yang dibuat di Camture!',
                        url: pageUrl, // URL ini akan dibagikan jika aplikasi target mendukungnya
                        files: [file],
                    });
                    console.log('Foto berhasil dibagikan');
                } catch (error) {
                    console.error('Gagal membagikan:', error);
                }
            });
        } else {
            // Jika browser tidak mendukung, sembunyikan tombolnya agar tidak membingungkan
            shareBtn.style.display = 'none';
            console.log('Web Share API tidak didukung di browser ini.');
        }

        let canvas;

        const stickerSources = [
            '{{ asset('stickers/1.png') }}', '{{ asset('stickers/2.png') }}',
            '{{ asset('stickers/3.png') }}', '{{ asset('stickers/4.png') }}',
            '{{ asset('stickers/5.png') }}', '{{ asset('stickers/6.png') }}',
            '{{ asset('stickers/7.png') }}', '{{ asset('stickers/8.png') }}',
            '{{ asset('stickers/9.png') }}', '{{ asset('stickers/10.png') }}',
            '{{ asset('stickers/11.png') }}', '{{ asset('stickers/12.png') }}',
            '{{ asset('stickers/april.png') }}',
        ];

        const stickerList = document.getElementById('sticker-list');
        stickerSources.forEach(src => {
            if (src && !src.endsWith('/')) {
                const img = document.createElement('img');
                img.src = src;
                img.className = 'sticker-thumb p-1 bg-white rounded shadow';
                img.onclick = () => addSticker(src);
                stickerList.appendChild(img);
            }
        });

        editModeBtn.addEventListener('click', () => {
            editorWrapper.classList.add('editor-active');
            initCanvas();
        });

        cancelEditBtn.addEventListener('click', () => {
            editorWrapper.classList.remove('editor-active');
            if (canvas) {
                canvas.dispose();
                canvas = null;
            }
        });

        // KEMBALI MENGGUNAKAN LOGIKA INIT STABIL ANDA
        function initCanvas() {
            canvas = new fabric.Canvas('editor-canvas');
            const photoUrl = '{{ asset('storage/' . $photo->file_path) }}' + '?t=' + new Date().getTime(); // Cache busting

            fabric.Image.fromURL(photoUrl, (img) => {
                // Set canvas seukuran gambar asli, biarkan CSS yang menangani scrolling
                canvas.setWidth(img.width);
                canvas.setHeight(img.height);
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    originX: 'left',
                    originY: 'top',
                });
            }, { crossOrigin: 'anonymous' });

            const deleteBtn = document.getElementById('delete-sticker-btn');
            canvas.on('selection:created', (e) => {
                deleteBtn.style.display = 'flex';
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
                img.scaleToWidth(150);
                img.set({
                    top: canvas.height / 2,
                    left: canvas.width / 2,
                    originX: 'center',
                    originY: 'center',
                    cornerColor: '#E27396',
                    cornerSize: 10,
                    transparentCorners: false,
                });
                canvas.add(img);
                canvas.setActiveObject(img);
                canvas.renderAll();
            }, { crossOrigin: 'anonymous' });
        }

        document.getElementById('save-sticker-form').addEventListener('submit', (e) => {
            e.preventDefault();
            canvas.discardActiveObject().renderAll(); 
            const imageData = canvas.toDataURL({ format: 'jpeg', quality: 0.9 });
            document.getElementById('imageData').value = imageData;
            e.target.submit();
        });
    });
    </script>
    @endpush
</x-app-layout>