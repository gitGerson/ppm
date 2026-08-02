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
            // When the WhatsApp registration confirmation was delivered. Null
            // means it still owes the santri that message.
            $table->timestamp('pendaftaran_notified_at')->nullable()->after('sheet_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_santris', function (Blueprint $table) {
            $table->dropColumn('pendaftaran_notified_at');
        });
    }
};
