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
        Schema::create('barangs', function (Blueprint $table) {
            $table->integerIncrements('Id_Barang')->primary();
            $table->string('Kode_Barang');
            $table->string('Nama_Barang');
            $table->integer('Stock');
            $table->integer('Harga_Jual');
            $table->integer('Harga_Beli');
            $table->integer('Harga_Grosir');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
