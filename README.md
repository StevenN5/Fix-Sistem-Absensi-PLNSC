# Sistem Pengelolaan Absensi Peserta Magang - PT PLN Suku Cadang

Aplikasi web berbasis Laravel untuk mengelola absensi peserta magang, pengajuan ketidakhadiran, serta dokumen laporan bulanan dan akhir. Sistem menyediakan area admin untuk monitoring dan persetujuan, serta area pengguna untuk pencatatan kehadiran dan unggah dokumen.

## Fitur Utama
- Landing page dan pendaftaran magang online.
- Dashboard admin untuk monitoring data absensi dan peserta.
- Manajemen data master:
  - Divisi
  - Mentor
  - Hari libur nasional
  - Jadwal kerja
  - Data pegawai/peserta
- Pencatatan absensi masuk dan pulang oleh pengguna.
- Riwayat absensi pengguna dan draft dokumen absensi.
- Pengajuan ketidakhadiran oleh pengguna dan alur review admin.
- Unggah, lihat, dan unduh:
  - Laporan bulanan
  - Laporan akhir
  - Template/dokumen draft magang
- Ekspor data pendaftaran magang dan laporan per bulan.
- Manajemen role `admin` dan `user`.

## Teknologi
- PHP 7.3+ / 8.x
- Laravel 8
- MySQL
- Blade Template
- Bootstrap
- JavaScript (Laravel Mix)
- Maatwebsite Excel (ekspor data)

## Prasyarat
- PHP dan Composer
- Node.js dan npm
- MySQL/MariaDB

## Instalasi dan Setup Lokal
```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Atur konfigurasi database pada file `.env`, lalu jalankan:

```bash
php artisan migrate --seed
```

## Menjalankan Aplikasi
```bash
php artisan serve
```

Untuk kompilasi aset frontend:

```bash
npm run dev
```

Build production:

```bash
npm run prod
```

## Akun Default Seeder
Seeder bawaan akan membuat akun admin berikut:

- Email: `admin@ams.com`
- Password: `admin@ams.com`

## Struktur Singkat Fitur Berdasarkan Akses
- Admin:
  - Kelola data master, absensi, ketidakhadiran, laporan, dan pendaftaran magang.
- User:
  - Check-in/check-out, lihat riwayat absensi, ajukan ketidakhadiran, unggah dan unduh dokumen.

## Catatan
- Endpoint registrasi user (`/register`) aktif.
- Fitur reset password tidak diaktifkan secara default.

## Lisensi
MIT License
