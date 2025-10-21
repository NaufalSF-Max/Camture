<?php

namespace App\Http\Controllers\Admin; // Namespace diubah

use App\Http\Controllers\Controller; // Gunakan base Controller
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller // Extend base Controller
{
    /**
     * Menampilkan daftar semua pengguna (kecuali admin saat ini).
     */
    public function index()
    {
        // Ambil semua user KECUALI user admin yang sedang login, paginasi 15 per halaman
        $users = User::where('id', '!=', Auth::id())->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Memperbarui peran (role) pengguna.
     */
    public function updateRole(Request $request, User $user)
    {
        // Validasi input role
        $request->validate([
            'role' => 'required|in:user,admin', // Role harus 'user' atau 'admin'
        ]);

        // Keamanan: Cegah admin mengubah rolenya sendiri menjadi user
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah peran Anda sendiri.');
        }

        // Update role pengguna
        $user->role = $request->role;
        $user->save();

        // Redirect kembali dengan pesan sukses
        return back()->with('success', "Peran untuk '{$user->name}' berhasil diperbarui menjadi {$user->role}.");
    }
}