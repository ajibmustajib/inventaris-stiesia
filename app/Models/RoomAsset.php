<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAsset extends Model
{
    protected $table = 'room_assets';

    protected $fillable = [

        'room_id',
        'item_id',
        'stock',
        'description',

    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
