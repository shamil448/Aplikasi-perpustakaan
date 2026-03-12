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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            // mahasiswa yang meminjam
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // buku yang dipinjam
            $table->foreignId('book_id')->constrained()->onDelete('cascade');

            // kode eksemplar yang dipinjam
            $table->string('kode_eksemplar');

            // tanggal pinjam
            $table->date('tanggal_pinjam');

            // tanggal kembali
            $table->date('tanggal_kembali');

            // status buku
            $table->string('status')->default('dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
