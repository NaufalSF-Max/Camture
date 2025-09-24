<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Loop melalui peran yang diizinkan (misal: 'admin', 'manager')
        foreach ($roles as $role) {
            // Jika peran pengguna cocok dengan salah satu peran yang diizinkan
            if ($request->user()->role == $role) {
                // Lanjutkan permintaan ke tujuan
                return $next($request);
            }
        }

        // Jika tidak ada peran yang cocok, tolak akses
        // Opsional: Anda bisa logout pengguna atau hanya redirect
        // Auth::logout();
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}