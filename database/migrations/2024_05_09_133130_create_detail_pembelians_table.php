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
            $table->integer('id_barang')->unsigned()->index();
            $table->integer('id_pembelian')->unsigned()->index();
            $table->integer('jumlah')->unsigned();
            $table->integer('sub_total')->unsigned();

            $table->foreign('id_barang')->references('id_barang')
                ->on('barangs');
            $table->foreign('id_pembelian')->references('id_pembelian')
                ->on('pembelians')->onDelete('cascade');

            $table->timestamps();
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
