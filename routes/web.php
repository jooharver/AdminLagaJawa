<?php

use App\Exports\BookingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingLogController;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

// Export PDF
Route::get('/booking/export-pdf', function () {
    $monthYear = Carbon::now()->translatedFormat('F_Y'); 
    return app(BookingLogController::class)->exportPDF($monthYear);
})->name('export-bookinglog');

// Export Excel
Route::get('/export-booking', function () {
    Carbon::setLocale('id');
    $monthYear = Carbon::now()->translatedFormat('F_Y'); 
    $fileName = 'laporan_pemesanan_laga_jawa_futsal_bulan_' . strtolower($monthYear) . '.xlsx';
    return Excel::download(new BookingExport(), $fileName);
})->name('export-booking');
