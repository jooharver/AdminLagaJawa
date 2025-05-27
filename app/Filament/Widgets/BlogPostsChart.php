<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Pertumbuhan Booking (7 Hari Terakhir)';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Ambil tanggal 7 hari ke belakang
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Ambil data dan kelompokkan berdasarkan tanggal
        $data = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Buat array tanggal dan count
        $dates = [];
        $counts = [];

        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->toDateString();
            $dates[] = $date;

            $counts[] = $data->firstWhere('date', $date)?->count ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Booking',
                    'data' => $counts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)', // biru
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diganti 'line' kalau mau garis
    }
}
