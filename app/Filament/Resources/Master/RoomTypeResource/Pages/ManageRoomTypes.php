<?php

namespace App\Filament\Resources\Master\RoomTypeResource\Pages;

use App\Filament\Resources\Master\RoomTypeResource;
use Filament\Resources\Pages\ManageRecords;

class ManageRoomTypes extends ManageRecords
{
    protected static string $resource = RoomTypeResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl('index') => 'Data Master > Jenis Ruangan',
        ];
    }
}
