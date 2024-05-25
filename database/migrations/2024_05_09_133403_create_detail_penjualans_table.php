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
            $table->id();
            $table->integer('barang_id')->unsigned()->nullable()->index();
            $table->integer('penjualan_id')->unsigned()->nullable()->index();
            $table->json('jumlah');
            $table->integer('harga_jual')->unsigned();
            $table->integer('sub_total')->unsigned();

            $table->foreign('barang_id')->references('id')
                ->on('barangs')->onDelete('set null');
            $table->foreign('penjualan_id')->references('id')
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
