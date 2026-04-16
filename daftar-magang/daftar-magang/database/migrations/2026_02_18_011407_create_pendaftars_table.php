<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            
            // --- DATA PRIBADI ---
            $table->string('nama');                
            $table->string('email')->unique();      
            $table->string('no_hp');                
            $table->text('alamat')->nullable();     
            
            // --- DATA AKADEMIK ---
            $table->string('asal_kampus');          
            $table->string('jurusan');              
            $table->integer('semester');
            $table->string('periode_magang')->nullable();            
            
            // --- FILE DOKUMEN ---
            
            $table->string('cv_path')->nullable();  
            $table->string('transkrip_path')->nullable();
            $table->string('surat_path')->nullable();

            // --- STATUS LAMARAN ---
            
            $table->enum('status', ['Menunggu', 'Wawancara', 'Diterima', 'Ditolak'])
                  ->default('Menunggu');

            $table->timestamps(); 
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};