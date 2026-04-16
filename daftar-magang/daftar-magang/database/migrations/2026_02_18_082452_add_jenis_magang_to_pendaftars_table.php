<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->string('jenis_magang')->after('nama'); // 'fresh_graduate' atau 'mahasiswa'
        $table->string('surat_permohonan_path')->nullable(); // Wajib untuk semua
        $table->string('dokumen_pendukung_path')->nullable(); // Opsional
        
        // Catatan: 'surat_path' yang lama akan kita pakai sebagai 'Surat Pengantar Kampus'
        // Kita ubah 'surat_path' jadi nullable karena Fresh Graduate tidak wajib upload ini
        $table->string('surat_path')->nullable()->change(); 
        $table->string('semester')->nullable()->change(); // Fresh graduate tidak punya semester
    });
}

public function down(): void
{
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->dropColumn(['jenis_magang', 'surat_permohonan_path', 'dokumen_pendukung_path']);
        // Kembalikan ke not null (hati-hati jika ada data kosong)
        // $table->string('surat_path')->nullable(false)->change(); 
    });
}
};
