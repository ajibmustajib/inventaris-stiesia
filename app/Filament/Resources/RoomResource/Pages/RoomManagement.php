<?php

namespace App\Filament\Resources\RoomResource\Pages;

use App\Filament\Resources\RoomResource;
use Filament\Resources\Pages\ManageRecords;

class RoomManagement extends ManageRecords
{
    protected static string $resource = RoomResource::class;

    public function getTitle(): string
    {
        $roomId = request()->get('room_id');

        if ($roomId) {
            $room = Room::find($roomId);
            if ($room) {
                return 'Asset Management - Ruangan: '.$room->name; // Sesuaikan kolom nama ruangan
            }
        }

        return 'Asset Manajemen';
    }

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl('index') => 'Asset Manajemen',
        ];
    }
}
