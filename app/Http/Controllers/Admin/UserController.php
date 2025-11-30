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
    public function index(Request $request)
    {
        // Fitur Searching & Pagination
        $query = User::query();

        // Jika ada input search dari dashboard
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Ambil data user kecuali admin, urutkan terbaru, paginate 10
        $users = $query->where('role', '!=', 'admin')
            ->withCount('photos') // Menghitung jumlah foto user (Relasi harus ada)
            ->latest()
            ->paginate(10);

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

    // FITUR EXPORT CSV
    public function exportCsv()
    {
        $fileName = 'laporan_user_camture_' . date('Y-m-d_H-i') . '.csv';

        // Ambil data user + jumlah foto
        $users = User::where('role', '!=', 'admin')->withCount('photos')->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('ID', 'Nama User', 'Email', 'Tanggal Bergabung', 'Jumlah Foto Diambil');

        $callback = function () use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $row['ID'] = $user->id;
                $row['Nama User'] = $user->name;
                $row['Email'] = $user->email;
                $row['Tanggal Bergabung'] = $user->created_at->format('d M Y');
                $row['Jumlah Foto Diambil'] = $user->photos_count; // Menggunakan accessor withCount

                fputcsv($file, array($row['ID'], $row['Nama User'], $row['Email'], $row['Tanggal Bergabung'], $row['Jumlah Foto Diambil']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}