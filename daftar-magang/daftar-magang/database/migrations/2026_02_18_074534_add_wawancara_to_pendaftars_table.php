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
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dateTime('wawancara_waktu')->nullable();  // Tgl & Jam Wawancara
            $table->string('wawancara_lokasi')->nullable();   // Link Zoom / Ruangan
            $table->text('pesan')->nullable();                // Pesan Admin ke Mahasiswa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Menghapus kolom jika dilakukan Rollback
            $table->dropColumn(['wawancara_waktu', 'wawancara_lokasi', 'pesan']);
        });
    }
};