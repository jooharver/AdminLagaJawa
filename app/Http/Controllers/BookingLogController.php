<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Exports\BookingExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class BookingLogController extends Controller
{
            public function exportExcel()
            {
                return Excel::download(new BookingExport(), 'usage_data.xlsx');
            }

public function exportPDF()
{
    Carbon::setLocale('id');
    $now = now();
    $monthYear = $now->translatedFormat('F_Y'); 
    $monthYearDisplay = $now->translatedFormat('F Y'); 

    $reports = Booking::with(['transaction.user', 'court'])
        ->whereHas('transaction')
        ->whereMonth('booking_date', $now->month)
        ->whereYear('booking_date', $now->year)
        ->get();

    $totalSemua = $reports->sum(function ($booking) {
        return $booking->transaction->total_amount ?? 0;
    });

    $html = view('exports.booking_pdf', compact('reports', 'monthYearDisplay', 'totalSemua'))->render();

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);

    $fileName = 'laporan_pemesanan_laga_jawa_futsal_bulan_' .$monthYear . '.pdf';
    $mpdf->Output($fileName, 'D');

}
}