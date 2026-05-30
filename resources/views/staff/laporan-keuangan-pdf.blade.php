<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .sub {
            text-align: center;
            margin-bottom: 20px;
        }

        .total {
            margin-bottom: 15px;
            font-weight: bold;
            color: green;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
        }

        th {
            background: #f3f3f3;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>

    <h2>LAPORAN KEUANGAN DENDA</h2>

    <div class="sub">
        Sistem Informasi Perpustakaan
    </div>

    <div>
        Tanggal Cetak :
        {{ now()->format('d M Y H:i') }}
    </div>

    <br>

    <div class="total">
        Total Denda Lunas :
        Rp {{ number_format($totalDendaLunas, 0, ',', '.') }}
    </div>

    <table>

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Buku</th>
            <th>Tanggal Bayar</th>
            <th>Denda</th>
        </tr>

        @foreach($loans as $index => $loan)

        <tr>
            <td>{{ $index + 1 }}</td>

            <td>{{ $loan->user->name }}</td>

            <td>{{ $loan->book->judul }}</td>

            <td>
                {{ $loan->tanggal_bayar
                    ? $loan->tanggal_bayar->format('d M Y H:i')
                    : '-' }}
            </td>

            <td>
                Rp {{ number_format($loan->denda_dibayar, 0, ',', '.') }}
            </td>
        </tr>

        @endforeach

    </table>

    <div class="footer">

        Dicetak:
        {{ now()->format('d M Y H:i') }}

    </div>

</body>

</html>
