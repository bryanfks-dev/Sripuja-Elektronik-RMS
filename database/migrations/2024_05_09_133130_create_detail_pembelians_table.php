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
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->integer('barang_id')->unsigned()->index();
            $table->integer('pembelian_id')->unsigned()->index();
            $table->integer('jumlah')->unsigned();
            $table->integer('sub_total')->unsigned();

            $table->foreign('barang_id')->references('id')
                ->on('barangs');
            $table->foreign('pembelian_id')->references('id')
                ->on('pembelians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__pembelians');
    }
};
