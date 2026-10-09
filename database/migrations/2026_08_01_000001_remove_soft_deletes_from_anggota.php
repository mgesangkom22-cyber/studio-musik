<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('anggota')) {
            // Permanently delete old soft-deleted records if any exist with FK check temporarily disabled
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('anggota')->whereNotNull('deleted_at')->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Drop deleted_at column if present
            if (Schema::hasColumn('anggota', 'deleted_at')) {
                Schema::table('anggota', function (Blueprint $table) {
                    $table->dropColumn('deleted_at');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('anggota')) {
            if (!Schema::hasColumn('anggota', 'deleted_at')) {
                Schema::table('anggota', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }
};
