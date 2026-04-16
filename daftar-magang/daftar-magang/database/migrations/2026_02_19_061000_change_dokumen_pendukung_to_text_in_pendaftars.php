<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->text('dokumen_pendukung_path')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->string('dokumen_pendukung_path')->nullable()->change();
    });
}
};
