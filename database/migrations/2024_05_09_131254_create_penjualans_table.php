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
            $table->integerIncrements('id')->primary();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('pelanggan_id')->unsigned();
            $table->string('no_nota');

            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->foreign('pelanggan_id')->references('id')
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
