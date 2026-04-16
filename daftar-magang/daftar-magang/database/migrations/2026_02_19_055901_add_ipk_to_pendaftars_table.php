<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('pendaftars', function (Blueprint $table) {
        // Tambahkan kolom ipk setelah jurusan, tipe datanya string (agar aman menampung 3.xx)
        $table->string('ipk', 10)->nullable()->after('jurusan');
    });
}

public function down(): void
{
    Schema::table('pendaftars', function (Blueprint $table) {
        $table->dropColumn('ipk');
    });
}
};
