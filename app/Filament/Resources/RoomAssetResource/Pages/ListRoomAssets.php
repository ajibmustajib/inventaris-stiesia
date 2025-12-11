<?php

namespace App\Filament\Resources\RoomAssetResource\Pages;

use App\Filament\Resources\RoomAssetResource;
use App\Models\Room;
use App\Models\RoomAsset;
use App\Models\RoomType;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListRoomAssets extends ListRecords
{
    protected static string $resource = RoomAssetResource::class;

    public ?int $roomId = null;

    public string $roomName = 'Nama Ruangan';

    public function mount(): void
    {
        parent::mount();

        $this->roomId = (int) request()->query('room_id');
        $this->roomName = Room::find($this->roomId)?->name ?? 'Nama Ruangan';
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('Kembali')
                ->url(route('filament.admin.resources.rooms.index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),

            \Filament\Actions\CreateAction::make()
                ->label('Tambah Aset')
                ->url(fn () => route('filament.admin.resources.room-assets.create', ['room_id' => $this->roomId]))
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return RoomAsset::query()
            ->where('room_id', $this->roomId);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')->label('No.')->rowIndex(),
                Tables\Columns\TextColumn::make('item.name')->label('Barang')->searchable(),
                Tables\Columns\TextColumn::make('stock')->label('Stok'),
                Tables\Columns\TextColumn::make('description')->label('Keterangan')->searchable(),
                Tables\Columns\TextColumn::make('aksi_header')->alignment('right')->label('Aksi'),
            ])
            ->actions([
                Tables\Actions\Action::make('updateStock')
                    ->label('Update Stok')
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('Update Stok & Deskripsi')
                    ->form([
                        Forms\Components\Hidden::make('room_id_asal')
                            ->default(fn ($record) => $record->room_id)
                            ->dehydrated(),

                        Forms\Components\Placeholder::make('room_name')
                            ->label('Ruangan Asal')
                            ->content(fn ($record) => $record->room->name ?? '-'),

                        Forms\Components\TextInput::make('item')
                            ->label('Item')
                            ->default(fn ($record) => $record->item->name)
                            ->disabled(),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stock')
                            ->numeric()
                            ->minValue(0)
                            ->default(fn ($record) => $record->stock)
                            ->required(),

                        Forms\Components\TextInput::make('description')
                            ->label('Description')
                            ->default(fn ($record) => $record->description),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'stock' => $data['stock'],
                            'description' => $data['description'],
                        ]);
                    }),

                Tables\Actions\Action::make('pindahStok')
                    ->label('Pindah Stok')
                    ->icon('heroicon-o-arrow-right')
                    ->modalHeading('Pindahkan Stok ke Ruangan Lain')
                    ->form([
                        // room asal disuntikkan dari record & disubmit bersama modal
                        Forms\Components\Hidden::make('room_id_asal')
                            ->default(fn ($record) => $record->room_id)
                            ->dehydrated(),

                        Forms\Components\Placeholder::make('room_name')
                            ->label('Ruangan Asal')
                            ->content(fn ($record) => $record->room->name ?? '-'),

                        Forms\Components\TextInput::make('item')
                            ->label('Item')->default(fn ($record) => $record->item->name)->disabled(),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stock Saat Ini')
                            ->formatStateUsing(fn ($record) => $record->stock)->disabled(),

                        Forms\Components\TextInput::make('description')
                            ->label('Description')->default(fn ($record) => $record->description)->disabled(),

                        Forms\Components\Select::make('room_type_id')
                            ->label('Jenis Ruangan')
                            ->options(RoomType::pluck('name', 'id'))
                            ->reactive()->required(),

                        Forms\Components\Select::make('room_id')
                            ->label('Ruangan Tujuan')
                            ->options(function (callable $get) {

                                return Room::where('room_type_id', $get('room_type_id'))
                                    ->where('id', '!=', $this->roomId) // exclude ruangan ini
                                    ->pluck('name', 'id');
                            })
                            ->required(),

                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah Dipindah')->numeric()
                            ->minValue(1)->maxValue(fn ($record) => $record->stock)->required(),
                    ])
                    ->action(function ($record, array $data) {
                        // PAKAI room_id dari form (bukan request() & bukan breadcrumbs)
                        $roomAsal = (int) $data['room_id_asal'];
                        $roomTujuan = (int) $data['room_id'];
                        $jumlah = (int) $data['jumlah'];

                        DB::transaction(function () use ($record, $roomAsal, $roomTujuan, $jumlah) {
                            $originalAsset = RoomAsset::lockForUpdate()
                                ->where('room_id', $roomAsal)
                                ->where('item_id', $record->item_id)
                                ->first();

                            if (! $originalAsset || $originalAsset->stock < $jumlah) {
                                abort(422, 'Stok tidak cukup.');
                            }

                            // Kurangi stok asal
                            $originalAsset->decrement('stock', $jumlah);

                            // Tambah stok tujuan
                            RoomAsset::updateOrCreate(
                                ['room_id' => $roomTujuan, 'item_id' => $record->item_id],
                                ['stock' => DB::raw('COALESCE(stock,0) + '.$jumlah)]
                            );
                        });
                    }),
            ])
            ->bulkActions([]);
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.room-assets.index', ['room_id' => $this->roomId]) => 'Asset Manajemen',
            '#' => 'Ruangan ('.$this->roomName.')',
        ];
    }
}
