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
        Schema::create('labakas', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->string('produk');
            $table->string('beli');
            $table->string('jual');
            $table->string('debet');
            $table->string('credit');
            $table->string('ket');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labakas');
    }
};
