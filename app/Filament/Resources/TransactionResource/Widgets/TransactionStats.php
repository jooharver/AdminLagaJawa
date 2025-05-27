<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TransactionStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $currentMonthName = Carbon::now()->translatedFormat('F'); // nama bulan seperti "Mei"

        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $monthlyTransactions = Transaction::whereBetween('created_at', [$startOfMonth, now()])->count();
        $pendingTransactions = Transaction::where('status', 'pending')->count();

        return [
            Stat::make('Transaksi Hari Ini', $todayTransactions)
                ->description('Jumlah transaksi dibuat hari ini')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make('Transaksi Pending', $pendingTransactions)
                ->description('Transaksi dengan status pending')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Transaksi Bulan Ini', $monthlyTransactions)
                ->description("Total transaksi bulan $currentMonthName")
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('success'),

        ];
    }
}
