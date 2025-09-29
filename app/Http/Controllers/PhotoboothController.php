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

    public function capture(Request $request)
    {
        try {
            $validated = $request->validate([
                'template_id' => 'required|exists:templates,id',
                'frames' => 'required|array',
            ]);

            $template = Template::find($validated['template_id']);
            $framesData = $validated['frames'];
            $slotPositionsPercent = json_decode($template->slot_positions, true);

            if (!$slotPositionsPercent || count($slotPositionsPercent) !== count($framesData)) {
                throw new \Exception('Data posisi slot tidak valid atau tidak cocok dengan jumlah frame.');
            }

            $manager = new ImageManager(new Driver());

            // ======================================================================
            // ### PERBAIKAN LOGIKA UTAMA ADA DI SINI ###
            // ======================================================================

            // 1. Jadikan gambar template sebagai kanvas dasar (background).
            $finalImage = $manager->read(Storage::disk('public')->path($template->image_path));
            $templateWidth = $finalImage->width();
            $templateHeight = $finalImage->height();
            
            // 2. Tempelkan setiap foto jepretan DI ATAS template.
            foreach ($framesData as $index => $frameData) {
                $slotPercent = $slotPositionsPercent[$index];

                // Hitung ukuran & posisi piksel yang sebenarnya berdasarkan persentase
                $slotWidth = ($slotPercent['width'] / 100) * $templateWidth;
                $slotHeight = ($slotPercent['height'] / 100) * $templateHeight;
                $slotX = ($slotPercent['x'] / 100) * $templateWidth;
                $slotY = ($slotPercent['y'] / 100) * $templateHeight;

                $base64_str = substr($frameData, strpos($frameData, ",") + 1);
                $frameImage = $manager->read(base64_decode($base64_str));
                
                // Ubah ukuran foto jepretan agar pas dengan ukuran slot
                $frameImage->resize(round($slotWidth), round($slotHeight));
                
                // Tempatkan foto jepretan di posisi x dan y yang tepat DI ATAS template
                $finalImage->place($frameImage, 'top-left', round($slotX), round($slotY));
            }
            // ======================================================================

            $photoDirectory = 'photos';
            if (!Storage::disk('public')->exists($photoDirectory)) {
                Storage::disk('public')->makeDirectory($photoDirectory);
            }

            $fileName = 'camture-'. uniqid() . '.jpg';
            $savePath = Storage::disk('public')->path($photoDirectory . '/' . $fileName);
            
            $finalImage->toJpeg()->save($savePath);

            $photo = Photo::create([
                'user_id' => Auth::id(),
                'template_id' => $template->id,
                'file_path' => $photoDirectory . '/' . $fileName,
                'delete_at' => now()->addDays(30),
            ]);

            return response()->json([
                'success'   => true,
                'show_url'  => route('photo.show', $photo)
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