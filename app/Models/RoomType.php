<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_area',
        'ownership_status',
        'condition_status',
        'utilization',
    ];

    public function condition(): Attribute
    {
        return new Attribute(fn() => match($this->condition_status) {
            true => 'Terawat',
            false => 'Tidak Terawat',
            default => 'Tidak diketahui'
        });
    }

    public function ownership(): Attribute
    {
        return new Attribute(fn() => match($this->ownership_status) {
            true => 'Milik Sendiri',
            false => 'Sewa',
            default => 'Tidak diketahui'
        });
    }
}
