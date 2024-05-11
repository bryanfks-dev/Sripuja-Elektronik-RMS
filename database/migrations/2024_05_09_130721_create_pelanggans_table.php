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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->integerIncrements('Id_Pelanggan')->primary();
            $table->string('Nama_Lengkap');
            $table->string('Alamat')->nullable();
            $table->string('Telepon')->nullable();
            $table->string('No_Hp', 13)->nullable();
            $table->string('Fax')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
