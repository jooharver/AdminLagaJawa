<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class BookingExport implements FromCollection, WithHeadings
{
    /**
     * Ambil data yang akan diekspor ke Excel.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Booking::with(['requester', 'court', 'payment'])->get()->map(function ($booking, $key) {
            return [
                'No' => $key + 1,
                'Booking ID' => $booking->id_booking,
                'Nama Pemesan' => $booking->requester->name ?? '-',
                'Tanggal Booking' => $this->formatDate($booking->booking_date),
                'Jam Mulai' => $this->formatTime($booking->start_time),
                'Jam Selesai' => $this->formatTime($booking->end_time),
                'Durasi' => $booking->duration ?? '-',
                'Lapangan' => $booking->court->name ?? '-',
                'Status Approval' => ucfirst($booking->approval_status),
                'Metode Pembayaran' => $booking->payment->payment_method ?? '-',
                'Status Pembayaran' => ucfirst($booking->payment->payment_status ?? '-'),
            ];
        });
    }

    /**
     * Format tanggal menjadi 'd-m-Y'.
     *
     * @param  string|Carbon|null  $date
     * @return string
     */
    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('d-m-Y') : '-';
    }

    /**
     * Format waktu menjadi 'H:i'.
     *
     * @param  string|Carbon|null  $time
     * @return string
     */
    private function formatTime($time)
    {
        return $time ? Carbon::parse($time)->format('H:i') : '-';
    }

    /**
     * Judul kolom di Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Booking ID',
            'Nama Pemesan',
            'Tanggal Booking',
            'Jam Mulai',
            'Jam Selesai',
            'Durasi',
            'Lapangan',
            'Status Approval',
            'Metode Pembayaran',
            'Status Pembayaran',
        ];
    }
}
