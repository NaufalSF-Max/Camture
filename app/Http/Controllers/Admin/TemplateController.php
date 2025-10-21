<?php

namespace App\Http\Controllers\Admin; // Namespace diubah

use App\Http\Controllers\Controller; // Gunakan base Controller
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller // Extend base Controller
{
    /**
     * Menampilkan daftar semua template (halaman manajemen template).
     */
    public function index()
    {
        // Ambil template terbaru, paginasi 10 per halaman
        $templates = Template::latest()->paginate(10);
        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Menampilkan form untuk membuat template baru.
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Menyimpan template baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',           // Nama wajib, maks 255 karakter
            'capture_slots' => 'required|integer|min:1',   // Jumlah slot wajib, minimal 1
            'image' => 'required|image|mimes:png',         // Gambar wajib, harus PNG
            'slot_positions' => 'required|json',           // Posisi slot wajib, harus JSON valid
        ]);

        // 2. Simpan file gambar template ke storage/app/public/templates
        $path = $request->file('image')->store('templates', 'public');

        // 3. Simpan data template ke database
        Template::create([
            'name' => $validated['name'],
            'image_path' => $path,                      // Path relatif gambar
            'capture_slots' => $validated['capture_slots'],
            'slot_positions' => $validated['slot_positions'], // Simpan JSON posisi slot
            // 'is_active' defaultnya true (sesuai migrasi)
        ]);

        // 4. Redirect ke halaman daftar template (index) dengan pesan sukses
        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil ditambahkan!');
    }

    /**
     * Mengubah status aktif/nonaktif template.
     */
    public function toggleStatus(Template $template)
    {
        // Balik nilai boolean is_active
        $template->is_active = !$template->is_active;
        $template->save();

        // Tentukan pesan berdasarkan status baru
        $status = $template->is_active ? 'diaktifkan' : 'dinonaktifkan';

        // Redirect kembali ke daftar template dengan pesan sukses
        return redirect()->route('admin.templates.index')->with('success', "Template '{$template->name}' berhasil {$status}.");
    }

    /**
     * Menampilkan form untuk mengedit template.
     */
    public function edit(Template $template)
    {
        // Cek apakah template ini sudah pernah digunakan oleh foto
        $isUsed = $template->photos()->exists();

        // Kirim data template dan status penggunaan ke view
        return view('admin.templates.edit', compact('template', 'isUsed'));
    }

    /**
     * Memperbarui data template di database.
     */
    public function update(Request $request, Template $template)
    {
        // Cek kembali apakah template sudah digunakan
        $isUsed = $template->photos()->exists();

        // Validasi input, sesuaikan aturan jika template sudah digunakan
        $request->validate([
            'name' => 'required|string|max:255',
            // Gambar hanya boleh diubah jika template BELUM digunakan
            'image' => $isUsed ? 'nullable' : 'sometimes|image|mimes:png',
            'slot_positions' => 'required|json',
            'capture_slots' => 'required|integer|min:0', // Min 0 karena mungkin ingin dinonaktifkan? (Perlu dikonfirmasi)
        ]);

        // Update nama template (selalu bisa diubah)
        $template->name = $request->name;

        // Hanya perbarui gambar & slot jika template BELUM digunakan
        if (!$isUsed) {
            $template->slot_positions = $request->slot_positions;
            $template->capture_slots = $request->capture_slots;

            // Jika ada file gambar baru yang diupload
            if ($request->hasFile('image')) {
                // Hapus gambar lama dari storage
                Storage::disk('public')->delete($template->image_path);
                // Simpan gambar baru dan update path
                $template->image_path = $request->file('image')->store('templates', 'public');
            }
        }

        // Simpan perubahan ke database
        $template->save();

        // Redirect ke daftar template dengan pesan sukses
        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Menghapus template dari database dan storage.
     */
    public function destroy(Template $template)
    {
        // Keamanan: Jangan hapus template jika masih digunakan oleh foto
        if ($template->photos()->count() > 0) {
            return redirect()->route('admin.templates.index')
                ->with('error', "Gagal! Template '{$template->name}' tidak dapat dihapus karena masih digunakan oleh foto.");
        }

        // 1. Hapus file gambar dari storage
        Storage::disk('public')->delete($template->image_path);

        // 2. Hapus data template dari database
        $template->delete();

        // 3. Redirect ke daftar template dengan pesan sukses
        return redirect()->route('admin.templates.index')
            ->with('success', "Template '{$template->name}' berhasil dihapus secara permanen.");
    }

     /**
     * Menampilkan detail template (jika diperlukan di masa depan).
     * Saat ini tidak digunakan.
     */
    public function show(Template $template)
    {
        // Logika untuk menampilkan detail satu template (jika perlu)
    }
}