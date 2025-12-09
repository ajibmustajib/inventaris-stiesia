<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'room_code',
        'room_type_id',
        'name',
        'description',
        'dimension',
        'education_level_ids',
        'option',
        'utilization',
        'image',

    ];

    protected $casts = [
        'education_level_ids' => 'array',
    ];

    // public function room_assets(): HasMany
    // {
    //     return $this->hasMany(RoomAsset::class);
    // }

    // public function room_type(): BelongsTo
    // {
    //     return $this->belongsTo(RoomType::class);
    // }

    public function room_type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function room_assets()
    {
        return $this->hasMany(RoomAsset::class, 'room_id');
    }
}
