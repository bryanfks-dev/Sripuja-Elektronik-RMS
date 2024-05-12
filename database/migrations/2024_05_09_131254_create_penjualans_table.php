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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->integerIncrements('id_penjualan')->primary();
            $table->integer('id_user')->unsigned();
            $table->integer('id_pelanggan')->unsigned();
            $table->string('no_nota');
            $table->timestamp('tanggal_waktu')->default(now());

            $table->foreign('id_user')->references('id_user')
                ->on('users');
            $table->foreign('id_pelanggan')->references('id_pelanggan')
                ->on('pelanggans');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
