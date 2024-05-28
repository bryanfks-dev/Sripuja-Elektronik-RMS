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
            $table->integerIncrements('id')->primary();
            $table->string('nama_lengkap')->index();
            $table->string('alamat')->nullable()->index();
            $table->string('telepon')->nullable()->index();
            $table->string('no_hp', 13)->nullable()->index();
            $table->string('fax')->nullable()->index();

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
