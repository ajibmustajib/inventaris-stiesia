<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'id_item_type',
        'name',
        'distribution_amount',
        'image',
    ];

    public function getDistributionAmountAttribute()
    {
        return $this->roomAssets()->sum('stock');
    }

    public function roomAssets(): HasMany
    {
        return $this->hasMany(RoomAsset::class, 'item_id', 'id');
    }

    /**
     * Relasi ke JenisBarang
     * Barang milik satu JenisBarang
     */
    public function itemType()
    {
        return $this->belongsTo(ItemType::class, 'id_item_type', 'id');
    }
}
