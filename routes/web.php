<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhotoboothController;
use App\Http\Controllers\Admin\TemplateController;

// 1. ROUTE PUBLIK
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// 2. GRUP ROUTE UNTUK PENGGUNA YANG SUDAH LOGIN
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard & Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alur Utama Photobooth
    Route::get('/select-template', [PhotoboothController::class, 'selectLayout'])->name('template.select'); // Dulu: layouts.select
    Route::get('/camture/{template}', [PhotoboothController::class, 'show'])->name('camture.show');
    Route::post('/camture/capture', [PhotoboothController::class, 'capture'])->name('camture.capture');
    
    // Halaman Hasil & Galeri Foto
    Route::get('/result/{photo}', [PhotoboothController::class, 'showResult'])->name('photo.result'); // Dulu: photo.show
    Route::patch('/result/{photo}/title', [PhotoboothController::class, 'updateTitle'])->name('photo.update_title');
    Route::get('/gallery', [PhotoboothController::class, 'myPhotos'])->name('photo.gallery'); // Dulu: photo.gallery
});

// 3. GRUP ROUTE KHUSUS ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function() { return view('admin.dashboard'); })->name('dashboard');
    Route::resource('templates', TemplateController::class);
});

// 4. ROUTE OTENTIKASI BAWAAN LARAVEL
require __DIR__.'/auth.php';