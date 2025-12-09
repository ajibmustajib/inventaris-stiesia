<?php

namespace App\Filament\Resources\Master;

use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Filament\Resources\Master\ItemResource\Pages;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

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
                            'required', 'string', 'max:10',
                        ])
                        ->reactive(),

                    TextInput::make('name')
                        ->label('Nama Barang')
                        ->maxLength(255)
                        ->required()
                        ->rules([
                            'required', 'string', 'max:255',
                        ])
                        ->reactive(),

                    TextInput::make('distribution_amount')
                        ->label('Jumlah Distribusi')
                        ->disabled()
                        ->afterStateHydrated(function (TextInput $component, $state) {
                            if (is_null($state)) {
                                $component->state(0);
                            }
                        }),

                    FileUpload::make('image')
                        ->label('Upload File')
                        ->disk('media')
                        ->directory('inventaris/barang')
                        ->maxSize(2048)
                        ->rules([
                        'required', 
                        'mimes:jpeg,jpg,png',
                        'max:2048'
                        ])
                        ->image()
                        ->helperText('Max File 2MB dengan format jpeg, jpg, png')
                        ->visibility('public'),

                ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
    return parent::getEloquentQuery()
        ->with('itemType')                      
        ->withSum('roomAssets as total_stock', 'stock'); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('no')->label('No.')->rowIndex(),
                TextColumn::make('ItemType.name')->label('Jenis Barang')->searchable(),
                TextColumn::make('item_code')->label('Kode Barang')->searchable(),
                TextColumn::make('name')->label('Nama Barang')->searchable(),

                TextColumn::make('total_stock')
                    ->label('Jumlah Distribusi')
                    ->color('primary')
                    ->weight('bold')
                    ->tooltip('Klik untuk melihat stok per ruangan')
                    ->extraAttributes(['class' => 'cursor-pointer'])
                    ->action(
                        Action::make('lihat_stok')
                            ->icon('heroicon-o-eye')
                            ->modalHeading(fn($record) => 'Detail Stok Barang : ' . ($record->name ?? '-'))
                            ->modalDescription('Rincian stok barang ini berdasarkan jenis dan ruangannya.')
                            ->modalWidth('4xl')
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                            ->modalContent(function ($record) {
                                $record->loadMissing('roomAssets.room.room_type');

                                $assets = $record->roomAssets;

                                if ($assets->isEmpty()) {
                                    return view('filament.components.empty-state', [
                                        'message' => 'Belum ada data stok di ruangan manapun.',
                                    ]);
                                }

                                return view('filament.components.room-asset-table', [
                                    'assets' => $assets,
                                ]);
                            })
                        ),

                ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('media')
                    ->defaultImageUrl(asset('images/no-image.png'))
                    ->square()
                    ->height(100)
                    ->width(100)
                    ->alignment('start')
                    ->url(fn ($record) => $record->image
                        ? asset('storage/media/'.$record->image)
                        : asset('images/no-image.png'))
                    ->openUrlInNewTab(),

                TextColumn::make('aksi_header')->alignment('right')->label('Aksi'),
                 
            ])
            ->filters([

            SelectFilter::make('item_type_id')
                    ->label('Jenis Barang')
                    ->relationship('ItemType', 'name'),

        ], layout: FiltersLayout::AboveContent)
            ->actions([
            Tables\Actions\EditAction::make()
                    ->modalButton('Ubah Data')
                    ->modalHeading('Ubah Data Barang')
                    ->successNotificationTitle('Data barang berhasil diubah'),
            Tables\Actions\DeleteAction::make()
                    ->modalButton('Hapus Data')
                    ->modalHeading('Hapus Data Barang')
                    ->successNotificationTitle('Data barang berhasil dihapus'),
        ])
            ->headerActions([

            Tables\Actions\Action::make('cetak_item')
                    ->label('Cetak Jumlah Barang')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(route('report.item_all'))
                    ->openUrlInNewTab(),

            CreateAction::make()
                    ->label('Tambah Data')
                    ->icon('heroicon-o-plus')
                    ->createAnother(false)
                    ->modalButton('Simpan Data')
                    ->modalHeading('Tambah Data Barang')
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('Batal'))
                    ->successNotificationTitle('Data barang berhasil ditambahkan'),
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
