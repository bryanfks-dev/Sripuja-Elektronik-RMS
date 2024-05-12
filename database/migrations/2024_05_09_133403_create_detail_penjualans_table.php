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
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->integer('id_barang')->unsigned()->index();
            $table->integer('id_penjualan')->unsigned()->index();
            $table->integer('jumlah')->unsigned();
            $table->integer('harga_jual')->unsigned();
            $table->integer('sub_total')->unsigned();

            $table->foreign('id_barang')->references('id_barang')
                ->on('barangs');
            $table->foreign('id_penjualan')->references('id_penjualan')
                ->on('penjualans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__penjualans');
    }
};
