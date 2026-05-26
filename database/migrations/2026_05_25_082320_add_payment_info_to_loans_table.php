<!-- <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'denda_dibayar')) {
                $table->integer('denda_dibayar')->default(0)->after('denda');
            }

            if (!Schema::hasColumn('loans', 'tanggal_bayar')) {
                $table->dateTime('tanggal_bayar')->nullable()->after('denda_dibayar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'tanggal_bayar')) {
                $table->dropColumn('tanggal_bayar');
            }

            if (Schema::hasColumn('loans', 'denda_dibayar')) {
                $table->dropColumn('denda_dibayar');
            }
        });
    }
};
