<?php

namespace App\Filament\Resources\Master\ItemTypeResource\Pages;

use App\Filament\Resources\Master\ItemTypeResource;
use Filament\Resources\Pages\ManageRecords;

class ManageItemTypes extends ManageRecords
{
    protected static string $resource = ItemTypeResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl('index') => 'Data Master > Jenis Barang',
        ];
    }
}
