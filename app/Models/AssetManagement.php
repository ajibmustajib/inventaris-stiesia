<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetManagement extends Model
{
    protected $fillable = [
        'id_room',
        'id_item',
        'stock',
        'description',
        'image',
    ];

    protected $attributes = [
    'stock' => 0,
    ];

    public function Room()
    {
        return $this->belongsTo(InventoryManagement::class, 'id_room', 'id_room');
    }

    public function Item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id');
    }
}
