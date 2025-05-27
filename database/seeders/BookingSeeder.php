<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create([
            'transaction_id' => 1,
            'court_id' => 2,
            'booking_date' => '2025-05-28',
            'time_slots' => json_encode(['09:00:00']),
            // 'duration' => 1, // jam misal
            // 'amount' => 100000, // misal 3 jam x 100k/jam
        ]);

        Booking::create([
            'transaction_id' => 2,
            'court_id' => 3,
            'booking_date' => '2025-05-28',
            'time_slots' => json_encode(['06:00:00', '07:00:00']),
            // 'duration' => 2, // jam misal
            // 'amount' => 200000, // misal 3 jam x 100k/jam
        ]);

        Booking::create([
            'transaction_id' => 3,
            'court_id' => 1,
            'booking_date' => '2025-05-28',
            'time_slots' => json_encode(['06:00:00']),
            // 'duration' => 1, // jam misal
            // 'amount' => 75000, // misal 3 jam x 100k/jam
        ]);

        Booking::create([
            'transaction_id' => 3,
            'court_id' => 4,
            'booking_date' => '2025-05-28',
            'time_slots' => json_encode(['06:00:00']),
            // 'duration' => 1, // jam misal
            // 'amount' => 75000, // misal 3 jam x 100k/jam
        ]);
    }
}
