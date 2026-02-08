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
        Schema::table('catatan_kasus_siswas', function (Blueprint $table) {
            // Add tanggal column after siswas_id if it doesn't exist
            if (!Schema::hasColumn('catatan_kasus_siswas', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('siswas_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_kasus_siswas', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
};
