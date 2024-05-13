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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->integerIncrements('id')->primary();
            $table->bigInteger('id_user')->unsigned();
            $table->string('nama_lengkap');
            $table->string('alamat');
            $table->string('no_hp', 13);
            $table->integer('gaji')->unsigned();
            $table->enum('tipe', ['Kasir','Non-Kasir'])
                ->default('Non-Kasir');

            $table->foreign('id_user')->references('id')
                ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
