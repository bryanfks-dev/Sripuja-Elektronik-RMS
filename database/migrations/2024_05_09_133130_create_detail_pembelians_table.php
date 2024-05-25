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
            $table->id();
            $table->integer('detail_barang_id')->unsigned()->nullable()->index();
            $table->integer('pembelian_id')->unsigned()->index();
            $table->integer('jumlah');
            $table->integer('sub_total')->unsigned();

            $table->foreign('detail_barang_id')->references('id')
                ->on('detail_barangs')->onDelete('set null');
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
