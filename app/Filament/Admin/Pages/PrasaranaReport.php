<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;

class PrasaranaReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationLabel = 'Laporan Prasarana';

    protected static ?string $slug = 'laporan-prasarana';

    protected static string $view = 'filament.pages.prasarana-report';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetak')
                ->label('Cetak Daftar Prasarana')
                ->icon('heroicon-o-printer')
                ->color('secondary')
                ->url(route('report.prasarana'))
                ->openUrlInNewTab(),
        ];
    }
}
