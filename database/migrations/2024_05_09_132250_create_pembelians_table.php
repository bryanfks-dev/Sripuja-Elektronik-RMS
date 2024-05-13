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
            $table->integerIncrements('id')->primary();
            $table->integer('supplier_id')->unsigned();
            $table->string('no_nota');
            $table->timestamp('tanggal_waktu')->default(now());
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['Belum Lunas','Lunas'])
                ->default('Belum Lunas');

            $table->foreign('supplier_id')->references('id')
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
