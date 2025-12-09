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
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // ID auto-increment (opsional)

            $table->string('item_code')->unique(); // kode barang, misal: B001
            $table->foreignId('id_item_type')->references('id')->on('item_types')->onDelete('cascade');
            $table->string('name');
            $table->integer('distribution_amount')->default(0)->comment('jumlah distribusi');
            $table->string('image')->nullable(); // path ke file gambar

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
