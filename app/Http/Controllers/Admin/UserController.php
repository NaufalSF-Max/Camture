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
        $query = User::query();

        // 1. Searching
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Sorting (Dinamis)
        // Default: sort by created_at, direction desc
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validasi kolom agar tidak error jika user iseng ubah URL
        $validColumns = ['name', 'email', 'created_at', 'photos_count'];

        if (in_array($sortColumn, $validColumns)) {
            // Khusus photos_count perlu logic lain, tapi untuk basic kolom tabel:
            if ($sortColumn === 'photos_count') {
                $query->withCount('photos')->orderBy('photos_count', $sortDirection);
            } else {
                $query->withCount('photos')->orderBy($sortColumn, $sortDirection);
            }
        } else {
            $query->withCount('photos')->latest(); // Fallback
        }

        $users = $query->where('role', '!=', 'admin')
            ->paginate(5); // Pagination 5 per halaman

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

    // FITUR EXPORT Excel
    public function exportExcel()
    {
        $date = date('d-m-Y H:i');
        $fileName = 'Laporan_User_Camture_' . $date . '.xls'; // Ekstensi .xls agar dibaca Excel

        // Ambil data user + jumlah foto
        $users = User::where('role', '!=', 'admin')
            ->withCount('photos')
            ->latest()
            ->get();

        // Data Ringkasan untuk Header Laporan
        $summary = [
            'total_users' => $users->count(),
            'total_photos' => $users->sum('photos_count'), // Total semua foto yang ada
            'generated_at' => now()->format('d F Y, H:i WIB'),
            'generated_by' => auth()->user()->name
        ];

        // Kita return View, tapi dengan Header Excel
        return response()->view('admin.users.export', [
            'users' => $users,
            'summary' => $summary
        ])->withHeaders([
                    'Content-Type' => 'application/vnd.ms-excel',
                    'Content-Disposition' => "attachment; filename=\"$fileName\"",
                    'Pragma' => 'no-cache',
                    'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                    'Expires' => '0',
                ]);
    }
}