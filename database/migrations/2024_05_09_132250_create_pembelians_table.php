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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->integerIncrements('Id_Pembelian')->primary();
            $table->integer('Id_Supplier')->unsigned();
            $table->string('No_Nota');
            $table->timestamp('Tanggal_Waktu')->default(now());
            $table->date('Tanggal_Jatuh_Tempo');
            $table->enum('Status', ['Belum Lunas','Lunas'])
                ->default('Belum Lunas');

            $table->foreign('Id_Supplier')->references('Id_Supplier')
                ->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
