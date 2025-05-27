<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class BookingExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $now = Carbon::now();

        return Booking::with(['transaction', 'court', ])
            ->whereMonth('booking_date', $now->month)
            ->whereYear('booking_date', $now->year)
            ->get()
            ->map(function ($booking, $key) {
    return [
        'No' => $key + 1,
        'Booking ID' => $booking->id_booking,
        'Nama Pemesan' => $booking->transaction->user->name ?? '-',
        'Tanggal' => $this->formatDate($booking->booking_date),
        'Court' => optional($booking->court)->name ?? '-',
        'Durasi' => $booking->duration ? $booking->duration . ' Jam' : '-',
        'Slot Waktu' => implode(', ', $booking->time_slots ?? []),
        'Order ID' => optional($booking->transaction)->no_pemesanan ?? '-',
        'Total Amount' => 'Rp' . number_format(optional($booking->transaction)->total_amount ?? 0, 0, ',', '.'),
    ];
});

    }

    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('d-m-Y') : '-';
    }

    private function formatTime($time)
    {
        return $time ? Carbon::parse($time)->format('H:i') : '-';
    }

    public function headings(): array
    {
        return [
            'No',
            'Booking ID',
            'Nama Pemesan',
            'Tanggal',
            'Court',
            'Durasi',
            'Slot Waktu',
            'Order ID',
            'Total Amount',
        ];
    }
}
