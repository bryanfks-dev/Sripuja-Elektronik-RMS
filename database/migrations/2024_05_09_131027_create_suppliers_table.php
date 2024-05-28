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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->integerIncrements('id')->primary();
            $table->string('nama_supplier')->index();
            $table->string('nama_cv')->index();
            $table->string('alamat')->index();
            $table->string('telepon')->nullable()->index();
            $table->string('no_hp', 13)->index();
            $table->string('fax')->nullable()->index();
            $table->string('nama_sales')->index();
            $table->string('no_hp_sales', 13)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
