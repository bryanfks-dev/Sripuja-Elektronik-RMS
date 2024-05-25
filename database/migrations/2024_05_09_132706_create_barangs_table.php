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
            $table->integerIncrements('id')->primary();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->integer('merek_barang_id')->unsigned();
            $table->integer('jenis_barang_id')->unsigned();
            $table->integer('jumlah_per_grosir')->default(0);

            $table->foreign('merek_barang_id')->references('id')
                ->on('merek_barangs')->onDelete('cascade');
            $table->foreign('jenis_barang_id')->references('id')
                ->on('jenis_barangs')->onDelete('cascade');
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
