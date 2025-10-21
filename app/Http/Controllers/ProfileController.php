<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest; // Form Request untuk validasi update profil
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(), // Kirim data user ke view
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna (nama & email).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Isi model User dengan data yang sudah divalidasi oleh ProfileUpdateRequest
        $request->user()->fill($request->validated());

        // Jika email diubah, reset status verifikasi email
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Simpan perubahan ke database
        $request->user()->save();

        // Redirect kembali ke halaman edit profil dengan pesan status
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password saat ini untuk konfirmasi penghapusan
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Logout pengguna sebelum menghapus
        Auth::logout();

        // Hapus data pengguna dari database
        $user->delete();

        // Invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return Redirect::to('/');
    }
}