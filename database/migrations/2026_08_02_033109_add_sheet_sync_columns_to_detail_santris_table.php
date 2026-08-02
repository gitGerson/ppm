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
        Schema::table('detail_santris', function (Blueprint $table) {
            // Hash of the row payload as it stood at the last successful sync, so
            // the puller can tell "an admin edited the sheet" from "nothing changed".
            $table->string('sheet_hash', 64)->nullable()->after('is_ibu_alive');
            $table->timestamp('sheet_synced_at')->nullable()->after('sheet_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_santris', function (Blueprint $table) {
            $table->dropColumn(['sheet_hash', 'sheet_synced_at']);
        });
    }
};
