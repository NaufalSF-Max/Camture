<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Buat halaman untuk menampilkan semua template
        $templates = Template::latest()->paginate(10);
        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input, termasuk data JSON dari alat visual
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capture_slots' => 'required|integer|min:1',
            'image' => 'required|image|mimes:png',
            'slot_positions' => 'required|json', // Memastikan data yang masuk adalah JSON valid
        ]);

        // 2. Simpan File Gambar Template
        $path = $request->file('image')->store('templates', 'public');

        // 3. Simpan Data ke Database, termasuk data JSON
        Template::create([
            'name' => $validated['name'],
            'image_path' => $path,
            'capture_slots' => $validated['capture_slots'],
            'slot_positions' => $validated['slot_positions'], // Menyimpan data koordinat
        ]);

        // 4. Redirect dengan Pesan Sukses
        // Untuk sekarang, kita arahkan ke dashboard admin
        return redirect()->route('admin.dashboard')->with('success', 'Template berhasil ditambahkan!');
    }

    /**
     * METHOD Untuk mengubah status aktif/nonaktif template.
     */
    public function toggleStatus(Template $template)
    {
        // Membalik nilai boolean: jika true menjadi false, jika false menjadi true
        $template->is_active = !$template->is_active;
        $template->save();

        // Tentukan pesan notifikasi berdasarkan status baru
        $status = $template->is_active ? 'diaktifkan' : 'dinonaktifkan';

        // Kembali ke halaman daftar template dengan pesan sukses
        return redirect()->route('admin.templates.index')->with('success', "Template '{$template->name}' berhasil {$status}.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        // Cek apakah template sudah digunakan oleh foto
        $isUsed = $template->photos()->exists();

        return view('admin.templates.edit', compact('template', 'isUsed'));
    }

    /**
     * Memperbarui template di database.
     */
    public function update(Request $request, Template $template)
    {
        // Cek apakah template sudah digunakan
        $isUsed = $template->photos()->exists();

        $request->validate([
            'name' => 'required|string|max:255',
            // Validasi lain hanya jika template belum digunakan
            'image' => $isUsed ? 'nullable' : 'sometimes|image|mimes:png',
            'slot_positions' => 'required|json',
            'capture_slots' => 'required|integer|min:0',
        ]);

        $template->name = $request->name;

        // Hanya perbarui gambar & slot jika template belum digunakan
        if (!$isUsed) {
            $template->slot_positions = $request->slot_positions;
            $template->capture_slots = $request->capture_slots;

            if ($request->hasFile('image')) {
                // Hapus gambar lama
                Storage::disk('public')->delete($template->image_path);
                // Simpan gambar baru
                $template->image_path = $request->file('image')->store('templates', 'public');
            }
        }

        $template->save();

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        // Keamanan tambahan: Pastikan template ini tidak memiliki foto terkait sebelum dihapus
        if ($template->photos()->count() > 0) {
            return redirect()->route('admin.templates.index')
                ->with('error', "Gagal! Template '{$template->name}' tidak dapat dihapus karena masih digunakan oleh foto-foto yang ada.");
        }

        // Langkah 1: Hapus file gambar dari folder storage
        Storage::disk('public')->delete($template->image_path);

        // Langkah 2: Hapus data template dari database
        $template->delete();

        // Langkah 3: Kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.templates.index')
            ->with('success', "Template '{$template->name}' berhasil dihapus secara permanen.");
    }
}