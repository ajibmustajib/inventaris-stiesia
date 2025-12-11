<?php

namespace App\Filament\Resources\RoomImageResource\Pages;

use App\Filament\Resources\RoomImageResource;
use App\Models\Room;
use App\Models\RoomImage;
use Filament\Resources\Pages\CreateRecord;

class CreateRoomImage extends CreateRecord
{
    protected static string $resource = RoomImageResource::class;

    public ?int $roomId = null;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        $this->roomId = (int) request()->query('room_id');
    }

    public function getTitle(): string
    {
        $roomName = Room::find($this->roomId)?->name ?? 'Nama Ruangan';

        return 'Tambah Gambar';
    }

    protected function handleRecordCreation(array $data): RoomImage
    {
        return RoomImage::create([
            'room_id' => $this->roomId,
            'image' => $data['image'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.room-images.index', [
            'room_id' => $this->roomId,
        ]);
    }

    public function getBreadcrumbs(): array
    {
        $roomName = Room::find($this->roomId)?->name ?? 'Nama Ruangan';

        return [
            route('filament.admin.resources.room-images.index', ['room_id' => $this->roomId]) => 'Asset Manajemen',
            '#' => 'Ruangan ('.$roomName.')', ''.'Tambah Data',
        ];
    }
}
