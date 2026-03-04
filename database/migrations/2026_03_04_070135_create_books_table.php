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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('judul');
            $table->string('pengarang');
            $table->string('edisi')->nullable();
            $table->string('isbn_issn')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('tempat_terbit')->nullable();
            $table->string('deskripsi_fisik')->nullable();
            $table->string('bahasa')->nullable();
            $table->string('gambar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
