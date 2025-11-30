<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;      // Untuk logging error
use Illuminate\Support\Facades\Storage; // Untuk interaksi dengan filesystem (menyimpan foto)
use Intervention\Image\Drivers\Gd\Driver; // Driver GD untuk Intervention Image
use Intervention\Image\ImageManager;      // Library manipulasi gambar

class PhotoboothController extends Controller
{
    /**
     * Menampilkan halaman pemilihan layout/template.
     * Mengambil semua template yang aktif.
     */
    public function selectLayout()
    {
        // Ambil template yang aktif saja, urutkan berdasarkan nama
        $templates = Template::where('is_active', true)->orderBy('name')->get();
        return view('select-layout', ['templates' => $templates]);
    }

    /**
     * Menampilkan halaman photobooth utama (dengan kamera).
     * Menerima instance Template yang dipilih.
     */
    public function show(Template $template)
    {
        return view('camture', ['template' => $template]);
    }

    /**
     * Memperbarui judul foto.
     * Menerima request dan instance Photo.
     */
    public function updateTitle(Request $request, Photo $photo)
    {
        // Keamanan: Pastikan pengguna hanya bisa mengedit fotonya sendiri
        if (Auth::user()->role !== 'admin' && $photo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.'); // Tolak akses jika bukan pemilik/admin
        }

        // Validasi input
        $request->validate([
            'title' => 'nullable|string|max:255', // Judul boleh kosong, maks 255 karakter
        ]);

        // Update judul di database
        $photo->update([
            'title' => $request->title,
        ]);

        // Kembali ke halaman yang sama dengan pesan sukses
        return redirect()->back()->with('title_success', 'Judul berhasil disimpan!');
    }

    /**
     * Memproses gambar yang diambil dari webcam, menggabungkannya dengan template,
     * dan menyimpannya.
     */
    public function capture(Request $request)
    {
        try {
            // Validasi data yang dikirim dari frontend
            $validated = $request->validate([
                'template_id' => 'required|exists:templates,id', // Pastikan template_id ada di DB
                'images' => 'required|array', // Pastikan 'images' ada dan berupa array
            ]);

            $template = Template::findOrFail($validated['template_id']); // Cari template atau error 404
            $framesData = $validated['images']; // Data gambar (base64) dari frontend
            $slotPositionsPercent = json_decode($template->slot_positions, true); // Decode JSON posisi slot

            // Validasi kecocokan jumlah gambar dan slot
            if (!$slotPositionsPercent || count($slotPositionsPercent) !== count($framesData)) {
                throw new \Exception('Data posisi slot tidak valid atau tidak cocok dengan jumlah frame.');
            }

            // Inisialisasi ImageManager dengan driver GD
            $manager = new ImageManager(new Driver());

            // --- Logika Penggabungan Gambar ---
            // 1. Baca template untuk mendapatkan dimensinya
            $templateImage = $manager->read(Storage::disk('public')->path($template->image_path));
            $templateWidth = $templateImage->width();
            $templateHeight = $templateImage->height();

            // 2. Buat kanvas kosong seukuran template (lapisan untuk foto)
            $finalImage = $manager->create($templateWidth, $templateHeight);

            // 3. Loop setiap gambar (frame) yang dikirim
            foreach ($framesData as $index => $frameData) {
                $slotPercent = $slotPositionsPercent[$index]; // Ambil data posisi slot ke-index

                // Hitung dimensi dan posisi slot dalam piksel
                $slotWidth = ($slotPercent['width'] / 100) * $templateWidth;
                $slotHeight = ($slotPercent['height'] / 100) * $templateHeight;
                $slotX = ($slotPercent['x'] / 100) * $templateWidth;
                $slotY = ($slotPercent['y'] / 100) * $templateHeight;

                // Decode gambar base64 dari frontend
                $base64_str = substr($frameData, strpos($frameData, ",") + 1);
                $frameImage = $manager->read(base64_decode($base64_str));

                // Sesuaikan ukuran gambar dengan ukuran slot
                $frameImage->resize(round($slotWidth), round($slotHeight));

                // Tempatkan gambar ke kanvas kosong pada posisi slot yang sesuai
                $finalImage->place($frameImage, 'top-left', round($slotX), round($slotY));
            }

            // 4. Setelah semua foto ditempatkan, tumpuk gambar template di atasnya
            $finalImage->place($templateImage, 'top-left', 0, 0);
            // --- Akhir Logika Penggabungan ---

            // Tentukan path penyimpanan
            $photoDirectory = 'photos'; // Folder penyimpanan di storage/app/public/photos
            $filename = 'camture-' . uniqid() . '.jpg'; // Nama file unik
            $savePath = $photoDirectory . '/' . $filename;

            // Simpan gambar hasil gabungan sebagai JPG ke public storage
            $finalImage->toJpeg()->save(Storage::disk('public')->path($savePath));

            // Simpan informasi foto ke database
            $photo = Photo::create([
                'user_id' => Auth::id(),        // ID pengguna yang login
                'template_id' => $template->id, // ID template yang digunakan
                'file_path' => $savePath,       // Path relatif file gambar
                'delete_at' => now()->addDays(30), // Jadwal penghapusan otomatis (opsional)
                'title' => 'Foto Tanpa Judul - ' . now()->format('d M Y'), // Judul default
            ]);

            // Kirim response JSON ke frontend berisi URL halaman hasil
            return response()->json([
                'success' => true,
                'redirect_url' => route('photo.result', $photo) // URL halaman hasil foto
            ]);

        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Kesalahan saat memproses capture: ' . $e->getMessage());
            Log::error($e->getTraceAsString()); // Log stack trace untuk debug
            // Kirim response error ke frontend
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan di server.'], 500);
        }
    }

    /**
     * Menampilkan halaman detail hasil foto.
     */
    public function showResult(Photo $photo)
    {
        // Pastikan pengguna adalah pemilik foto atau admin
        if (Auth::user()->role !== 'admin' && $photo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('result', ['photo' => $photo]);
    }

    /**
     * Menampilkan galeri foto milik pengguna yang sedang login.
     */
    public function myPhotos(Request $request)
    {
        $query = auth()->user()->photos(); // Mulai query dari relasi user

        // 1. Searching (Berdasarkan Judul Foto / Caption jika ada)
        // Anggap kita cari berdasarkan tanggal atau ID dulu jika tidak ada caption
        if ($request->has('search')) {
            $search = $request->search;
            // Contoh: cari berdasarkan ID atau tanggal (karena foto jarang ada judul manual)
            $query->where('created_at', 'like', '%' . $search . '%');
        }

        // 2. Sorting & Pagination
        // Kita tampilkan 12 foto per halaman (Grid 3x4 atau 4x3)
        $photos = $query->latest()->paginate(12);

        return view('gallery', compact('photos'));
    }

    /**
     * Menerapkan stiker ke foto yang sudah ada.
     * Menerima data gambar (base64) yang sudah diberi stiker dari frontend.
     */
    public function applyStickers(Request $request, Photo $photo)
    {
        // Pastikan hanya pemilik foto yang bisa mengedit
        if ($photo->user_id !== Auth::id()) {
            abort(403); // Tolak akses jika bukan pemilik
        }

        // Validasi data gambar yang dikirim
        $request->validate([
            'imageData' => 'required|string', // Pastikan imageData ada dan berupa string (base64)
        ]);

        // Proses data gambar base64
        $imageData = $request->input('imageData');
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData); // Hapus header base64
        $imageData = base64_decode($imageData); // Decode base64 menjadi data biner

        // Timpa file foto lama dengan gambar yang sudah diberi stiker
        Storage::disk('public')->put($photo->file_path, $imageData);

        // Redirect kembali ke halaman hasil dengan pesan sukses
        return redirect()->route('photo.result', $photo)->with('success', 'Hiasan berhasil disimpan!');
    }

    /**
     * Menghapus foto dari storage dan database.
     */
    public function destroyPhoto(Photo $photo)
    {
        // Pastikan hanya pemilik atau admin yang bisa menghapus
        if (Auth::user()->role !== 'admin' && $photo->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // 1. Hapus file gambar dari storage
        Storage::disk('public')->delete($photo->file_path);

        // 2. Hapus record foto dari database
        $photo->delete();

        // 3. Redirect ke galeri dengan pesan sukses
        return redirect()->route('photo.gallery')->with('success', 'Foto berhasil dihapus secara permanen.');
    }
}