<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DashboardLineChart extends ChartWidget
{
    protected static ?string $heading = 'Pertumbuhan Transaksi (7 Hari Terakhir)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $dates = collect(range(6, 0))->map(function ($daysAgo) {
            return Carbon::now()->subDays($daysAgo)->format('Y-m-d');
        });

        $transactionCounts = $dates->map(function ($date) {
            return Transaction::whereDate('created_at', $date)->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $transactionCounts,
                    'borderColor' => '#3b82f6', // biru
                    'backgroundColor' => 'rgba(59, 130, 246, 0.3)', // biru transparan
                    'fill' => true,
                    'tension' => 0.4, // lengkung lembut
                ],
            ],
            'labels' => $dates->map(function ($date) {
                return Carbon::parse($date)->translatedFormat('d M'); // contoh: 26 Mei
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // protected function getColumnSpan(): int | string | array
    // {
    //     return 2; // Lebar penuh (bisa 1 jika digabung dengan widget lain)
    // }
}
