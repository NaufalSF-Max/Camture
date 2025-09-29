<?php

namespace App\Console\Commands;

use App\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PruneOldPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus foto yang sudah kedaluwarsa berdasarkan timestamp delete_at';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses penghapusan foto lama...');

        // Cari semua foto yang waktu delete_at-nya sudah terlewati
        $expiredPhotos = Photo::where('delete_at', '<=', now())->get();

        if ($expiredPhotos->isEmpty()) {
            $this->info('Tidak ada foto kedaluwarsa yang ditemukan.');
            return;
        }

        $count = 0;
        foreach ($expiredPhotos as $photo) {
            // Hapus file fisik dari storage
            if (Storage::disk('public')->exists($photo->file_path)) {
                Storage::disk('public')->delete($photo->file_path);
                $this->line('File terhapus: ' . $photo->file_path);
            }

            // Hapus record dari database
            $photo->delete();
            $count++;
        }

        $this->info($count . ' foto kedaluwarsa berhasil dihapus.');
        Log::info($count . ' foto kedaluwarsa berhasil dihapus oleh Task Scheduling.');
    }
}