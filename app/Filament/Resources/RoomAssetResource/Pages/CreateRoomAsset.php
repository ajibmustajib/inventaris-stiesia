<?php

namespace App\Filament\Resources\RoomAssetResource\Pages;

use App\Filament\Resources\RoomAssetResource;
use App\Models\Room;
use App\Models\RoomAsset;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRoomAsset extends CreateRecord
{
    protected static string $resource = RoomAssetResource::class;

     public ?int $roomId = null;

    public string $roomName = 'Nama Ruangan';

    protected $room;

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
        $this->roomName = Room::find($this->roomId)?->name ?? 'Nama Ruangan';
    }

    public function getTitle(): string
    {
        return 'Tambah Asset';
    }

    protected function handleRecordCreation(array $data): Model
    {
        foreach ($data['assets'] as $asset) {
            RoomAsset::create([
                'room_id' => $data['room_id'],
                'item_id' => $asset['item_id'],
                'stock' => $asset['stock'],
                'description' => $asset['description'],
            ]);
        }

        return new RoomAsset;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

   public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.room-assets.index', ['room_id' => $this->roomId]) => 'Asset Manajemen',
            '#' => 'Ruangan (' . $this->roomName . ')', '' . 'Tambah Data',
        ];
    }
   
}
