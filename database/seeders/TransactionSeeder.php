<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::create([
            'user_id' => 7,
            'no_pemesanan' => 'NTR-00J98725E',
            'payment_method' => 'transfer',
            'total_amount' => 100000,
            'payment_status' => 'paid',
            'paid_at' => now(),
            'status' => 'confirmed',
        ]);
        Transaction::create([
            'user_id' => 1,
            'no_pemesanan' => 'NTR-110H87K09',
            'payment_method' => 'qris',
            'total_amount' => 200000,
            'payment_status' => 'waiting',
            'paid_at' => null,
            'status' => 'pending',
        ]);
        Transaction::create([
            'user_id' => 4,
            'no_pemesanan' => 'NTR-116RH0987',
            'payment_method' => 'cod',
            'total_amount' => 150000,
            'payment_status' => 'waiting',
            'paid_at' => null,
            'status' => 'pending',
        ]);
    }
}
