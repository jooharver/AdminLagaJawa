<?php

namespace App\Filament\Resources\BookingResource\Widgets;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class BookingStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        $todayBookings = Booking::whereDate('booking_date', $today)->count();
        $monthlyBookings = Booking::whereBetween('booking_date', [$startOfMonth, now()])->count();
        $pendingBookings = Transaction::where('status', 'pending')->count();

        return [
            Stat::make('Booking Hari Ini', $todayBookings)
                ->description('Jumlah booking lapangan hari ini')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make('Booking Pending', $pendingBookings)
                ->description('Booking dengan status pending')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Booking Bulan Ini', $monthlyBookings)
                ->description("Total booking bulan ini")
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('success'),

        ];
    }
}
