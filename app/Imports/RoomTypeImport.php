<?php

namespace App\Imports;

use App\Models\RoomType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RoomTypeImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (empty($row['name'])) {
                continue;
            }

            RoomType::updateOrCreate(
                [
                    'name' => trim($row['name']), 
                ],
                [
                    'total_area'       => (int) ($row['total_area'] ?? 0),
                    'ownership_status' => $this->toBoolean($row['ownership_status']),
                    'condition_status' => $this->toBoolean($row['condition_status']),
                    'utilization'      => (int) ($row['utilization'] ?? 0),
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.name'             => ['required', 'string'],
            '*.total_area'       => ['nullable', 'integer', 'min:0'],
            '*.ownership_status' => ['required'],
            '*.condition_status' => ['required'],
            '*.utilization'      => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function toBoolean($value): bool
    {
        return in_array(
            strtolower((string) $value),
            ['1', 'true', 'ya', 'yes', 'y'],
            true
        );
    }
}
