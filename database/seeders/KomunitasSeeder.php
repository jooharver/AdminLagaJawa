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
            'title' => 'Laga Jawa Tim A',
            'deskripsi' => 'Komunitas ini adalah komunitas yang dibentuk untuk menampung para pemain yang ingin bermain di tim Jawa Tim A',
            'phone' => '081234567890',
            'tanggal' => '2025-05-20',
            'jadwal' => json_encode([
                '08:00',
                '09:00',
                '10:00',
            ]),
            'court' => 'Court 1',
        ]);

        Komunitas::create([
            'title' => 'Laga Jawa Tim B',
            'deskripsi' => 'Komunitas ini adalah komunitas yang dibentuk untuk menampung para pemain yang ingin bermain di tim Jawa Tim B',
            'phone' => '081234567890',
            'tanggal' => '2025-05-24',
            'jadwal' => json_encode([
                '15:00',
                '16:00',
            ]),
            'court' => 'Court 2',
        ]);
    }
}
