<?php

use Illuminate\Support\Facades\Route;
use App\Models\InventoryManagement;
use App\Filament\Resources\InventoryManagementResource;
use Filament\Forms\Form;
use App\Filament\Resources\AssetManagementResource;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/storage/media/inventaris/{path}', function($path) {

    if(auth()->user()) {
        return response()->file(storage_path('app/media/inventaris/' . $path));

        // dd($path);
    }

    abort(403);
})->where('path', '.*');

Route::get('/debug-inventory-modal', function () {
    foreach (InventoryManagement::all() as $record) {
        try {
            $form = InventoryManagementResource::form(app(Form::class));
            $form->getSchema()[0]->getChildComponents(); // Trigger component build
            $form->fill($record->toArray());
        } catch (\Throwable $e) {
            dd("Error on ID: {$record->id}", $e->getMessage());
        }
    }

    return 'Semua data aman. Tidak ada error saat load form edit.';
});

Route::get('/aset/kelola/{id_room}', function ($id_room) {
    return redirect()->to(route('filament.admin.resources.asset-managements.index') . '?room_id=' . $id_room);
})->name('aset.tambah');
