<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('anggota') && !Schema::hasColumn('anggota', 'uid_rfid')) {
            Schema::table('anggota', function (Blueprint $table) {
                $table->string('uid_rfid', 50)->nullable()->unique()->after('id_rfid');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('anggota') && Schema::hasColumn('anggota', 'uid_rfid')) {
            Schema::table('anggota', function (Blueprint $table) {
                $table->dropColumn('uid_rfid');
            });
        }
    }
};
