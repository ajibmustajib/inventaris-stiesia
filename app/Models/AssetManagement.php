<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetManagement extends Model
{
    protected $table = 'inventory_management_det';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int'; // atau 'bigint' jika kamu pakai bigserial

    public $timestamps = true; // karena ada created_at dan updated_at

    protected $fillable = [
        'id_room',
        'id_item',
        'stock',
        'description',
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
