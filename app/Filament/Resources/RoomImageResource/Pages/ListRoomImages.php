<?php

namespace App\Filament\Resources\RoomImageResource\Pages;

use App\Filament\Resources\RoomImageResource;
use App\Models\Room;
use App\Models\RoomImage;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ListRoomImages extends ListRecords
{
    protected static string $resource = RoomImageResource::class;

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
            Actions\Action::make('back')
                ->label('Kembali')
                ->url(route('filament.admin.resources.rooms.index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),

            Actions\CreateAction::make()
                ->label('Tambah Gambar')
                ->url(fn () => route('filament.admin.resources.room-images.create', ['room_id' => $this->roomId]))
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return RoomImage::query()
            ->where('room_id', $this->roomId);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No.')
                    ->rowIndex(),

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

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->alignment('start')
                    ->searchable(),

                TextColumn::make('aksi_header')
                    ->alignment('right')
                    ->label('Aksi'),
            ])
            ->actions([
                // Aksi Edit Gambar
                Tables\Actions\Action::make('updateImage')
                    ->label('Edit Gambar')
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('Edit Gambar & Keterangan')
                    ->form([
                        Forms\Components\Placeholder::make('current_image')
                            ->label('Gambar Saat Ini')
                            ->content(function ($record) {
                                $imgUrl = $record->image
                                    ? asset('storage/media/'.$record->image)
                                    : asset('images/no-image.png');

                                return new \Illuminate\Support\HtmlString(
                                    '<img src="'.$imgUrl.'" style="max-width:280px;max-height:180px;border-radius:6px;" />'
                                );
                            })
                            ->extraAttributes(['class' => 'text-center'])
                            ->columnSpanFull()
                            ->disableLabel(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Upload Gambar (kosongkan jika tidak ingin ganti)')
                            ->disk('media')
                            ->directory('inventaris/room')
                            ->maxSize(2048)
                            ->rules([
                                'required',
                                'mimes:jpeg,jpg,png',
                                'max:2048',
                            ])
                            ->image()
                            ->helperText('Max File 2MB dengan format jpeg, jpg, png')
                            ->required()
                            ->visibility('public'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Ruangan')
                            ->autosize()
                            ->required()
                            ->rules([
                                'required', 'string', 'max:255',
                            ])
                            ->default(fn ($record) => $record->description),
                    ])
                    ->action(function ($record, array $data) {
                        $oldImage = $record->image;
                        $newImage = $data['image'] ?? null;

                        if ($newImage && $newImage !== $oldImage) {
                            if ($oldImage && Storage::disk('media')->exists($oldImage)) {
                                Storage::disk('media')->delete($oldImage);
                            }
                            $record->image = $newImage;
                        }

                        $record->description = $data['description'] ?? $record->description;
                        $record->save();
                    }),

                // Aksi Hapus
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Gambar')
                    ->modalSubheading('Apakah Anda yakin ingin menghapus gambar ini?')
                    ->modalButton('Ya, Hapus')
                    ->successNotificationTitle('Gambar berhasil dihapus')
                    ->before(function ($record) {
                        if ($record->image && Storage::disk('media')->exists($record->image)) {
                            Storage::disk('media')->delete($record->image);
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.room-images.index', ['room_id' => $this->roomId]) => 'Asset Manajemen',
            '#' => 'Ruangan ('.$this->roomName.')',
        ];
    }
}
