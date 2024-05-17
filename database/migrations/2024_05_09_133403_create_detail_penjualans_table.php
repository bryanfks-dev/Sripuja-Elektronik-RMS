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
            $table->integer('barang_id')->nullable()->unsigned()->index();
            $table->integer('penjualan_id')->nullable()->unsigned()->index();
            $table->integer('jumlah')->unsigned();
            $table->integer('harga_jual')->unsigned();
            $table->integer('sub_total')->unsigned();

            $table->foreign('barang_id')->references('id')
                ->on('barangs')->onDelete('set null');
            $table->foreign('penjualan_id')->references('id')
                ->on('penjualans')->onDelete('set null');
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
