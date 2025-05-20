<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Exports\BookingExport;
use Maatwebsite\Excel\Facades\Excel;

class BookingLogController extends Controller
{
            // Menangani ekspor Excel
            public function exportExcel()
            {
                return Excel::download(new BookingExport(), 'usage_data.xlsx');
            }

            public function exportPDF()
            {
                $reports = Booking::with(['requester', 'court'])->get();

                // Siapkan HTML untuk PDF
                $html = view('exports.booking_pdf', compact('reports'))->render();

                // Buat instance mPDF dan konversi HTML ke PDF
                $mpdf = new Mpdf();
                $mpdf->WriteHTML($html);

                // Unduh file PDF
                $mpdf->Output('Report.pdf', 'D');
            }
}
