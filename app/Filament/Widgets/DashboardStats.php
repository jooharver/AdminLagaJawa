<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DashboardStats extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        $todayBookings = Transaction::whereDate('created_at', $today)->count();
        $pendingBookings = Transaction::where('status', 'pending')->count();

        // Hitung total pendapatan hari ini dari transaksi yang dibuat hari ini dengan status bukan cancelled
        $todayRevenue = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid') // hanya payment_status paid
            ->sum('total_amount');
        // asumsi kolom total_amount menyimpan pendapatan

        return [
            Stat::make('Transaksi Hari Ini', $todayBookings)
                ->description('jumlah transaksi hari ini')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make('Booking Pending', $pendingBookings)
                ->description('Booking dengan status pending')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description("Total pendapatan hari ini")
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
        ];
    }
}
