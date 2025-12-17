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

            if (! $id || $roomCode === '' || ! $roomTypeId) {
                continue;
            }

            $educationLevels = null;
            $rawEdu = $row['education_level_ids'] ?? null;

            if ($rawEdu) {
                if (is_string($rawEdu)) {
                    $decoded = json_decode($rawEdu, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $educationLevels = array_map('strval', $decoded);
                    }
                } elseif (is_array($rawEdu)) {
                    $educationLevels = array_map('strval', $rawEdu);
                }
            }


            $rawOption = trim((string) ($row['option'] ?? ''));

            $option = match (strtolower($rawOption)) {
                '01', '1', 'umum'           => '1',
                '02', '2', 'dosen'          => '2',
                '03', '3', 'laboratorium',
                'lab'                       => '3',
                default                     => null,
            };

            Room::updateOrCreate(
                ['id' => (int) $id], // ← ID dari Excel (AMAN)
                [
                    'room_code'           => $roomCode,
                    'room_type_id'        => (int) $roomTypeId,
                    'name'                => $name ?: null,
                    'description'         => isset($row['description'])
                        ? trim((string) $row['description'])
                        : null,
                    'dimension'           => isset($row['dimension'])
                        ? trim((string) $row['dimension'])
                        : null,
                    'education_level_ids' => $educationLevels
                        ? json_encode($educationLevels)
                        : null,
                    'option'              => $option, 
                    'utilization'         => isset($row['utilization'])
                        ? (string) $row['utilization']
                        : null,
                    'image'               => $row['image'] ?? null,
                ]
            );
        }
    }
}
