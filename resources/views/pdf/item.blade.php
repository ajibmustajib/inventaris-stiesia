<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Daftar Jumlah Barang</title>
    <style>
        @page {
            margin: 20px;
        }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .header { width: 100%; }
        .header-table { width: 100%; border: none; }
        .header-table td { vertical-align: middle; text-align: center; border: none !important; }
        .header-table .logo { width: 80px; text-align: left; }
        .judul { font-size: 16px; font-weight: bold; }
        .alamat { font-size: 12px; margin: 0; padding: 0; }
        .garis-bawah { border-bottom: 2px solid #000; margin-top: 5px; margin-bottom: 15px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin-top: 10px; }
        .subtitle { text-align: center; font-size: 12px; margin-top: 3px; margin-bottom: 10px; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background: #f0f0f0; font-size: 12px; }
    </style>
</head>
<body>
    <!-- Header / Kop -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo">
                    <img src="assets/images/logo/logo-stiesia.png" width="70px" height="70px">
                </td>
                <td>
                    <div class="judul">SEKOLAH TINGGI ILMU EKONOMI INDONESIA (STIESIA) SURABAYA</div>
                    <div class="alamat">Jalan Menur Pumpungan 30 Surabaya - 60118</div>
                    <div class="alamat">T: (031) 594-7505; 594-7840 | Fax : (031) 593-2218</div>
                    <div class="alamat">E: stiesia@sby.dnet.id</div>
                </td>
            </tr>
        </table>
        <div class="garis-bawah"></div>

        <div class="title">DAFTAR JUMLAH BARANG</div>
        <div class="subtitle">Rekapitulasi Barang per Ruangan</div>
    </div>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Total</th>
                <th>Ruangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($items as $item)
                @php
                    $total = $item->roomAssets->sum('stock');
                    $rowspan = max(1, $item->roomAssets->count());
                @endphp

                {{-- Jika barang punya distribusi ke ruangan --}}
                @if($item->roomAssets->count() > 0)
                    @foreach($item->roomAssets as $index => $asset)
                        <tr>
                            @if($index == 0)
                                <td rowspan="{{ $rowspan }}">{{ $no++ }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $item->name }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $total }}</td>
                            @endif
                            <td>{{ $asset->room->name ?? '-' }}</td>
                            <td>{{ $asset->stock }}</td>
                        </tr>
                    @endforeach
                @else
                    {{-- Jika barang tidak ada di ruangan mana pun --}}
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>0</td>
                        <td>-</td>
                        <td>0</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
