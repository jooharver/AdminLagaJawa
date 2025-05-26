<?php

namespace Database\Seeders;

use App\Models\Komunitas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KomunitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Komunitas::create([
            'title' => 'LJ Mawar',
            'user_id' => 8,
            'deskripsi' => 'Komunitas ini adalah komunitas yang dibentuk untuk menampung para pemain yang ingin bermain di tim Jawa Tim Mawar',
            'phone' => '081234567890',
            'image' => 'komunitas/image_1.png',
            'image_logo' => 'komunitas/logo_1.png',
            'image_banner' => 'komunitas/banner_1.png',

        ]);

        Komunitas::create([
            'title' => 'LJ Serigala',
            'user_id' => 9,
            'deskripsi' => 'Komunitas ini adalah komunitas yang dibentuk untuk menampung para pemain yang ingin bermain di tim Jawa Tim Serigala',
            'phone' => '081234567890',
            'image' => 'komunitas/image_2.png',
            'image_logo' => 'komunitas/logo_2.png',
            'image_banner' => 'komunitas/banner_2.png',
        ]);

        Komunitas::create([
            'title' => 'Sudimoro FC',
            'user_id' => 10,
            'deskripsi' => 'Komunitas ini adalah komunitas yang dibentuk untuk menampung para pemain yang ingin bermain di tim Jawa Tim Sudimoro',
            'phone' => '081234567890',
            'image' => 'komunitas/image_3.png',
            'image_logo' => 'komunitas/logo_3.png',
            'image_banner' => 'komunitas/banner_3.png',
        ]);
    }
}
