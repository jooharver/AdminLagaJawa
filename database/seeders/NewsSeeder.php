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
            'tempat' => 'Malang',
            'tanggal' => '2025-05-14',
            'deskripsi' => 'Berawal dari membeli es teh, sekarang pria asal Sudimoro Malang mendapat golden tiket untuk ikut kejuaraan futsal internasional. Momen itu ia dapat karena terinspirasi dari penjual es teh yang punya orang dalam.'
        ]);
    }
}
