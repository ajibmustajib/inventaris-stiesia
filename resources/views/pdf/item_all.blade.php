<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Stok Barang per Ruangan</title>
    <style>
        @page { margin: 20px; }
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
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #f0f0f0; font-size: 12px; text-align: center; }
        td { vertical-align: top; }

        .room-name { text-align: left; }
        .stock-qty { text-align: right; }

        table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 15px; 
        }
        
        th, td { 
            border: 1px solid #333; 
            padding: 6px; 
            vertical-align: top; 
        }
        th { background: #f0f0f0; font-size: 12px; text-align: center; }

        .item-group {
            page-break-inside: avoid !important; 
        }
        
        .hidden-border-top {
            border-top: none !important; 
        }

    .visually-hidden {
        visibility: hidden;
        padding: 6px; 
    }
        
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

        <div class="title">DAFTAR STOK BARANG PER RUANGAN</div>
        <div class="subtitle">Rekapitulasi distribusi barang berdasarkan ruangan</div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">No</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2" style="width: 80px;">Total</th>
                <th colspan="2">Detail Stok per Ruangan</th>
            </tr>
            <tr>
                <th>Ruangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
    @php $grandTotal = 0; @endphp

    @foreach ($items as $i => $item)
        @php
            $details = collect($item->roomAssets)->filter(fn($ra) => $ra->stock > 0);
            $totalStock = $details->sum('stock');
            $grandTotal += $totalStock;
            $detailCounter = 0;
        @endphp

        @if ($totalStock > 0)
            @foreach ($details as $detail)
                @php
                    $isFirstDetail = ($detailCounter === 0);
                    $detailCounter++;
                    $main_cols_class = $isFirstDetail ? '' : 'hidden-border-top';
                @endphp
                
                <tr class="item-group"> 
                    
                    <td class="{{ $main_cols_class }}" style="width: 30px;">
                        @if ($isFirstDetail)
                            {{ $loop->parent->iteration }}
                        @else
                            {{-- Jika bukan baris pertama, biarkan kosong --}}
                            <span class="visually-hidden">{{ $loop->parent->iteration }}</span>
                        @endif
                    </td>

                    <td class="{{ $main_cols_class }}" style="text-align: left;">
                        @if ($isFirstDetail)
                            {{ $item->name }}
                        @else
                            <span class="visually-hidden">{{ $item->name }}</span>
                        @endif
                    </td>

                    <td class="{{ $main_cols_class }}" style="width: 80px; text-align:center;">
                        @if ($isFirstDetail)
                            {{ $totalStock }}
                        @else
                            <span class="visually-hidden">{{ $totalStock }}</span>
                        @endif
                    </td>

                    <td class="room-name">
                        {{ $detail->room->name ?? '-' }}
                        <div style="font-size:10px;color:#555;">
                            ({{ $detail->room->room_type->name ?? '-' }})
                        </div>
                    </td>
                    <td class="stock-qty" style="width: 80px; text-align:center;">{{ $detail->stock }}</td>
                </tr>
            @endforeach
        @endif
    @endforeach

    {{-- Baris total akhir --}}
    <tr>
        <td colspan="3" style="text-align: right; font-weight: bold;">GRAND TOTAL</td>
        <td colspan="2" style="font-weight: bold;">{{ $grandTotal }}</td>
    </tr>
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
</body>
</html>
