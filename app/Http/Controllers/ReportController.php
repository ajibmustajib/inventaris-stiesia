<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function facilities()
    {
        $roomTypes = RoomType::withCount('rooms')->get();

        $pdf = Pdf::loadView('pdf.facilities', [
            'roomTypes' => $roomTypes,
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('daftar-prasarana.pdf');
    }

    public function room_all()
    {

        $roomTypes = RoomType::with(['rooms.room_assets.item.itemType'])->get();

        $pdf = Pdf::loadView('pdf.inventory_room_type', [
            'roomTypes' => $roomTypes,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('daftar-ruangan.pdf');
    }

    public function item()
    {
        $items = \App\Models\Item::with(['roomAssets.room'])->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.item', [
            'items' => $items,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('daftar-barang.pdf');
    }

    public function item_all()
    {

        $items = \App\Models\Item::with([
            'itemType',
            'roomAssets.room.room_type',
        ])
            ->whereHas('roomAssets', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->take(50) 
            ->get();

        if ($items->isEmpty()) {
            dd('Data kosong nih', $items);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.item_all', [
            'items' => $items,
        ])->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-stok-barang.pdf');
    }

    public function room_asset(Room $room)
    {
        $room->load([
            'room_assets.item.itemType',
        ]);

        $pdf = Pdf::loadView('pdf.inventory', [
            'record' => $room,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-asset-'.$room->name.'.pdf');

    }

    public function room_image(Room $room)
    {

        $room->load([
            'room_assets.item',
        ]);

        $pdf = Pdf::loadView('pdf.inventory_image', [
            'record' => $room,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-gambar-'.$room->name.'.pdf');
    }

    
}
