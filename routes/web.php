<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\PhotoboothController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// GRUP RUTE KHUSUS ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Rute untuk Dashboard Admin
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');

    // Rute untuk Manajemen Template (CRUD)
    Route::resource('templates', TemplateController::class);

});

// RUTE UNTUK FITUR PHOTOBOOTH UTAMA
Route::get('/camture', [PhotoboothController::class, 'show'])->name('camture.show');
Route::post('/camture/capture', [PhotoboothController::class, 'capture'])->name('camture.capture')->middleware('auth'); // Sementara kita proteksi

require __DIR__.'/auth.php';