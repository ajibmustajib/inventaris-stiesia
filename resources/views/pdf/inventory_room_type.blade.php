<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Ruangan Semua Jenis</title>
    <style>
        @page { margin: 20px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .header-table { width: 100%; border: none; }
        .header-table td { vertical-align: middle; text-align: center; }
        .header-table .logo { width: 80px; text-align: left; }
        .judul { font-size: 16px; font-weight: bold; }
        .alamat { font-size: 12px; margin: 0; padding: 0; }
        .garis-bawah { border-bottom: 2px solid #000; margin-top: 5px; margin-bottom: 15px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin-top: 10px; }
        .subtitle { text-align: center; font-size: 12px; margin-top: 3px; margin-bottom: 15px; }

        h3 { margin: 15px 0 5px 0; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #f0f0f0; font-size: 12px; text-align: center; }
        td { vertical-align: top; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="logo" style="border: 0px;">
                <img src="assets/images/logo/logo-stiesia.png" width="70px" height="70px">
            </td>
            <td style="border: 0px;">
                <div class="judul">SEKOLAH TINGGI ILMU EKONOMI INDONESIA (STIESIA) SURABAYA</div>
                <div class="alamat">Jalan Menur Pumpungan 30 Surabaya - 60118</div>
                <div class="alamat">T: (031) 594-7505; 594-7840 | Fax: (031) 593-2218</div>
                <div class="alamat">E: stiesia@sby.dnet.id</div>
            </td>
        </tr>
    </table>
    <div class="garis-bawah"></div>

    <div class="title">DAFTAR SEMUA RUANGAN</div>
    <div class="subtitle">Rekapitulasi seluruh ruangan berdasarkan kategori jenis ruangan</div>

    <!-- Tabel per jenis ruangan -->
    @foreach ($roomTypes as $type)
        <h3>{{ strtoupper($type->name) }}</h3>
        <table>
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th>Nama Ruang</th>
                    <th>Keterangan</th>
                    <th>Dimensi (m²)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($type->rooms as $room)
                    <tr>
                        <td class="center">{{ $loop->iteration }}</td>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->description ?? '-' }}</td>
                        <td class="center">{{ $room->dimension ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="center">Tidak ada ruangan untuk jenis ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

    <!-- Footer tanda tangan -->
    <br><br>
    <table width="100%">
        <tr>
            <td style="width:60%; border:0px;"></td>
            <td style="width:40%; text-align:center; border:0px;">
                Bagian Rumah Tangga<br><br><br><br>
                <u>(...........................................)</u><br>
                NIP. ....................................
            </td>
        </tr>
    </table>
</body>
</html>
