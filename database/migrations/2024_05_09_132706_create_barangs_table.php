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
            $table->integerIncrements('id_barang')->primary();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->integer('stock')->unsigned();
            $table->integer('harga_jual')->unsigned();
            $table->integer('harga_beli')->unsigned();
            $table->integer('harga_grosir')->unsigned();

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
