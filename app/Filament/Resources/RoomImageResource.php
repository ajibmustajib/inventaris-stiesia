<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomImageResource\Pages;
use App\Models\Room;
use App\Models\RoomImage;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class RoomImageResource extends Resource
{
    protected static ?string $model = RoomImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kelola Gambar Ruangan';

    protected static ?string $pluralModelLabel = 'Kelola Gambar Ruangan';

    public static function shouldRegisterNavigation(): bool
    {
        return false; // supaya tidak muncul di menu
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
                    ->default(function ($record) {
                        $roomId = request()->get('room_id');

                        return Room::find($roomId)?->name;
                    })
                    ->disabled(),

                FileUpload::make('image')
                    ->label('Upload File')
                    ->disk('media')
                    ->directory('inventaris/room')
                    ->maxSize(2048)
                    ->rules([
                        'required', 
                        'mimes:jpeg,jpg,png',
                        'max:2048'
                    ])
                    ->image()
                    ->helperText('Max File 2MB dengan format jpeg, jpg, png')
                    ->visibility('public'),

                Textarea::make('description')
                        ->label('Deskripsi Ruangan')
                        ->autosize()
                        ->required()
                        ->rules([
                            'required', 'string', 'max:255',
                        ]),

            ])->columns(1),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomImages::route('/'),
            'create' => Pages\CreateRoomImage::route('/create'),
            'edit' => Pages\EditRoomImage::route('/{record}/edit'),
        ];
    }
}
