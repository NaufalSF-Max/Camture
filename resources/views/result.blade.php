{{-- Menggunakan layout utama aplikasi --}}
<x-app-layout>
    @section('title', 'Hasil Foto Anda')

    {{-- Style khusus untuk editor stiker --}}
    <style>
        /* Panel stiker disembunyikan secara default */
        .sticker-panel {
            display: none;
        }

        /* Tampilkan panel stiker jika editor aktif */
        .editor-active .sticker-panel {
            display: block;
        }

        /* Sembunyikan gambar statis jika editor aktif */
        .editor-active .static-image-container {
            display: none;
        }

        /* Styling thumbnail stiker */
        .sticker-thumb {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .sticker-thumb:hover {
            transform: scale(1.1);
        }

        /* Wrapper canvas disembunyikan secara default */
        .canvas-wrapper {
            display: none;
        }

        /* Tampilkan wrapper canvas jika editor aktif */
        .editor-active .canvas-wrapper {
            display: block;
        }

        /* Membuat container canvas bisa di-scroll horizontal */
        .scrollable-canvas-container {
            width: 100%;
            overflow-x: auto;
            /* Aktifkan scroll horizontal */
            border: 2px dashed #E27396;
            /* Border penanda area canvas */
            border-radius: 0.75rem;
            -webkit-overflow-scrolling: touch;
            /* Scrolling halus di iOS */
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses (jika ada dari session) --}}
            @if (session('success') || session('title_success'))
                <div class="bg-camture-peach border-l-4 border-camture-rose text-camture-green-dark p-4 mb-6 rounded-md shadow-sm"
                    role="alert">
                    <p class="font-semibold text-camture-rose">Sukses!</p>
                    <p>{{ session('success') ?: session('title_success') }}</p> {{-- Tampilkan pesan 'success' atau
                    'title_success' --}}
                </div>
            @endif

            {{-- Card utama halaman hasil --}}
            <div class="bg-camture-pink-bg overflow-hidden shadow-xl sm:rounded-2xl p-6 md:p-10">
                {{-- Wrapper untuk toggle mode editor --}}
                <div id="editor-wrapper">
                    {{-- Grid layout (foto/canvas di kiri, kontrol di kanan) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12 items-start">

                        {{-- Kolom Kiri: Tampilan Foto / Kanvas Editor --}}
                        <div class="lg:col-span-2">
                            <h2 class="text-3xl font-extrabold text-camture-rose mb-6 text-center lg:text-left">Hias
                                Fotomu!</h2>

                            {{-- Container gambar/canvas --}}
                            <div class="bg-white p-3 rounded-xl shadow-lg border-2 border-camture-peach">
                                {{-- Tampilan Awal (Gambar Statis) --}}
                                <div class="static-image-container">
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Hasil Foto"
                                        class="rounded-lg w-full">
                                </div>

                                {{-- Kanvas untuk Editor (Tersembunyi Awalnya) --}}
                                <div class="canvas-wrapper relative">
                                    {{-- Container scrollable untuk canvas --}}
                                    <div class="scrollable-canvas-container">
                                        {{-- Elemen canvas target Fabric.js --}}
                                        <canvas id="editor-canvas"></canvas>
                                    </div>
                                    {{-- Tombol hapus stiker (muncul saat stiker dipilih) --}}
                                    <button id="delete-sticker-btn"
                                        class="hidden absolute top-0 -right-2 transform translate-x-full mt-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg hover:bg-red-600 transition">&times;</button>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Pengaturan & Aksi --}}
                        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-lg border-2 border-camture-peach">
                            <h3 class="text-2xl font-bold text-camture-green-dark mb-5 text-center">Pengaturan</h3>

                            {{-- Form Edit Judul Foto --}}
                            <form action="{{ route('photo.update_title', $photo) }}" method="POST"
                                class="mb-6 pb-6 border-b border-gray-200">
                                @csrf
                                @method('PATCH') {{-- Method PATCH untuk update --}}
                                {{-- Komponen Input Label --}}
                                <x-input-label for="title" value="Judul Foto"
                                    class="text-camture-green-dark font-semibold mb-2" />
                                {{-- Komponen Text Input --}}
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                    :value="$photo->title" placeholder="Berikan nama karyamu..." />
                                {{-- Komponen Primary Button --}}
                                <x-primary-button class="mt-3 w-full justify-center">Simpan Judul</x-primary-button>
                            </form>

                            {{-- Tombol Aksi Utama --}}
                            <div class="space-y-3">
                                {{-- Tombol Download --}}
                                <a href="{{ asset('storage/' . $photo->file_path) }}"
                                    download="{{ $photo->title ?? 'camture-photo' }}.jpg"
                                    class="w-full text-center flex items-center justify-center px-4 py-3 bg-camture-rose text-white rounded-lg hover:bg-camture-rose-hover font-bold shadow-md transition transform hover:scale-105">Download
                                    Foto</a>
                                {{-- Tombol Bagikan (jika didukung browser) --}}
                                <button id="share-btn"
                                    class="w-full text-center flex items-center justify-center px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-bold shadow-md transition transform hover:scale-105">
                                    Bagikan
                                </button>
                                {{-- Tombol Masuk Mode Edit Stiker --}}
                                <button id="edit-mode-btn"
                                    class="w-full text-center flex items-center justify-center px-4 py-3 bg-camture-green-dark text-white rounded-lg hover:bg-opacity-90 font-bold shadow-md transition transform hover:scale-105">Hias
                                    dengan Stiker</button>
                                {{-- Tombol Hapus Foto --}}
                                <div class="pt-4 border-t border-gray-200">
                                    <form action="{{ route('photo.destroy', $photo) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini secara permanen? Tindakan ini tidak bisa dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full text-center flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold shadow-md transition transform hover:scale-105">Hapus
                                            Foto Ini</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Panel Stiker (muncul saat mode edit aktif) --}}
                            <div class="sticker-panel mt-6">
                                <hr class="my-6">
                                <h3 class="font-bold text-xl text-camture-green-dark mb-4 text-center">Pilih Stiker</h3>
                                {{-- Container daftar stiker --}}
                                <div id="sticker-list"
                                    class="grid grid-cols-4 gap-3 mt-2 bg-camture-peach p-4 rounded-lg border border-camture-rose shadow-inner max-h-60 overflow-y-auto">
                                    {{-- Stiker dimuat oleh JavaScript --}}
                                </div>
                                {{-- Form untuk menyimpan gambar + stiker --}}
                                <form id="save-sticker-form" action="{{ route('photo.applyStickers', $photo) }}"
                                    method="POST">
                                    @csrf
                                    {{-- Input hidden untuk menyimpan data base64 canvas --}}
                                    <input type="hidden" name="imageData" id="imageData">
                                    <button type="submit"
                                        class="w-full mt-4 px-4 py-3 bg-camture-rose text-white rounded-lg hover:bg-camture-rose-hover font-bold shadow-md transition transform hover:scale-105">Simpan
                                        Hiasan</button>
                                </form>
                                {{-- Tombol Batal Edit --}}
                                <button id="cancel-edit-btn"
                                    class="w-full mt-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition-colors">Batal</button>
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
                // === Referensi Elemen DOM ===
                const editModeBtn = document.getElementById('edit-mode-btn');
                const cancelEditBtn = document.getElementById('cancel-edit-btn');
                const editorWrapper = document.getElementById('editor-wrapper'); // Wrapper untuk toggle editor
                const canvasWrapper = document.querySelector('.canvas-wrapper'); // Wrapper canvas Fabric.js
                const shareBtn = document.getElementById('share-btn');
                const stickerList = document.getElementById('sticker-list'); // Container thumbnail stiker
                const deleteBtn = document.getElementById('delete-sticker-btn'); // Tombol hapus stiker
                const saveStickerForm = document.getElementById('save-sticker-form'); // Form simpan stiker
                const imageDataInput = document.getElementById('imageData'); // Input hidden base64

                // === Variabel State ===
                let canvas; // Instance Fabric.js Canvas
                const photoUrl = '{{ asset('storage/' . $photo->file_path) }}'; // URL gambar asli
                const photoTitle = '{{ $photo->title ?? "Foto dari Camture" }}'; // Judul foto
                const pageUrl = '{{ route('photo.result', $photo) }}'; // URL halaman ini

                // === Logika Tombol Bagikan (Web Share API) ===
                // Cek apakah browser mendukung Web Share API
                if (navigator.share) {
                    shareBtn.addEventListener('click', async () => {
                        try {
                            // 1. Ambil data gambar sebagai Blob
                            const response = await fetch(photoUrl);
                            const blob = await response.blob();
                            // 2. Buat objek File dari Blob
                            const file = new File([blob], `${photoTitle}.jpg`, { type: blob.type });

                            // 3. Panggil navigator.share
                            await navigator.share({
                                title: photoTitle,
                                text: 'Lihat fotoku yang dibuat di Camture!',
                                url: pageUrl, // URL fallback jika file tidak didukung
                                files: [file], // Kirim file gambar
                            });
                            console.log('Foto berhasil dibagikan');
                        } catch (error) {
                            // Tangani error jika pengguna membatalkan atau terjadi kesalahan
                            console.error('Gagal membagikan:', error);
                            // Bisa tambahkan notifikasi error untuk pengguna di sini
                        }
                    });
                } else {
                    // Jika tidak didukung, sembunyikan tombol bagikan
                    shareBtn.style.display = 'none';
                    console.log('Web Share API tidak didukung di browser ini.');
                }

                // === Daftar Sumber Stiker ===
                // (Array URL stiker dari PHP)
                const stickerSources = [
                    '{{ asset('stickers/birthday_pack/birthday.png') }}', '{{ asset('stickers/wedding_pack/wedding.png') }}', '{{ asset('stickers/love_pack/love.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday1.png') }}', '{{ asset('stickers/wedding_pack/wedding1.png') }}', '{{ asset('stickers/love_pack/love1.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday2.png') }}', '{{ asset('stickers/wedding_pack/wedding2.png') }}', '{{ asset('stickers/love_pack/love2.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday3.png') }}', '{{ asset('stickers/wedding_pack/wedding3.png') }}', '{{ asset('stickers/love_pack/love3.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday4.png') }}', '{{ asset('stickers/wedding_pack/wedding4.png') }}', '{{ asset('stickers/love_pack/love4.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday5.png') }}', '{{ asset('stickers/wedding_pack/wedding5.png') }}', '{{ asset('stickers/love_pack/love5.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday6.png') }}', '{{ asset('stickers/wedding_pack/wedding6.png') }}', '{{ asset('stickers/love_pack/love6.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday7.png') }}', '{{ asset('stickers/wedding_pack/wedding7.png') }}', '{{ asset('stickers/love_pack/love7.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday8.png') }}', '{{ asset('stickers/wedding_pack/wedding8.png') }}', '{{ asset('stickers/love_pack/love8.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday9.png') }}', '{{ asset('stickers/wedding_pack/wedding9.png') }}', '{{ asset('stickers/love_pack/love9.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday10.png') }}', '{{ asset('stickers/wedding_pack/wedding10.png') }}', '{{ asset('stickers/love_pack/love10.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday11.png') }}', '{{ asset('stickers/wedding_pack/wedding11.png') }}', '{{ asset('stickers/love_pack/love11.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday12.png') }}', '{{ asset('stickers/wedding_pack/wedding12.png') }}', '{{ asset('stickers/love_pack/love12.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday13.png') }}', '{{ asset('stickers/wedding_pack/wedding13.png') }}', '{{ asset('stickers/love_pack/love13.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday14.png') }}', '{{ asset('stickers/wedding_pack/wedding14.png') }}', '{{ asset('stickers/love_pack/love14.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday15.png') }}', '{{ asset('stickers/wedding_pack/wedding15.png') }}', '{{ asset('stickers/love_pack/love15.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday16.png') }}', '{{ asset('stickers/wedding_pack/wedding16.png') }}', '{{ asset('stickers/love_pack/love16.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday17.png') }}', '{{ asset('stickers/wedding_pack/wedding17.png') }}', '{{ asset('stickers/love_pack/love17.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday18.png') }}', '{{ asset('stickers/wedding_pack/wedding18.png') }}', '{{ asset('stickers/love_pack/love18.png') }}',
                    '{{ asset('stickers/birthday_pack/birthday19.png') }}', '{{ asset('stickers/wedding_pack/wedding19.png') }}', '{{ asset('stickers/love_pack/love19.png') }}'
                ];

                // === Muat Thumbnail Stiker ===
                stickerSources.forEach(src => {
                    // Pastikan URL valid
                    if (src && !src.endsWith('/')) {
                        const img = document.createElement('img');
                        img.src = src;
                        img.className = 'sticker-thumb p-1 bg-white rounded shadow'; // Styling
                        img.onclick = () => addSticker(src); // Tambah stiker saat diklik
                        stickerList.appendChild(img);
                    }
                });

                // === Tombol Mode Edit & Batal ===
                editModeBtn.addEventListener('click', () => {
                    editorWrapper.classList.add('editor-active'); // Tambah kelas untuk toggle tampilan
                    initCanvas(); // Inisialisasi Fabric.js Canvas
                });

                cancelEditBtn.addEventListener('click', () => {
                    editorWrapper.classList.remove('editor-active'); // Hapus kelas editor
                    // Hancurkan instance canvas jika ada untuk membebaskan memori
                    if (canvas) {
                        canvas.dispose();
                        canvas = null;
                    }
                });

                // === Inisialisasi Fabric.js Canvas ===
                function initCanvas() {
                    // Buat instance Fabric Canvas baru
                    canvas = new fabric.Canvas('editor-canvas');
                    // Tambahkan cache busting ke URL gambar untuk memastikan gambar terbaru dimuat
                    const imageUrl = photoUrl + '?t=' + new Date().getTime();

                    // Muat gambar utama sebagai background canvas
                    fabric.Image.fromURL(imageUrl, (img) => {
                        // Atur ukuran canvas SAMA PERSIS dengan ukuran gambar asli
                        // Scrolling akan ditangani oleh CSS di .scrollable-canvas-container
                        canvas.setWidth(img.width);
                        canvas.setHeight(img.height);

                        // Set gambar sebagai background
                        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                            originX: 'left',
                            originY: 'top',
                        });
                    }, { crossOrigin: 'anonymous' }); // Penting untuk menghindari isu CORS

                    // --- Event Listener untuk Tombol Hapus ---
                    // Tampilkan tombol hapus saat objek (stiker) dipilih
                    canvas.on('selection:created', (e) => {
                        deleteBtn.style.display = 'flex';
                    });
                    // Sembunyikan tombol hapus saat seleksi dibatalkan
                    canvas.on('selection:cleared', (e) => {
                        deleteBtn.style.display = 'none';
                    });
                    // Fungsi hapus saat tombol diklik
                    deleteBtn.onclick = () => {
                        const activeObject = canvas.getActiveObject(); // Dapatkan stiker yang aktif
                        if (activeObject) {
                            canvas.remove(activeObject); // Hapus dari canvas
                            canvas.discardActiveObject().renderAll(); // Batalkan seleksi & render ulang
                        }
                    };
                }

                // === Fungsi Menambahkan Stiker ke Canvas ===
                function addSticker(src) {
                    if (!canvas) return; // Jangan lakukan apa-apa jika canvas belum siap
                    // Muat gambar stiker
                    fabric.Image.fromURL(src, (img) => {
                        // Atur properti awal stiker
                        img.scaleToWidth(150); // Ukuran awal
                        img.set({
                            top: canvas.height / 2, // Posisi tengah canvas
                            left: canvas.width / 2,
                            originX: 'center', // Titik pivot di tengah
                            originY: 'center',
                            cornerColor: '#E27396', // Warna kontrol
                            cornerSize: 10,
                            transparentCorners: false, // Kontrol tidak transparan
                        });
                        canvas.add(img); // Tambahkan ke canvas
                        canvas.setActiveObject(img); // Jadikan aktif (munculkan kontrol)
                        canvas.renderAll(); // Render ulang canvas
                    }, { crossOrigin: 'anonymous' });
                }

                // === Fungsi Menyimpan Gambar + Stiker ===
                saveStickerForm.addEventListener('submit', (e) => {
                    e.preventDefault(); // Hentikan submit form standar
                    if (!canvas) return; // Pastikan canvas ada

                    canvas.discardActiveObject().renderAll(); // Hapus seleksi aktif (kontrol)

                    // Konversi canvas ke data URL (base64 JPEG, kualitas 90%)
                    const imageData = canvas.toDataURL({ format: 'jpeg', quality: 0.9 });
                    // Masukkan data base64 ke input hidden
                    imageDataInput.value = imageData;
                    // Submit form secara manual
                    e.target.submit();
                });
            });
        </script>
    @endpush
</x-app-layout>