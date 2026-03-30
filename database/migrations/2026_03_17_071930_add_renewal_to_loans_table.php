<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {

            // apakah sudah pernah diperpanjang
            $table->boolean('is_extended')->default(false)->after('status');

            // jumlah denda
            $table->integer('denda')->default(0)->after('is_extended');
        });
    }


    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {

            $table->dropColumn(['is_extended', 'denda']);
        });
    }
};
