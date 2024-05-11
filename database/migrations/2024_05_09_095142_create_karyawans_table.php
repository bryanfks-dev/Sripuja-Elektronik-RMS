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
            $table->integerIncrements('Id_Karyawan')->primary();
            $table->string('Username')->unique();
            $table->string('Password');
            $table->string('Nama_Lengkap');
            $table->string('Alamat');
            $table->string('No_Hp', 13);
            $table->integer('Gaji')->unsigned();
            $table->enum('Tipe_Karyawan', ['Kasir','Non-Kasir'])->default('Non-Kasir');

            $table->timestamps();
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
