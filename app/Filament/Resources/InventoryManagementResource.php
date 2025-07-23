<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\StaticAction;
use App\Models\InventoryManagement;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InventoryManagementResource\Pages;
use App\Filament\Resources\InventoryManagementResource\RelationManagers;

class InventoryManagementResource extends Resource
{
    protected static ?string $model = InventoryManagement::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('room_type_id')
                    ->label('Room Type')
                    ->relationship(name: 'RoomType', titleAttribute: 'name')
                    ->required(),
                    TextInput::make('room_name')->required(),

                    TextInput::make('id_room')
                            ->label('Kode Ruangan')
                            ->required()
                            ->rules(fn ($record) => [
                                'required', 'string', 'max:10', Rule::unique('inventory_management', 'id_room')->ignore($record?->id_room, 'id_room')
                            ])
                            ->reactive(),

                    TextInput::make('room_description'),

                    TextInput::make('dimension'),

                    Select::make('room_option')
                    ->label('Room Option')
                    ->options([
                        '0' => 'Umum',
                        '1' => 'Dosen',
                        '2' => 'Laboratorium',
                    ])
                    ->required(),
                    TextInput::make('lecturer_option')->numeric()->hidden(true),
                    TextInput::make('lecturer_width')->numeric()->hidden(true),
                    TextInput::make('lecturer_length')->numeric()->hidden(true),
                    TextInput::make('utilization'),

                    

                ])->columns(2),

                Card::make([

                    CheckboxList::make('education_level_ids')
                    ->label('Education Levels')
                    ->options([
                        '01' => 'Akuntansi',
                        '02' => 'Manajemen',
                        '03' => 'Akuntansi',
                        '04' => 'Manajemen Perpajakan',
                        '05' => 'Akuntansi',
                        '06' => 'Manajemen',
                        '07' => 'Ilmu Akuntansi',
                        '08' => 'Ilmu Manajemen',
                        '09' => 'Pendidikan Profesi Akuntan',
                        '10' => 'Cafe',
                        '11' => 'LSP',
                        '12' => 'Poliklinik',
                        '13' => 'Perpendiknas',
                    ])
                    ->columns(2)
                    ->required()
                    ->bulkToggleable(), // opsional

                ])->columns(1)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No.')->rowIndex(),
                TextColumn::make('id_room')->sortable(),
                TextColumn::make('room_name')->searchable(),
                TextColumn::make('room_description'),
                TextColumn::make('dimension'),
                TextColumn::make('utilization'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalButton('Ubah Data')
                ->modalHeading('Ubah Data Inventory')
                ->successNotificationTitle('Data Inventory berhasil diubah'),
                Tables\Actions\Action::make('kelola')
                ->label('Kelola Aset')
                ->icon('heroicon-o-plus-circle')
                ->url(fn ($record) => route('aset.tambah', ['id_room' => $record->id_room]))
                ->openUrlInNewTab(),
                Tables\Actions\Action::make('print')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('secondary')
                ->action(function ($record) {
                    $pdf = Pdf::loadView('pdf.inventory', ['record' => $record]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'inventory-' . $record->id_room . '.pdf'
                        
                    );
                }),
                Tables\Actions\DeleteAction::make()
                ->modalButton('Hapus Data')
                ->modalHeading('Hapus Data Inventory')
                ->successNotificationTitle('Data inventory berhasil dihapus'),
                   
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Data')
                    ->icon('heroicon-o-plus')
                    ->createAnother(false)
                    ->modalButton('Simpan Data')
                    ->modalHeading('Tambah Data Inventory')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal'))
                    ->successNotificationTitle('Data inventory berhasil ditambahkan')
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
            'index' => Pages\ManageInventoryManagement::route('/'), 
        ];
    }
}
