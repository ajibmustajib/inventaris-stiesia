<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomPrintController;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/storage/media/inventaris/{path}', function ($path) {

    if (auth()->user()) {
        return response()->file(storage_path('app/media/inventaris/'.$path));

        // dd($path);
    }

    abort(403);
})->where('path', '.*');

Route::get('/debug-inventory-modal', function () {
    foreach (RoomManagement::all() as $record) {
        try {
            $form = RoomResource::form(app(Form::class));
            $form->getSchema()[0]->getChildComponents(); // Trigger component build
            $form->fill($record->toArray());
        } catch (\Throwable $e) {
            dd("Error on ID: {$record->id}", $e->getMessage());
        }
    }

    return 'Semua data aman. Tidak ada error saat load form edit.';
});

Route::get('/aset/kelola/{id_room}', function ($id_room) {
    return redirect()->to(route('filament.admin.resources.room-assets.create').'?room_id='.$id_room);
})->name('aset.tambah');

Route::get('/aset/gambar/{id_room}', function ($id_room) {
    return redirect()->route('filament.admin.resources.room-images.create', ['room_id' => $id_room]);
})->name('image.tambah');

Route::get('/report/{room}/room_asset', [ReportController::class, 'room_asset'])->name('report.room_asset');

Route::get('/report/{room}/room_image', [ReportController::class, 'room_image'])->name('report.room_image');

//Route::get('/room-image/{room}/print_image', [ReportController::class, 'print_image'])->name('room-image.print_image');

Route::get('/room-type/{room}/print_room_type', [RoomPrintController::class, 'print_room_type'])->name('room-type.print_room_type');

Route::get('/report/facilities', [ReportController::class, 'facilities'])
    ->name('report.facilities');

Route::get('/report/room_all', [ReportController::class, 'room_all'])
    ->name('report.room_all');

Route::get('/report/item', [ReportController::class, 'item'])
    ->name('report.item');

Route::get('/report/item_all', [ReportController::class, 'item_all'])
    ->name('report.item_all');

