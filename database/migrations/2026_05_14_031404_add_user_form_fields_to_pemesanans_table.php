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
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();
            $table->string('nama_kos')->nullable()->after('nama');
            $table->string('seragam_ppm_size')->nullable()->after('payment_status');
            $table->string('baju_asad_size')->nullable()->after('seragam_ppm_size');
            $table->string('bukti_pembayaran_path')->nullable()->after('baju_asad_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'nama_kos',
                'seragam_ppm_size',
                'baju_asad_size',
                'bukti_pembayaran_path',
            ]);
        });
    }
};
