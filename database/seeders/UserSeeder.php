<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'phone' => '087870463683',
            'address' => 'Jl.Sudimoro 11',
            'name' => 'Eka Krisna',
            'email' => 'joo@email.com',
            'password' => Hash::make('joo'),
        ]);

        User::create([
            'phone' => '085670463683',
            'address' => 'Jl. Anomali Brainrot 01',
            'name' => 'Tung Tung Tung Sahur',
            'email' => 'tung@email.com',
            'password' => Hash::make('tung'),
        ]);
    }
}
