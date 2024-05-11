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
            $table->integerIncrements('Id_Supplier')->primary();
            $table->string('Nama');
            $table->string('Alamat');
            $table->string('Telepon')->nullable();
            $table->string('No_Hp', 13);
            $table->string('Fax')->nullable();
            $table->string('Nama_Sales');
            $table->string('No_Hp_Sales', 13);

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
