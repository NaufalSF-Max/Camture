<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Photo;
use App\Models\Template;

class PhotoboothController extends Controller
{
    public function show()
    {
        $templates = Template::where('is_active', true)->get();
        return view('camture', ['templates' => $templates]);
    }

    public function capture(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'template_id' => 'required|exists:templates,id',
            'frames' => 'required|array',
            'frames.*' => 'required|string',
        ]);

        $template = Template::find($validated['template_id']);
        $framesData = $validated['frames'];

        // Inisialisasi Intervention Image Manager
        $manager = new ImageManager(new Driver());

        // 2. Muat gambar template utama
        $templateImage = $manager->read(Storage::disk('public')->path($template->image_path));

        // Logika sederhana untuk menempatkan foto (perlu disesuaikan)
        // Misal: template 4 slot akan menempatkan foto di 4 kuadran
        $positions = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];

        // 3. Loop dan gabungkan setiap frame
        foreach ($framesData as $index => $frameData) {
            // Konversi Base64 ke gambar
            $base64_str = substr($frameData, strpos($frameData, ",") + 1);
            $frameImage = $manager->read(base64_decode($base64_str));

            // Sesuaikan ukuran frame agar pas (misal, setengah lebar/tinggi template)
            $frameImage->scale(width: $templateImage->width() / 2);

            // Tempelkan frame ke template
            if (isset($positions[$index])) {
                $templateImage->place($frameImage, $positions[$index]);
            }
        }

        // (Opsional) Tambahkan watermark
        // $watermark = $manager->read(public_path('images/watermark.png'));
        // $templateImage->place($watermark, 'bottom-right', 10, 10);

        // 4. Simpan gambar final
        $fileName = 'camture-'. uniqid() . '.jpg';
        $path = 'photos/' . $fileName;
        $templateImage->toJpeg()->save(Storage::disk('public')->path($path));

        // 5. Simpan record ke database
        $photo = Photo::create([
            'user_id' => Auth::id(),
            'template_id' => $template->id,
            'file_path' => $path,
            'delete_at' => now()->addDays(30), // Atur masa berlaku foto
        ]);

        // 6. Kirim respons JSON kembali ke frontend
        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dibuat!',
            'photo_url' => Storage::disk('public')->url($path)
        ]);
    }
}
