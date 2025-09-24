<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // 1. Validasi Input [cite: 421]
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capture_slots' => 'required|integer|min:1',
            'image' => 'required|image|mimes:png', // Wajib gambar, format PNG
        ]);

        // 2. Simpan File Gambar
        $path = $request->file('image')->store('templates', 'public');

        // 3. Simpan Data ke Database [cite: 440]
        Template::create([
            'name' => $validated['name'],
            'capture_slots' => $validated['capture_slots'],
            'image_path' => $path,
        ]);

        // 4. Redirect dengan Pesan Sukses
        return redirect()->route('admin.dashboard')->with('success', 'Template berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
