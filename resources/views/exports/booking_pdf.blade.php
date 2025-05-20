<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Booking</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Booking Lapangan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Booking</th>
                <th>Nama Pemesan</th>
                <th>Tanggal Booking</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Durasi</th>
                <th>Lapangan</th>
                <th>Status</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->id_booking }}</td>
                    <td>{{ $booking->requester->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                    <td>{{ $booking->duration ?? '-' }}</td>
                    <td>{{ $booking->court->name ?? '-' }}</td>
                    <td>{{ ucfirst($booking->approval_status) }}</td>
                    <td>{{ ucfirst($booking->payment->payment_status ?? '-') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align: right; font-size: 11px;">Generated at: {{ now()->format('d-m-Y H:i:s') }}</p>
</body>
</html>
