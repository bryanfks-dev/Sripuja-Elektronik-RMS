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
            $table->integerIncrements('Id_Penjualan')->primary();
            $table->integer('Id_Karyawan')->unsigned()->nullable();
            $table->integer('Id_Pelanggan')->unsigned();
            $table->string('No_Nota');
            $table->timestamp('Tanggal_Waktu')->default(now());

            $table->foreign('Id_Karyawan')->references('Id_Karyawan')
                ->on('karyawans');
            $table->foreign('Id_Pelanggan')->references('Id_Pelanggan')
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
