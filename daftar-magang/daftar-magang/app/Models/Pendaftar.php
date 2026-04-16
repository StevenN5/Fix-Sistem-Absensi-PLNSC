<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    // Pastikan semua kolom ini ada di sini!
    protected $fillable = [
        // 1. Data Diri & Jenis Magang
        'jenis_magang',
        'tipe_mahasiswa',  // <--- WAJIB ADA
        'nama', 
        'email', 
        'no_hp', 
        'alamat',
        
        // 2. Data Akademik
        'asal_kampus', 
        'jurusan', 
        'ipk',
        'semester', 
        'periode',
        
        // 3. Status & Pesan Admin
        'status', 
        'pesan',
        
        // 4. Jadwal Wawancara
        'wawancara_waktu', 
        'wawancara_lokasi', 
        
        // 5. Dokumen (Path File)
        'cv_path', 
        'transkrip_path', 
        'surat_path',            // Surat Pengantar Kampus
        'surat_permohonan_path', // Surat Permohonan Magang
        'dokumen_pendukung_path' // Dokumen Opsional
    ];

    protected $casts = [
        'wawancara_waktu' => 'datetime',
        'dokumen_pendukung_path' => 'array', 
    ];

    /**
     * MUTATOR: Otomatis ubah format nomor HP saat disimpan.
     * Mengubah 08... menjadi 628... agar link WhatsApp bekerja.
     */
    public function setNoHpAttribute($value)
    {
        // 1. Hapus spasi atau karakter selain angka
        $cleanNumber = preg_replace('/[^0-9]/', '', $value);

        // 2. Jika diawali '08', ganti jadi '628'
        if (substr($cleanNumber, 0, 2) === '08') {
            $this->attributes['no_hp'] = '62' . substr($cleanNumber, 1);
        } else {
            // Jika sudah 62 atau format lain, simpan apa adanya
            $this->attributes['no_hp'] = $cleanNumber;
        }
    }
}