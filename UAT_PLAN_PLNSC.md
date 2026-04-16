# UAT Plan - Sistem Manajemen Pengelolaan (PLNSC)

Tanggal dokumen: 10 April 2026  
Versi: 1.0  
Tujuan: memastikan fitur sesuai kebutuhan operasional Admin dan User sebelum go-live.

## 1. Ruang Lingkup UAT
- Login & otorisasi role (`admin`, `user`)
- Dashboard Admin & Dashboard User
- Presensi (masuk/pulang) dan status ketepatan waktu
- Riwayat kehadiran (kalender + export PDF)
- Ketidakhadiran:
  - Izin & Sakit
  - Lupa Absensi (koreksi admin)
- Laporan bulanan / laporan akhir
- Draft dokumen magang (admin upload, user view/download)
- Master data: Divisi, Mentor, Cuti Nasional
- Dampak master data ke profil, kalender, dan export
- Responsif mobile pada halaman user utama

## 2. Di Luar Scope UAT
- Load testing/performance tinggi
- Security pentest formal
- Integrasi perangkat fingerprint fisik (jika tidak tersedia saat UAT)

## 3. Kriteria Masuk UAT (Entry Criteria)
- Migrasi database sukses (`php artisan migrate`)
- Seeder/test data minimal tersedia (1 admin, 2 user)
- Semua route utama bisa diakses
- Storage link tersedia (`php artisan storage:link`)
- Tidak ada error fatal di log saat smoke test

## 4. Kriteria Lulus UAT (Exit Criteria)
- 100% test case Critical & High = PASS
- Maksimal 5% Medium gagal dan ada workaround
- Tidak ada bug blocker pada alur bisnis utama

## 5. Peran UAT
- UAT Lead: mengatur jadwal, final sign-off
- Business User (Admin): verifikasi alur operasional admin
- Business User (Peserta): verifikasi kenyamanan alur user
- QA/Support: dokumentasi bug dan retest
- Dev: perbaikan bug dan konfirmasi patch

## 6. Data Uji yang Disiapkan
- Admin A
- User A (ada employee profile lengkap)
- User B (belum lengkap profile, untuk negative test)
- Divisi: `Perencanaan`, `Teknis`
- Mentor: masing-masing divisi minimal 1 mentor
- Cuti nasional: 1 `LH`, 1 `CB` di bulan aktif

## 7. Skenario UAT Per Modul (Ringkas)

### A. Login & Role
1. Admin login berhasil, masuk menu admin.
2. User login berhasil, masuk menu user.
3. User tidak bisa akses route admin.

### B. Presensi User
1. Masuk sebelum/sama 08:00 => status tepat waktu (`H`).
2. Masuk > 08:00 => status terlambat (`T`).
3. Pulang >= 16:30 => tepat waktu.
4. Pulang < 16:30 => terlambat (`T`).

### C. Riwayat Kehadiran User
1. Kalender tampil per bulan.
2. Chip warna status sesuai jenis.
3. Jam masuk/pulang tampil saat data ada.
4. Export PDF berhasil dan konten sesuai bulan terpilih.

### D. Ketidakhadiran - Izin/Sakit
1. User submit Izin/Sakit dengan alasan.
2. Admin setujui => status menjadi `I`/`S` di kalender.
3. Admin tolak => status `TK`.

### E. Ketidakhadiran - Lupa Absensi
1. User submit `Lupa Absensi` + jam koreksi.
2. Admin approve + verifikasi jam koreksi.
3. Sistem menulis data ke attendance/leave.
4. Kalender dan PDF user/admin ikut terupdate.

### F. Laporan & Draft
1. User upload laporan bulanan.
2. User upload laporan akhir.
3. User bisa preview/view dokumen tanpa download wajib.
4. Admin bisa lihat dokumen yang diupload.
5. Admin upload draft template; user bisa view/download.

### G. Master Data Admin
1. CRUD Divisi.
2. CRUD Mentor per Divisi.
3. CRUD Cuti Nasional (`LH`/`CB`).
4. Dampak `LH/CB` ke kalender + kode keterangan export.

### H. Mobile UAT (User)
1. Navbar user bisa dibuka via hamburger.
2. Halaman Kehadiran, Riwayat, Ketidakhadiran, Laporan nyaman dipakai <= 390px.
3. Tabel berubah ke kartu pada layar kecil.

## 8. Defect Severity
- Blocker: fitur inti tidak bisa dipakai sama sekali
- High: fitur inti jalan tapi hasil bisnis salah
- Medium: fungsi jalan, ada mismatch UI/UX/logika non-kritis
- Low: typo, alignment minor, kosmetik

## 9. Alur Eksekusi UAT (Praktis)
1. Jalankan smoke test 15 menit.
2. Eksekusi test case Critical dulu.
3. Catat hasil di sheet eksekusi (`PASS/FAIL`).
4. Bug fix oleh dev.
5. Retest bug yang sudah fix.
6. Final sign-off.

## 10. Template Bukti UAT
Setiap case minimal simpan:
- Screenshot sebelum/sesudah aksi
- URL halaman
- Role penguji
- Timestamp pengujian

Gunakan file pendamping: `UAT_EXECUTION_SHEET.csv`.
