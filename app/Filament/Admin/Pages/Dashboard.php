<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Method ini harus PUBLIC, tapi TIDAK STATIC
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\OverviewStats::class,
            \App\Filament\Admin\Widgets\AssetChart::class,
        ];
    }
}
