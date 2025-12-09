<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Room;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\ActionGroup;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Asset Manajemen';

    protected static ?string $pluralModelLabel = 'Asset Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('room_type_id')
                        ->label('Jenis Ruangan')
                        ->relationship(name: 'room_type', titleAttribute: 'name')
                        ->required(),

                    TextInput::make('name')
                    ->label('Nama Ruangan')
                        ->required()
                        ->rules([
                            'required', 'string', 'max:100',
                        ]),

                    TextInput::make('room_code')
                        ->label('Kode Ruangan')
                        ->required()
                        ->rules(fn ($record) => [
                            'required', 'string', 'max:10', Rule::unique('rooms', 'room_code')->ignore($record?->id),
                        ])
                        ->reactive(),

                    Textarea::make('description')
                        ->label('Deskripsi Ruangan')
                        ->autosize()
                        ->required()
                        ->rules([
                            'required', 'string', 'max:255',
                        ]),

                    TextInput::make('dimension')
                        ->label('Dimensi Ruangan')
                        ->required()
                        ->rules([
                            'required', 'string', 'max:100',
                        ]),

                    Select::make('option')
                        ->label('Opsi Ruangan')
                        ->options([
                            '1' => 'Umum',
                            '2' => 'Dosen',
                            '3' => 'Laboratorium',
                        ])
                        ->required(),

                    TextInput::make('utilization')
                        ->label('Utilisasi Ruangan')
                        ->required()
                        ->rules([
                            'required', 'string', 'max:100',
                        ]),

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

                ])->columns(2),

                Card::make([

                    CheckboxList::make('education_level_ids')
                        ->label('Penyebaran fungsi')
                        ->options([
                            '1' => 'Akuntansi',
                            '2' => 'Manajemen',
                            '3' => 'Akuntansi',
                            '4' => 'Manajemen Perpajakan',
                            '5' => 'Akuntansi',
                            '6' => 'Manajemen',
                            '7' => 'Ilmu Akuntansi',
                            '8' => 'Ilmu Manajemen',
                            '9' => 'Pendidikan Profesi Akuntan',
                            '10' => 'Cafe',
                            '11' => 'LSP',
                            '12' => 'Poliklinik',
                            '13' => 'Perpendiknas',
                        ])
                        ->columns(2)
                        ->required()
                        ->bulkToggleable(), // opsional

                ])->columns(1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No.')->alignCenter()->rowIndex(),
                TextColumn::make('room_code')->label('Kode Ruang')->alignCenter()->sortable(),
                TextColumn::make('room_type.name')->label('Jenis Ruang')->alignCenter()->searchable(),
                TextColumn::make('name')->label('Nama Ruang')->alignCenter()->searchable(),
                TextColumn::make('description')->label('Keterangan')->searchable(),
                TextColumn::make('dimension')->label('Dimensi')->alignCenter()->searchable(),
                TextColumn::make('utilization')->label('Utilisasi')->alignCenter()->searchable(),
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('media')
                    ->defaultImageUrl(asset('images/no-image.png'))
                    ->square()
                    ->height(50)
                    ->width(50)
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
                    ->relationship('room_type', 'name'),

            ], layout: FiltersLayout::AboveContent)
            ->actions([
                ActionGroup::make([
                Tables\Actions\EditAction::make()
                    ->label('Edit Ruangan')
                    ->tooltip('Edit Ruangan')
                    ->modalButton('Ubah Data')
                    ->modalHeading('Ubah Data Asset Manajemen')
                    ->successNotificationTitle('Data Asset Manajemen berhasil diubah'),
                Tables\Actions\Action::make('kelola_aset')
                    ->label('Kelola Aset')
                    ->tooltip('Kelola Asset')
                    ->icon('heroicon-o-rectangle-stack')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.resources.room-assets.index', ['room_id' => $record->id])),
                Tables\Actions\Action::make('kelola_image')
                    ->label('Kelola Gambar')
                    ->tooltip('Kelola Gambar')
                    ->icon('heroicon-o-photo')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.resources.room-images.index', ['room_id' => $record->id])),
                Tables\Actions\Action::make('print')
                    ->label('Cetak Asset')
                    ->tooltip('Cetak Asset')
                    ->icon('heroicon-o-printer')
                    ->color('warning')
                    ->url(fn ($record) => route('report.room_asset', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('print')
                    ->label('Cetak Gambar')
                    ->tooltip('Cetak Gambar')
                    ->icon('heroicon-o-printer')
                    ->color('warning')
                    ->url(fn ($record) => route('report.room_image', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus Ruangan')
                    ->tooltip('Hapus Ruangan')
                    ->modalButton('Hapus Data')
                    ->modalHeading('Hapus Data Inventory')
                    ->successNotificationTitle('Data inventory berhasil dihapus'),
                ])
                ->icon('heroicon-o-bars-3') 
                ->label('') 
                ->extraAttributes([
                    'class' => 'justify-start',
                ])
            ])
            ->headerActions([
                Tables\Actions\Action::make('cetak_prasarana')
                    ->label('Daftar Prasarana')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(route('report.facilities'))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('cetak_all_barang')
                    ->label('Ruangan Per Jenis')
                    ->icon('heroicon-o-printer')
                    ->color('danger')
                    ->url(route('report.room_all'))
                    ->openUrlInNewTab(),

                CreateAction::make()
                    ->label('Tambah Data')
                    ->icon('heroicon-o-plus')
                    ->createAnother(false)
                    ->modalButton('Simpan Data')
                    ->modalHeading('Tambah Data Asset Manajemen')
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('Batal'))
                    ->successNotificationTitle('Data inventory berhasil ditambahkan'),
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
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\RoomManagement::route('/'),
        ];
    }
}
