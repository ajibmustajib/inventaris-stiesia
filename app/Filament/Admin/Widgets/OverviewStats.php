<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make(
                'Total Jenis Barang',
                cache()->remember('total_jenis_barang', 60, fn () => \App\Models\ItemType::count())
            ),

            Stat::make(
                'Total Barang',
                cache()->remember('total_barang', 60, fn () => \App\Models\RoomAsset::sum('stock'))
            ),

            Stat::make(
                'Total Jenis Ruangan',
                cache()->remember('total_jenis_ruangan', 60, fn () => \App\Models\RoomType::count())
            ),

            Stat::make(
                'Total Ruangan',
                cache()->remember('total_ruangan', 60, fn () => \App\Models\Room::count())
            ),



        ];
    }

    
}
