<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryManagement extends Model
{

    use HasFactory;

    protected $primaryKey = 'id_room'; // ← Ganti ke nama kolom primary key kamu
    public $incrementing = false;       // Atau false jika kamu isi manual
    protected $keyType = 'int';        // 'string' jika pakai UUID

    protected $fillable = [

        'id_room',
        'room_type_id',
        'room_name',
        'room_description',
        'dimensions',
        'education_level_ids',
        'room_option',
        'lecturer_option',
        'lecturer_width',
        'lecturer_length',
        'utilization',

    ];

    protected $casts = [
    'education_level_ids' => 'array',
    ];

    public function RoomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'id');
    }
}
