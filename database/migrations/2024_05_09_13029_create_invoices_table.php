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
        Schema::create('invoices', function (Blueprint $table) {
            $table->integerIncrements('Id_Invoice')->primary();
            $table->integer('Id_Penjualan')->unsigned();
            $table->string('No_Invoice');

            $table->foreign('Id_Penjualan')->references('Id_Penjualan')
                ->on('penjualans')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
