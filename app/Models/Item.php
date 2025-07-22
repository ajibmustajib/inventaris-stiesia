<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $attributes = [
    'distribution_amount' => 0,
    ];

    /**
     * Relasi ke JenisBarang
     * Barang milik satu JenisBarang
     */
    public function itemType()
    {
        return $this->belongsTo(ItemType::class, 'id_item_type', 'id');
    }
}
