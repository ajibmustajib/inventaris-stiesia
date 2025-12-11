<?php

namespace App\Filament\Admin\Widgets;

use App\Models\RoomType;
use Filament\Widgets\ChartWidget;

class AssetChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Aset per Jenis Ruangan';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $roomTypes = RoomType::with([
            'rooms' => function ($q) {
                return $q->select('id', 'room_type_id')->withSum('room_assets', 'stock');
            },
        ])
            ->select('id', 'name')
            ->get();

        $labels = $roomTypes->pluck('name')->toArray();
        $data = $roomTypes->map(fn ($t) => $t->rooms->sum('room_assets_sum_stock'))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Aset',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
