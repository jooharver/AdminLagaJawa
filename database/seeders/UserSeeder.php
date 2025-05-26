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
            'phone' => '087870498762',
            'address' => 'Jl.Sudimoro 11',
            'name' => 'Muhammad Rifky',
            'email' => 'rifky@email.com',
            'password' => Hash::make('rifky'),
        ]);

        User::create([
            'phone' => '081567298787',
            'address' => 'Jl. Tlogomas 24',
            'name' => 'Aunurrofiq Farhan',
            'email' => 'rofiq@email.com',
            'password' => Hash::make('rofiq'),
        ]);

        User::create([
            'phone' => '085162889276',
            'address' => 'Jl. Bunga kertas 25',
            'name' => 'Febby Mathelda',
            'email' => 'febby@email.com',
            'password' => Hash::make('febby'),
        ]);

        User::create([
            'phone' => '088761666725',
            'address' => 'Jl. Remujung 67',
            'name' => 'Hilmi Irfan',
            'email' => 'hilmi@email.com',
            'password' => Hash::make('hilmi'),
        ]);

        User::create([
            'phone' => '085670463683',
            'address' => 'Jl. Anomali Brainrot 01',
            'name' => 'Tung Tung Tung Sahur',
            'email' => 'tung@email.com',
            'password' => Hash::make('tung'),
        ]);

        User::create([
            'phone' => '087870463683',
            'address' => 'Jl.Sudimoro 11',
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin'),
        ]);


        User::create([
            'phone' => '087870463683',
            'address' => 'Jl.Sudimoro 1',
            'name' => 'LJ Mawar',
            'email' => 'ljm@email.com',
            'password' => Hash::make('ljm'),
        ]);

        User::create([
            'phone' => '087870463683',
            'address' => 'Jl.Sudimoro 2',
            'name' => 'LJ Serigala',
            'email' => 'ljs@email.com',
            'password' => Hash::make('ljs'),
        ]);

        User::create([
            'phone' => '087870463683',
            'address' => 'Jl.Sudimoro 3',
            'name' => 'Sudimoro FC',
            'email' => 'sfc@email.com',
            'password' => Hash::make('sfc'),
        ]);
    }
}
