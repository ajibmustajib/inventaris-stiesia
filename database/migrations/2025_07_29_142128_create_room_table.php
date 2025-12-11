<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->string('room_code', 10);
            $table->foreignId('room_type_id')->references('id')->on('room_types')->onDelete('restrict');
            $table->string('name', 100)->nullable();
            $table->string('description', 100)->nullable();
            $table->string('dimension', 50)->nullable()->comment('dimensi ruangan');
            $table->json('education_level_ids')->nullable();
            $table->string('option', 2)->nullable()->comment('Room Options, U: Umum, D: Dosen, L: Laboratorium');
            $table->string('utilization', 255)->nullable();

            $table->timestamps();
        });

        DB::statement("ALTER TABLE rooms ADD CONSTRAINT check_room_option CHECK(option in ('D','U','L'))");

        Schema::create('room_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->references('id')->on('rooms')->onDelete('restrict');
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('restrict');
            $table->integer('stock')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_assets');
        Schema::dropIfExists('rooms');
    }
};
