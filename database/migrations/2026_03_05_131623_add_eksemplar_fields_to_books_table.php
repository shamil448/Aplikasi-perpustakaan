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
        Schema::table('books', function (Blueprint $table) {
            $table->string('no_panggil')->nullable()->after('bahasa');
            $table->string('kode_inventaris')->nullable()->after('no_panggil');
            $table->string('lokasi')->nullable()->after('kode_inventaris');
            $table->string('lokasi_rak')->nullable()->after('lokasi');
            $table->integer('eksemplar')->default(0)->after('lokasi_rak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'no_panggil',
                'kode_inventaris',
                'lokasi',
                'lokasi_rak',
                'eksemplar'
            ]);
        });
    }
};
