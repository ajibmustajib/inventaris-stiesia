<?php

namespace App\Filament\Resources\Master;

use App\Filament\Resources\Master\ItemTypeResource\Pages;
use App\Models\ItemType;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class ItemTypeResource extends Resource
{
    protected static ?string $model = ItemType::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $slug = 'master/item-type';

    protected static ?string $navigationGroup = 'Master';

    protected static ?string $navigationLabel = 'Jenis Barang';

    protected static ?string $pluralModelLabel = 'Jenis Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('item_type_code')
                        ->label('Kode Jenis Barang')
                        ->required()
                        ->numeric()
                        ->maxLength(3)
                        ->rules(fn ($record) => [
                            'required', 'numeric', 'max:10', 'digits_between:1,3', Rule::unique('item_types', 'item_type_code')->ignore($record?->id),
                        ])
                        ->reactive(),

                    TextInput::make('name')
                        ->label('Nama Jenis Barang')
                        ->maxLength(255)
                        ->required()
                        ->rules([
                            'required', 'string', 'max:255',
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
                TextColumn::make('item_type_code')->alignment('center')->label('Kode Jenis Barang')->searchable(),
                TextColumn::make('name')->alignment('center')->label('Jenis Barang')->searchable(),
                TextColumn::make('aksi_header')->alignment('right')->label('Aksi'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalButton('Ubah Data')
                    ->modalHeading('Ubah Data Jenis Barang')
                    ->successNotificationTitle('Data jenis barang berhasil diubah'),
                Tables\Actions\DeleteAction::make()
                    ->modalButton('Hapus Data')
                    ->modalHeading('Hapus Data Jenis Barang')
                    ->successNotificationTitle('Data jenis barang berhasil dihapus'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Data')
                    ->icon('heroicon-o-plus')
                    ->createAnother(false)
                    ->modalButton('Simpan Data')
                    ->modalHeading('Tambah Data Jenis Barang')
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('Batal'))
                    ->successNotificationTitle('Data jenis barang berhasil ditambahkan'),
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
            'index' => Pages\ManageItemTypes::route('/'),
        ];
    }

}
