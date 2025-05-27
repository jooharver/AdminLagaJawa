<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DashboardPieChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Transaksi (7 Hari Terakhir)';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $statuses = ['pending', 'paid', 'cancelled'];
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $counts = array_map(function ($status) use ($startDate, $endDate) {
            return Transaction::where('status', $status)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }, $statuses);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $counts,
                    'backgroundColor' => ['#facc15', '#22c55e', '#ef4444'], // kuning, hijau, merah
                ],
            ],
            'labels' => $statuses,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected int | string | array $columnSpan = [
        'md' => 1,
    ];

}
