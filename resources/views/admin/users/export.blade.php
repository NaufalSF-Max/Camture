<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan User Camture</title>
    <style>
        /* CSS ini akan dibaca oleh Excel sebagai formatting */
        body {
            font-family: sans-serif;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: #E27396;
        }

        /* Warna Camture Rose */
        .subtitle {
            font-size: 12px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #E27396;
            /* Header Pink */
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #000000;
            padding: 10px;
            text-align: center;
        }

        td {
            border: 1px solid #000000;
            padding: 8px;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bg-gray {
            background-color: #f3f3f3;
        }

        .total-row {
            font-weight: bold;
            background-color: #EADAB2;
        }

        /* Warna Camture Beige */
    </style>
</head>

<body>

    {{-- KOP LAPORAN --}}
    <table>
        <tr>
            <td colspan="5" class="title" align="center" style="height: 30px; font-size: 20px;">
                LAPORAN PENGGUNA - CAMTURE PHOTOBOOTH
            </td>
        </tr>
        <tr>
            <td colspan="5" align="center" style="font-style: italic;">
                Jl. HS. Ronggowaluyo, Telukjambe Timur, Karawang
            </td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Dicetak Oleh:</strong> {{ $summary['generated_by'] }}</td>
            <td colspan="3" class="text-right"><strong>Tanggal Cetak:</strong> {{ $summary['generated_at'] }}</td>
        </tr>
    </table>

    <br>

    {{-- TABEL DATA UTAMA --}}
    <table>
        <thead>
            <tr>
                <th width="5" style="width: 30px;">No</th>
                <th width="25" style="width: 200px;">Nama Lengkap</th>
                <th width="30" style="width: 250px;">Email Pengguna</th>
                <th width="20" style="width: 150px;">Tanggal Bergabung</th>
                <th width="15" style="width: 120px;">Jumlah Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-center">{{ $user->photos_count }}</td>
                </tr>
            @endforeach

            {{-- SUMMARY / TOTAL DI BAWAH --}}
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL FOTO TERSIMPAN DALAM SISTEM</td>
                <td class="text-center">{{ $summary['total_photos'] }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>