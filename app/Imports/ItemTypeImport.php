<?php

namespace App\Imports;

use App\Models\ItemType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemTypeImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (
                empty($row['item_type_code']) ||
                empty($row['name'])
            ) {
                continue;
            }

            ItemType::updateOrCreate(
                [
                    'item_type_code' => trim($row['item_type_code']),
                ],
                [
                    'name' => trim($row['name']),
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.item_type_code' => 'required|string|max:20',
            '*.name'           => 'required|string|max:100',
        ];
    }
}
