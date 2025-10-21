<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Form Request untuk validasi login
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login (autentikasi).
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Lakukan autentikasi menggunakan data dari LoginRequest
        $request->authenticate();

        // Regenerate session ID untuk keamanan
        $request->session()->regenerate();

        // --- LOGIKA REDIRECT KUSTOM SETELAH LOGIN ---
        $user = $request->user(); // Dapatkan user yang berhasil login

        // Jika role user adalah 'admin'
        if ($user->role === 'admin') {
            // Arahkan ke dashboard admin
            // intended() akan mengarahkan ke halaman yang sebelumnya ingin diakses user,
            // atau ke 'admin.dashboard' jika tidak ada intended URL.
            return redirect()->intended(route('admin.dashboard'));
        }

        // Jika bukan admin (role = 'user')
        // Arahkan ke halaman welcome (halaman utama)
        return redirect()->intended(route('welcome'));
    }

    /**
     * Menangani proses logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout user dari guard 'web'
        Auth::guard('web')->logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return redirect('/');
    }
}