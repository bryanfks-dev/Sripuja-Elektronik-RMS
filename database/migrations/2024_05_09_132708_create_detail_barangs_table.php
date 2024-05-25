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
        Schema::create('detail_barangs', function (Blueprint $table) {
            $table->integerIncrements('id')->primary();
            $table->integer('barang_id')->unsigned();
            $table->integer('stock')->unsigned();
            $table->integer('harga_jual')->unsigned();
            $table->integer('harga_beli')->unsigned();
            $table->integer('harga_grosir')->unsigned()->default(0);

            $table->foreign('barang_id')->references('id')
                ->on('barangs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_barangs');
    }
};
