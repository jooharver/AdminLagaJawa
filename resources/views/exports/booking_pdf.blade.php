<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Laporan Booking Lapangan</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&display=swap" rel="stylesheet">
  <style>
   body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 30px;
  background-color: #f9f9f9;
}
.header {
  display: flex;
  align-items: center;
  gap: 20px;
}
.header-img {
  width: 500px;
  height: auto;
}
.header-text {
  text-align: left;
}
.header-title {
  font-family: 'Montserrat', sans-serif;
  font-weight: 900;
  margin: 0;
}
.header-address {
  margin: 2px 0;
}
.separator {
  border-top: 4px solid #000;
  margin: 10px 0 30px 0;
}
.section-title {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
}
.table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
  background-color: #fff;
}
th {
  background-color: #52B788; 
  color: white;
  padding: 8px;
  text-align: center;
  border: 1px solid #000;
}
td {
  padding: 6px;
  text-align: center;
  border: 1px solid #000;
}
td:last-child {
  color: green;
  font-weight: bold;
}
.note {
  font-size: 14px;
  margin-top: 20px;
  text-align: justify;
}
body {
  font-family: 'Arial, sans-serif';
  font-size: 14px;
}
tbody tr:nth-child(even) {
  background-color: #f5f5f5;
}
.report-card {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 0 15px rgba(0,0,0,0.1);
  margin: auto;
  width: 90%;
  max-width: 900px;
  font-family: 'Segoe UI', sans-serif;
}

  </style>
</head>
<body>
  <div class="container">
    <div style="display: flex; align-items: center; gap: 15px;">
      <img src="{{ public_path('images/logoLJ.png') }}" width="120" alt="Logo" />
      <div>
        <h1 style="margin: 0; font-family: 'Montserrat', sans-serif;">LJ Futsal</h1>
        <p style="margin: 0;">Jl. Bunga Melati No.9, Malang, Jawa Timur<br>Telepon: 0811 3443 4544</p>
      </div>
    </div>

    <hr class="separator" />

    <h4 style="text-align: center; margin-bottom: 20px;">
    LAPORAN PEMESANAN LAGA JAWA FUTSAL <br>
    BULAN {{ strtoupper($monthYearDisplay) }}
</h4>
    <p style="text-align:right; font-size:12px; color:#888;">
      Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}
    </p>
  
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Pemesan</th>
          <th>Tanggal</th>
          <th>Court</th>
          <th>Order ID</th>
          <th>Durasi</th> 
          <th>Slot Waktu</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach($reports as $index => $booking)
  <tr>
    <td>{{ $booking->id_booking }}</td>
    <td>{{ $booking->transaction->user->name ?? '-' }}</td>
    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y') }}</td>
    <td>{{ $booking->court->name ?? '-' }}</td>
    <td>{{ $booking->transaction->no_pemesanan ?? '-' }}</td>
    <td>{{ $booking->duration ? $booking->duration . ' Jam' : '-' }}</td>
    <td>{{ implode(', ', $booking->time_slots ?? []) }}</td>
    <td>Rp{{ number_format($booking->transaction->total_amount ?? 0, 0, ',', '.') }}</td>
  </tr>
@endforeach

      </tbody>
    </table>

    <p class="note">
      Laporan ini merupakan seluruh data pemesanan yang telah tercatat dalam sistem per tanggal cetak.
    </p>
    <p style="font-size: 12px; font-style: italic; text-align: justify; margin-top: 30px;">
      *Laporan ini dihasilkan secara otomatis oleh sistem. Tidak memerlukan tanda tangan.
    </p>
  </div>
</body>
</html>
