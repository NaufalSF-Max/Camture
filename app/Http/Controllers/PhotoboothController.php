<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PhotoboothController extends Controller
{
    public function selectLayout()
    {
        $templates = Template::where('is_active', true)->orderBy('name')->get();
        return view('select-layout', ['templates' => $templates]);
    }

    public function show(Template $template)
    {
        return view('camture', ['template' => $template]);
    }

    /**
     * METHOD BARU: Update judul foto.
     */
    public function updateTitle(Request $request, Photo $photo)
    {
        // Keamanan: Pastikan pengguna hanya bisa mengedit fotonya sendiri
        if (Auth::user()->role !== 'admin' && $photo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input
        $request->validate([
            'title' => 'nullable|string|max:255',
        ]);

        // Update judul di database
        $photo->update([
            'title' => $request->title,
        ]);

        // Kembali ke halaman yang sama dengan pesan sukses
        return redirect()->back()->with('title_success', 'Judul berhasil disimpan!');
    }

    public function capture(Request $request)
    {
        try {
            // PERBAIKAN A: Mengubah 'frames' menjadi 'images' agar cocok dengan data dari browser
            $validated = $request->validate([
                'template_id' => 'required|exists:templates,id',
                'images' => 'required|array',
            ]);

            $template = Template::findOrFail($validated['template_id']);
            $framesData = $validated['images'];
            $slotPositionsPercent = json_decode($template->slot_positions, true);

            if (!$slotPositionsPercent || count($slotPositionsPercent) !== count($framesData)) {
                throw new \Exception('Data posisi slot tidak valid atau tidak cocok dengan jumlah frame.');
            }

            $manager = new ImageManager(new Driver());

            // ======================================================================
            // ### LOGIKA FINAL: FOTO DI BELAKANG TEMPLATE ###
            // ======================================================================

            // 1. Muat gambar template HANYA untuk mendapatkan ukurannya.
            $templateImage = $manager->read(Storage::disk('public')->path($template->image_path));
            $templateWidth = $templateImage->width();
            $templateHeight = $templateImage->height();

            // 2. Buat kanvas KOSONG seukuran template. Ini akan menjadi lapisan foto kita.
            $finalImage = $manager->create($templateWidth, $templateHeight);

            // 3. Loop dan "lukis" setiap foto jepretan ke kanvas kosong.
            //    Logika resize dan posisi di sini 100% sama dengan kode referensi Anda.
            foreach ($framesData as $index => $frameData) {
                $slotPercent = $slotPositionsPercent[$index];
                $slotWidth = ($slotPercent['width'] / 100) * $templateWidth;
                $slotHeight = ($slotPercent['height'] / 100) * $templateHeight;
                $slotX = ($slotPercent['x'] / 100) * $templateWidth;
                $slotY = ($slotPercent['y'] / 100) * $templateHeight;

                $base64_str = substr($frameData, strpos($frameData, ",") + 1);
                $frameImage = $manager->read(base64_decode($base64_str));

                $frameImage->resize(round($slotWidth), round($slotHeight));
                
                // Tempatkan foto ke kanvas KOSONG, bukan ke template.
                $finalImage->place($frameImage, 'top-left', round($slotX), round($slotY));
            }

            // 4. Setelah kanvas foto selesai, tumpuk gambar template di lapisan PALING ATAS.
            $finalImage->place($templateImage, 'top-left', 0, 0);
            
            // ======================================================================

            $photoDirectory = 'photos';
            $savePath = $photoDirectory . '/camture-' . uniqid() . '.jpg';
            
            $finalImage->toJpeg()->save(Storage::disk('public')->path($savePath));

            // PERBAIKAN B: Menambahkan 'title' untuk mengatasi error database
            $photo = Photo::create([
                'user_id' => Auth::id(),
                'template_id' => $template->id,
                'file_path' => $savePath,
                'delete_at' => now()->addDays(30),
                'title' => 'Untitled Photo',
            ]);
            
            // PERBAIKAN C: Menggunakan nama route yang benar ('photo.result')
            return response()->json([
                'success'       => true,
                'redirect_url'  => route('photo.result', $photo) 
            ]);

        } catch (\Exception $e) {
            Log::error('EXCEPTION saat capture: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan di server.'], 500);
        }
    }

    /**
     * METHOD Menampilkan halaman hasil foto.
     */
    public function showResult(Photo $photo)
    {
        // Keamanan: Pastikan pengguna hanya bisa melihat fotonya sendiri (kecuali admin)
        if (Auth::user()->role !== 'admin' && $photo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('result', ['photo' => $photo]);
    }

    /**
     * METHOD BARU: Menampilkan galeri foto milik pengguna.
     */
    public function myPhotos()
    {
        $photos = Photo::where('user_id', Auth::id())
                        ->latest() // Mengurutkan dari yang paling baru
                        ->paginate(12); // Menampilkan 12 foto per halaman

        return view('gallery', ['photos' => $photos]);
    }
}