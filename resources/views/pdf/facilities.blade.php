<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Prasarana</title>
    <style>
        @page {
            margin: 20px;
        }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .header { width: 100%; }
        .header-table { width: 100%; border: none; }
        .header-table td { vertical-align: middle; text-align: center; }
        .header-table .logo { width: 80px; text-align: left; }
        .judul { font-size: 16px; font-weight: bold; }
        .alamat { font-size: 12px; margin: 0; padding: 0; }
        .garis-bawah { border-bottom: 2px solid #000; margin-top: 5px; margin-bottom: 15px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin-top: 10px; }
        .subtitle { text-align: center; font-size: 12px; margin-top: 3px; margin-bottom: 10px; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: center; }
        th { background: #f0f0f0; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr style="border: none;">
                <td class="logo" style="border: none;">
                    <img src="assets/images/logo/logo-stiesia.png" width="70px" height="70px">
                </td>
                <td style="border: none;">
                    <div class="judul">SEKOLAH TINGGI ILMU EKONOMI INDONESIA (STIESIA) SURABAYA</div>
                    <div class="alamat">Jalan Menur Pumpungan 30 Surabaya - 60118</div>
                    <div class="alamat">T: (031) 594-7505; 594-7840 | Fax : (031) 593-2218</div>
                    <div class="alamat">E: stiesia@sby.dnet.id</div>
                </td>
            </tr>
        </table>
        <div class="garis-bawah"></div>

        <div class="title">DAFTAR PRASARANA PENUNJANG & PENYEDIAAN SARANA UTAMA</div>
        <div class="subtitle">Berikut ini informasi Prasarana Penunjang & Penyediaan Sarana Utama</div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Jenis Prasarana</th>
                <th rowspan="2">Jumlah Unit</th>
                <th rowspan="2">Total Luas (m²)</th>
                <th colspan="2">Kepemilikan</th>
                <th colspan="2">Kondisi</th>
                <th rowspan="2">Utilisasi (Jam/Minggu)</th>
            </tr>
            <tr>
                <th>SD</th>
                <th>SW</th>
                <th>Terawat</th>
                <th>Tidak Terawat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roomTypes as $i => $rt)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $rt->name }}</td>
                    <td>{{ $rt->rooms_count }}</td>
                    <td>{{ $rt->total_area }}</td>
                    <td>{!! $rt->ownership_status ? '&#10003;' : '&#10007;' !!}</td>
                    <td>{!! $rt->ownership_status ? '&#10003;' : '&#10007;' !!}</td>
                    <td>{!! $rt->condition_status ? '&#10003;' : '&#10007;' !!}</td>
                    <td>{!! $rt->condition_status ? '&#10003;' : '&#10007;' !!}</td>
                    <td>{{ $rt->utilization }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

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
