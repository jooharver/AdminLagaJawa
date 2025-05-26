<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Court::create([
            'name' => 'Court 1',
            'type' => 'Standar',
            'price_per_hour' => '75000',
        ]);

        Court::create([
            'name' => 'Court 2',
            'type' => 'Premium',
            'price_per_hour' => '100000',
        ]);


        Court::create([
            'name' => 'Court 3',
            'type' => 'Premium',
            'price_per_hour' => '100000',
        ]);


        Court::create([
            'name' => 'Court 4',
            'type' => 'Standar',
            'price_per_hour' => '75000',
        ]);


        Court::create([
            'name' => 'Court 5',
            'type' => 'Premium',
            'price_per_hour' => '100000',
        ]);
    }
}
