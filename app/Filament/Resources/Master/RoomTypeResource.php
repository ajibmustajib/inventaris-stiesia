<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Master\RoomTypeResource\Pages;
use App\Models\RoomType;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RoomTypeResource extends Resource
{
    protected static ?string $model = RoomType::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $slug = 'master/room-type';

    protected static ?string $navigationGroup = 'Master';

    protected static ?string $navigationLabel = 'Jenis Ruangan';

    protected static ?string $pluralModelLabel = 'Jenis Ruangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->label('Nama Jenis Ruangan')
                        ->required()
                        ->rules([
                            'required', 'string', 'max:50',
                        ])
                        ->reactive(),

                    TextInput::make('total_area')
                        ->label('Total Area (m2)')
                        ->numeric()
                        ->minLength(1)
                        ->maxLength(3)
                        ->required()
                        ->rules([
                            'required', 'numeric', 'digits_between:1,3',
                        ])
                        ->reactive(),

                    Radio::make('ownership_status')
                        ->options([
                            true => 'SD',
                            false => 'SW',
                        ])
                        ->inline()
                        ->default(false)
                        ->required()
                        ->rules([
                            'required', 'boolean',
                        ])
                        ->label('Kepemilikan'),

                    Radio::make('condition_status')
                        ->options([
                            true => 'Terawat',
                            false => 'Tidak Terawat',
                        ])
                        ->inline()
                        ->default(true)
                        ->rules([
                            'required', 'boolean',
                        ])
                        ->required()
                        ->label('Kondisi'),

                    TextInput::make('utilization')
                        ->label('Utilisasi (Jam/Minggu)')
                        ->numeric()
                        ->minLength(1)
                        ->maxLength(3)
                        ->required()
                        ->rules([
                            'required', 'numeric', 'digits_between:1,3',
                        ])
                        ->reactive(),

                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No.')->rowIndex(),
                TextColumn::make('name')->label('Jenis Ruangan')->searchable(),
                TextColumn::make('ownership')->alignment('center')->label('Kepemilikan'),
                TextColumn::make('condition')->alignment('center')->label('Kondisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Terawat' => 'success',
                        'Tidak Terawat' => 'warning',
                    })->alignment('center'),
                TextColumn::make('utilization')->alignment('center')->label('Utilisasi (Jam/Minggu)'),
                TextColumn::make('aksi_header')->alignment('right')->label('Aksi'),
            ])
            ->filters([
                SelectFilter::make('condition_status')
                    ->label('Kondisi')
                    ->options([
                        true => 'Terawat',
                        false => 'Tidak Terawat',
                    ]),

                SelectFilter::make('ownership_status')
                    ->label('Kepemilikan')
                    ->options([
                        true => 'Milik Sendiri',
                        false => 'Sewa',
                    ]),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                 Tables\Actions\EditAction::make()
                    ->modalButton('Ubah Data')
                    ->modalHeading('Ubah Data Jenis Ruangan')
                    ->successNotificationTitle('Data jenis ruangan berhasil diubah'),
                Tables\Actions\DeleteAction::make()
                    ->modalButton('Hapus Data')
                    ->modalHeading('Hapus Data Jenis Ruangan')
                    ->successNotificationTitle('Data jenis ruangan berhasil dihapus'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Data')
                    ->icon('heroicon-o-plus')
                    ->createAnother(false)
                    ->modalButton('Simpan Data')
                    ->modalHeading('Tambah Data Jenis Ruangan')
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('Batal'))
                    ->successNotificationTitle('Data Jenis Ruangan berhasil ditambahkan'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoomTypes::route('/'),
        ];
    }

}
