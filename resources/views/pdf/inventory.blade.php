<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootrap Versi 3.3.6 -->
    <link rel="stylesheet" href="assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/icon-kit/dist/css/iconkit.min.css">

    <style>
      table thead tr th{
        text-align:center;
        margin-top: -5px;
        vertical-align: middle;
      }
    </style>
    <style>
        @page {
            margin: 20px; /* Atur margin halaman */
        }

        body {
            font-family: sans-serif;
            margin: 0px; /* Reset margin body */
            padding: 0px;
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

        .garis {
            border-bottom: 2px solid #000;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        h4 {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 14px;
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

        hr.garis-bawah {
            border: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 15px;
        }

    </style>
</head>
<body>
  <div class="container">
    <div class="row">
        <div class="col-xs-2">
            <img src="assets/images/logo/logo-stiesia.png" width="70px" height="70px">
        </div>
        <div class="col-xs-10 kop">
            <div class="judul">SEKOLAH TINGGI ILMU EKONOMI INDONESIA (STIESIA) SURABAYA</div>
            <div class="alamat">Jalan Menur Pumpungan 30 Surabaya - 60118</div>
            <div class="alamat">T: (031) 594-7505; 594-7840 | Fax : (031) 593-2218</div>
            <div class="email">E: stiesia@sby.dnet.id</div>
        </div>
        <hr class="garis-bawah">
    </div>
  <?php //print_r($jenisruang);die;?>
  <h4 style="text-align:center;">Laporan Pengelolaan {{ $record->room_name }}<br/>Ukuran &nbsp;{{ $record->dimension }}</h4><br />
  
  <br />          
  <table class="table table-bordered" cellpadding="4" style="font-size: 15px; margin-top: -25px">
    <thead>
      <tr>
        <th>NO</th>
        <th>NAMA</th>
        <th>JUMLAH</th>
        <th>KETERANGAN</th>
        <th>COA</th>
      </tr>
    </thead>
    <tbody>
        <?php $i=1; foreach($record as $data):?>
          <tr>
            <td style="text-align: center"><?php echo $i;?></td>
            <td style="text-align: center">{{ $record->room_name }}</td>
            <td style="text-align: center">{{ $record->room_name }}</td>
            <td style="text-align: center">{{ $record->room_name }}</td>
            <td style="text-align: center">{{ $record->room_name }}</td>
          </tr>
        <?php $i++; endforeach;?> 
    </tbody>
  </table>
  <table width="100%">
        <tr >
            <td style="width:20%"></td>
            <td style="width:20%"></td>
            <td style="width:20%"></td>
            <td style="width:40%">Bagian Rumah Tangga</td>
        </tr>    
  </table> 
  </div>
</body>
</html>