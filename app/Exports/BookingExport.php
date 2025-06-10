<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class BookingExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    private $totalKeseluruhan = 0;

    public function collection()
    {
        $now = Carbon::now();

        $bookings = Booking::with(['transaction', 'court'])
            ->whereHas('transaction')
            ->whereMonth('booking_date', $now->month)
            ->whereYear('booking_date', $now->year)
            ->get();

        $this->totalKeseluruhan = $bookings->sum(function ($booking) {
            return optional($booking->transaction)->total_amount ?? 0;
        });

        return $bookings->map(function ($booking, $key) {
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD9EDF7'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A1:I$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                $row = $lastRow + 1;
                $sheet->setCellValue('H' . $row, 'Total Keseluruhan:');
                $sheet->setCellValue('I' . $row, 'Rp' . number_format($this->totalKeseluruhan, 0, ',', '.'));
                $sheet->getStyle("H$row:I$row")->applyFromArray([
                    'font' => ['bold' => true],
                ]);
            },
        ];
    }
}
