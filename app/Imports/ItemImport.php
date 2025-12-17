<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $itemCode = trim((string) ($row['item_code'] ?? ''));
            $itemTypeCode = trim((string) ($row['item_type_code'] ?? ''));
            $name = trim((string) ($row['name'] ?? ''));
            $image = $row['image'] ?? null;

            if ($itemTypeCode === '' || $name === '') {
                continue;
            }

            $itemType = ItemType::where('item_type_code', $itemTypeCode)->first();

            if (! $itemType) {
                continue;
            }

            Item::updateOrCreate(
                ['item_code' => $itemCode],
                [
                    'id_item_type' => $itemType->id,
                    'name'         => $name,
                    'image'        => $image,
                ]
            );
        }
    }
}
