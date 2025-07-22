<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Inventory</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <h2>Detail Data Inventory</h2>

    <table>
        <tr><th>Kode Ruangan</th><td>{{ $record->id_room }}</td></tr>
        <tr><th>Nama Ruangan</th><td>{{ $record->room_name }}</td></tr>
        <tr><th>Deskripsi</th><td>{{ $record->room_description }}</td></tr>
        <tr><th>Dimensi</th><td>{{ $record->dimension }}</td></tr>
        <tr><th>Penggunaan</th><td>{{ $record->utilization }}</td></tr>
        <tr><th>Opsi Ruangan</th><td>{{ $record->room_option }}</td></tr>
    </table>
</body>
</html>
