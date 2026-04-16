<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('pendaftars', function (Blueprint $table) {
        
        $table->string('tipe_mahasiswa')->nullable()->after('jenis_magang');
    });
}

public function down(): void
{
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->dropColumn('tipe_mahasiswa');
    });
}
};
