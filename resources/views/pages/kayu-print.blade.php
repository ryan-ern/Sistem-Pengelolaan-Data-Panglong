<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Laporan Data Kayu</title>
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

        <h3>Laporan Data Kayu</h3>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Cabang</th>
                    <th>Jenis Kayu</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kayu as $i => $item)
                    <tr>
                        <td align="center">{{ $i + 1 }}</td>
                        <td>{{ $item->cabang->nama_cabang ?? '-' }}</td>
                        <td>{{ $item->jenis_kayu }}</td>
                        <td align="center">{{ $item->jumlah }}</td>
                        <td align="right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td align="right">
                            Rp {{ number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
