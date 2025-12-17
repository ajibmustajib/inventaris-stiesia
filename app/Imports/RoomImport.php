<?php

namespace App\Imports;

use App\Models\Room;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RoomImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $id         = $row['id'] ?? null;
            $roomCode   = trim((string) ($row['room_code'] ?? ''));
            $roomTypeId = $row['room_type_id'] ?? null;
            $name       = trim((string) ($row['name'] ?? ''));


            if (! $id || $roomCode === '' || ! $roomTypeId || $name === '') {
                continue;
            }

            $educationLevelsRaw = $row['education_level_ids'] ?? null;
            $educationLevels = null;

            if ($educationLevelsRaw) {
                if (is_string($educationLevelsRaw)) {
                    $decoded = json_decode($educationLevelsRaw, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $educationLevels = array_map('strval', $decoded);
                    }
                } elseif (is_array($educationLevelsRaw)) {
                    $educationLevels = array_map('strval', $educationLevelsRaw);
                }
            }

            Room::updateOrCreate(
                ['id' => (int) $id], // id dari excel
                [
                    'room_code'           => $roomCode,
                    'room_type_id'        => $roomTypeId,
                    'name'                => $name,
                    'description'         => $row['description'] ?? null,
                    'dimension'           => $row['dimension'] ?? null,
                    'education_level_ids' => $educationLevels
                        ? json_encode($educationLevels)
                        : null,
                    'option'              => $row['option'] ?? null,
                    'utilization'         => $row['utilization'] ?? 0,
                    'image'               => $row['image'] ?? null,
                ]
            );
        }
    }
}
