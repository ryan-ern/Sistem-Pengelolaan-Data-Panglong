<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Struk Transaksi</title>

        <style>
            @page {
                margin: 5px;
            }

            body {
                font-family: monospace;
                font-size: 10px;
                margin: 0;
                padding: 0;
            }

            .header {
                text-align: center;
                margin-bottom: 6px;
            }

            .header h3 {
                margin: 0;
                font-size: 12px;
            }

            .divider {
                border-top: 1px dashed #000;
                margin: 6px 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td {
                padding: 2px 0;
                vertical-align: top;
            }

            .label {
                width: 40%;
            }

            .value {
                width: 60%;
                text-align: right;
            }

            .total {
                font-weight: bold;
                font-size: 11px;
            }

            .footer {
                text-align: center;
                margin-top: 12px;
            }
        </style>
    </head>

    <body>

        {{-- HEADER --}}
        <div class="header">
            <h3>STRUK TRANSAKSI</h3>
            <div>{{ $transaksi->cabang->nama_cabang }}</div>
            <div>{{ $transaksi->tanggal }}</div>
        </div>

        <div class="divider"></div>

        {{-- INFO TRANSAKSI --}}
        <table>
            <tr>
                <td class="label">Informasi</td>
                <td class="" style="white-space: pre-line;">{{ $transaksi->informasi }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        {{-- TOTAL --}}
        <table>
            <tr class="total">
                <td class="label">TOTAL</td>
                <td class="value">
                    Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        {{-- FOOTER --}}
        <div class="footer">
            <div>Dicetak oleh</div>
            <br>
            <div>{{ $transaksi->pengguna->nama }}</div>
            <br>
            <div>*** TERIMA KASIH ***</div>
        </div>

    </body>

</html>
