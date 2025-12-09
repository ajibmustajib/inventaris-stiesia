<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Gambar Aset</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        .kop {
            text-align: center;
            margin-top: -60px;
        }

        .kop img {
            width: 70px;
            height: auto;
        }

        .kop .judul {
            font-size: 16px;
            font-weight: bold;
        }

        .kop .alamat, .kop .email {
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        hr.garis-bawah {
            border: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        table.table {
            font-size: 13px;
            width: 100%;
            border-collapse: collapse;
        }

        table.table th, table.table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        table.table img {
            width: 70px;
            height: 70px;
            object-fit: cover;
        }
    </style>
</head>
<body>
  <div class="container">
    <div class="header">
        <div class="kop">
            <img src="assets/images/logo/logo-stiesia.png" alt="Logo">
            <div class="judul">SEKOLAH TINGGI ILMU EKONOMI INDONESIA (STIESIA) SURABAYA</div>
            <div class="alamat">Jalan Menur Pumpungan 30 Surabaya - 60118</div>
            <div class="alamat">T: (031) 594-7505; 594-7840 | Fax : (031) 593-2218</div>
            <div class="email">E: stiesia@sby.dnet.id</div>
        </div>

        <hr class="garis-bawah">

        <div class="title" style="text-align: center; font-size: 16px; font-weight: bold; margin-top: 15px;">
            Laporan Gambar Aset Ruang {{ $record->name }}
        </div>
        <div style="text-align: center; font-size: 14px; margin-top: 5px;">
            Ukuran {{ $record->dimension }} M²
        </div>
    </div>

    <br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>GAMBAR</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($record->room_assets as $asset)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $asset->item->name ?? '-' }}</td>
                    <td>
                        @if(!empty($asset->item->image))
                            <img src="{{ storage_path('app/media/' . $asset->item->image) }}" alt="Gambar Barang">
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

      <!-- Footer tanda tangan -->
    <br><br>
    <table width="100%">
        <tr>
            <td style="width:60%"></td>
            <td style="width:40%; text-align:center;">
                Bagian Rumah Tangga<br><br><br><br>
                <u>(...........................................)</u><br>
                NIP. ....................................
            </td>
        </tr>
    </table>
  </div>
</body>
</html>
