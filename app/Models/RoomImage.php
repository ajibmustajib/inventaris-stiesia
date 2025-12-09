<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomImage extends Model
{
    protected $table = 'room_images';

    protected $fillable = [

        'room_id',
        'image',
        'description',

    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
}
