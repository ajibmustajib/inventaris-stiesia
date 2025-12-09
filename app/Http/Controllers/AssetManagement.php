<?php

namespace App\Http\Controllers;

use App\Models\InventoryManagement;

class AssetManagement extends Controller
{
    public function create($id_room)
    {
        $room = InventoryManagement::findOrFail($id_room);

        return view('aset.create', compact('room'));
    }
}
