<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

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
        // TODO: Buat halaman edit
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        // TODO: Buat logika untuk update
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        // TODO: Buat logika untuk hapus
    }
}