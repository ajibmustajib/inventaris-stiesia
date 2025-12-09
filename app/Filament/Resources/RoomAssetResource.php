<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomAssetResource\Pages;
use App\Models\Item;
use App\Models\RoomAsset;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RoomAssetResource extends Resource
{
    protected static ?string $model = RoomAsset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationLabel = 'Kelola Asset Ruangan';

    protected static ?string $pluralModelLabel = 'Kelola Asset Ruangan';

    public static function shouldRegisterNavigation(): bool
    {
        return false; // supaya tidak muncul di menu
    }

    public static function getEloquentQuery(): Builder
    {
        $roomId = request()->get('room_id');

        return parent::getEloquentQuery()
            ->select(
                DB::raw('MIN(id) as id'), // kunci unik untuk Filament
                'item_id',
                DB::raw('SUM(stock) as stock'),
                DB::raw('MAX(description) as description'),
                DB::raw('MAX(room_id) as room_id')
            )
            ->when($roomId, fn ($q) => $q->where('room_id', $roomId))
            ->groupBy('item_id')
            ->with('item')
            ->orderBy('item_id', 'asc'); // urutkan biar aman di PostgreSQL
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                Hidden::make('room_id')
                    ->default(fn () => request()->get('room_id'))
                    ->required()
                    ->dehydrated(),

                TextInput::make('room.name')
                    ->label('Ruangan')
                    ->default(function () {
                        $roomId = request()->get('room_id');

                        return \App\Models\Room::find($roomId)?->name;
                    })
                    ->disabled(),

                Repeater::make('assets')
                    ->label('Tambah Aset')
                    ->schema([

                        Select::make('item_id')
                            ->label('Barang')
                            ->options(function () {
                                $roomId = request()->get('room_id');
                                $existingItemIds = RoomAsset::where('room_id', $roomId)
                                    ->pluck('item_id')
                                    ->toArray();

                                return Item::whereNotIn('id', $existingItemIds)
                                    ->pluck('name', 'id');
                            })
                            ->required(),

                        TextInput::make('stock')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->rules([
                            'required',
                             ]),

                        Textarea::make('description')
                        ->label('Deskripsi Ruangan')
                        ->autosize()
                        ->required()
                        ->rules([
                            'required', 'string', 'max:255',
                        ]),

                    ])->defaultItems(1)
                    ->minItems(1)
                    ->columns(3)
                    ->reorderable(false),

            ])->columns(2),

        ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Simpan manual ke tabel room_assets
        foreach ($data['assets'] as $asset) {
            RoomAsset::create([
                'room_id' => $data['room_id'],
                'item_id' => $asset['item_id'],
                'stock' => $asset['stock'],
                'description' => $asset['description'],
            ]);
        }

        // Jangan insert data utama (karena hanya repeaters yang disimpan)
        return [];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomAssets::route('/'),
            'create' => Pages\CreateRoomAsset::route('/create'),
            'edit' => Pages\EditRoomAsset::route('/{record}/edit'),
        ];
    }
}
