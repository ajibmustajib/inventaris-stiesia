<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetManagementResource\Pages;
use App\Filament\Resources\AssetManagementResource\RelationManagers;
use App\Models\AssetManagement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetManagementResource extends Resource
{
    protected static ?string $model = AssetManagement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('id_item')
                    ->label('Barang')
                    ->relationship(name: 'Item', titleAttribute: 'name')
                    ->required(),
                    TextInput::make('stock'),
                    TextInput::make('description'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No.')->rowIndex(),
                TextColumn::make('Item.name')->label('Barang'),
                TextColumn::make('stock')->label('Stock'),
                TextColumn::make('description')->label('Keterangan'),
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
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal'))
                    ->successNotificationTitle('Data jenis barang berhasil ditambahkan')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ManageAssetManagement::route('/'),
        ];
    }
}
