<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'judul' => 'Atlit LJ Futsal Go Internasional',
            'sub_judul' => 'Berawal dari minum Es Teh',
            'image' => 'news/atlit_ljfutsal.JPG',
            'tempat' => 'Malang',
            'tanggal' => '2025-05-14',
            'deskripsi' => 'Berawal dari membeli es teh, sekarang pria asal Sudimoro Malang mendapat golden tiket untuk ikut kejuaraan futsal internasional. Momen itu ia dapat karena terinspirasi dari penjual es teh yang punya orang dalam.'
        ]);

        News::create([
            'judul' => 'Kerasnya Kehidupan Seorang Atlit Futsal',
            'sub_judul' => 'Atlit asal Gresik memutuskan untuk pensiun',
            'image' => 'news/atlit_kasihan.JPG',
            'tempat' => 'Malang',
            'tanggal' => '2025-05-26',
            'deskripsi' => '"Menjadi Atlit futsal adalah impian saya sedari kecil". Itulah yang diucapkan oleh atlit asal gresik saat konferensi pers di LJ Futsal pada tanggal 26 Mei 2025. Atlit tersebut memutuskan untuk pensiun lantaran tidak ada dukungan dari pemerintah setempat. sehingga ia harus hidup dengan memakan pungutan dari tong sampah. kasihan sekali'
        ]);
    }
}
