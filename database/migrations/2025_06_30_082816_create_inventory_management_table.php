<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id(); // ini akan jadi primary key otomatis
            $table->string('name', 50);
            $table->integer('total_area')->nullable();
            $table->boolean('ownership_status')->default(false);
            $table->boolean('condition_status')->default(false);
            $table->integer('utilization')->nullable(); 
            $table->timestamps();
        });

        Schema::create('inventory_management', function (Blueprint $table) {
            $table->string('id_room')->unique();
            $table->foreignId('room_type_id')->references('id')->on('room_types')->onDelete('restrict');
            $table->string('room_name', 100)->nullable();
            $table->string('room_description', 100)->nullable();
            $table->string('dimension', 50)->nullable()->comment('dimensi ruangan');
            $table->json('education_level_ids')->nullable(); 
            $table->integer('room_option')->nullable();
            $table->integer('lecturer_option')->nullable();
            $table->decimal('lecturer_width', 11, 2)->nullable();
            $table->decimal('lecturer_length', 11, 2)->nullable();
            $table->string('utilization', 255)->nullable();

            $table->timestamps();
        });

        Schema::create('inv_management_det', function (Blueprint $table) {
            $table->string('id_room'); // hanya SEKALI
            $table->foreign('id_room')->references('id_room')->on('inventory_management')->onDelete('restrict');

            $table->foreignId('id_item')->constrained('items')->onDelete('restrict');
            $table->integer('stock')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   
        Schema::dropIfExists('inv_management_det');
        Schema::dropIfExists('inventory_management');
        Schema::dropIfExists('room_types');
    }
};
