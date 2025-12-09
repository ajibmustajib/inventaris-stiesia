<?php

namespace App\Filament\Resources\Master\ItemResource\Pages;

use App\Filament\Resources\Master\ItemResource;
use Filament\Resources\Pages\ManageRecords;

class ManageItems extends ManageRecords
{
    protected static string $resource = ItemResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl('index') => 'Data Master > Barang',
        ];
    }
}
