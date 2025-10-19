<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Ambil semua pengguna kecuali admin yang sedang login
        $users = User::where('id', '!=', Auth::id())->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Memperbarui peran pengguna.
     */
    public function updateRole(Request $request, User $user)
    {
        // Validasi
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        // Keamanan: Jangan biarkan admin terakhir mengubah perannya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah peran Anda sendiri.');
        }

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Role untuk '{$user->name}' berhasil diperbarui menjadi {$user->role}.");
    }
}