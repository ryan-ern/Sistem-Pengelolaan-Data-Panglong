<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Laporan Data Transaksi</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 11px;
            }

            h3 {
                text-align: center;
                margin-bottom: 10px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 6px;
            }

            th {
                background: #f2f2f2;
                text-align: center;
            }

            td {
                vertical-align: middle;
            }
        </style>
    </head>

    <body>

        <h3>Laporan Data Transaksi</h3>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Cabang</th>
                    <th>Pengguna</th>
                    <th>Jenis</th>
                    <th>Informasi</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $i => $item)
                    <tr>
                        <td align="center">{{ $i + 1 }}</td>
                        <td>{{ $item->tanggal ?? '-' }}</td>
                        <td>{{ $item->cabang->nama_cabang }}</td>
                        <td style="text-transform: capitalize;">{{ $item->pengguna->nama }}</td>
                        <td style="text-transform: capitalize;" align="center">{{ $item->jenis_transaksi }}</td>
                        <td style="white-space: pre-line;">{{ $item->informasi }}</td>
                        <td align="right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
