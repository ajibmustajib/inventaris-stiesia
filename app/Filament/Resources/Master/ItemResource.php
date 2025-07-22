<?php

namespace App\Filament\Resources\Master;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Master\ItemResource\Pages;
use App\Filament\Resources\Master\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $slug = 'master/item';

    protected static ?string $navigationGroup = 'Master';

    protected static ?string $navigationLabel = 'Barang';

    protected static ?string $pluralModelLabel = 'Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('id_item_type')
                    ->relationship(name: 'itemType', titleAttribute: 'name')
                    ->label('Jenis Barang')
                    ->required()
                    ->reactive(),

                    TextInput::make('item_code')
                        ->label('Kode Barang')
                        ->required()
                        ->rules(fn ($record) => [
                            'required', 'string', 'max:10', Rule::unique('items', 'item_code')->ignore($record?->id)
                        ])
                        ->reactive(),

                    TextInput::make('name')
                        ->label('Nama Barang')
                        ->maxLength(255)
                        ->required()
                        ->rules([
                            'required', 'string', 'max:255'
                        ])
                        ->reactive(),
                    
                    TextInput::make('distribution_amount')
                        ->label('Jumlah Distribusi')
                        ->readonly()
                        ->afterStateHydrated(function (TextInput $component, $state) {
                            if (is_null($state)) {
                                $component->state(0);
                            }
                        }),

                    FileUpload::make('image')
                        ->disk('media')
                        ->directory('inventaris/barang')
                        ->image()
                        ->visibility('public')

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No.')->rowIndex(),
                TextColumn::make('ItemType.name')->label('Jenis Barang'),
                TextColumn::make('item_code')->label('Kode Barang'),
                TextColumn::make('name')->label('Nama Barang'),
                TextColumn::make('distribution_amount')->label('Jumlah Distribusi'),
                ImageColumn::make('image')
                ->label('Gambar')
                ->getStateUsing(function ($record) {
                    return Storage::disk('media')->url('inventaris/barang/' . $record->image);
                })
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
                    ->modalHeading('Tambah Data Barang')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal'))
                    ->successNotificationTitle('Data barang berhasil ditambahkan')
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
            'index' => Pages\ManageItems::route('/'),
        ];
    }
}
