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
            $table->integer('Id_Barang')->unsigned()->index();
            $table->integer('Id_Penjualan')->unsigned()->index();
            $table->integer('Jumlah')->unsigned();
            $table->integer('Harga_Jual')->unsigned();
            $table->integer('Sub_Total')->unsigned();

            $table->foreign('Id_Barang')->references('Id_Barang')
                ->on('barangs');
            $table->foreign('Id_Penjualan')->references('Id_Penjualan')
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
