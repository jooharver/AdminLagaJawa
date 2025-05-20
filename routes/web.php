<?php

use App\Exports\BookingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingLogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/booking/export-pdf', [BookingLogController::class, 'exportPDF'])->name('export-bookinglog');
Route::get('/export-booking', function () {
    return Excel::download(new BookingExport(), 'booking_data.xlsx');
})->name('export-booking');
