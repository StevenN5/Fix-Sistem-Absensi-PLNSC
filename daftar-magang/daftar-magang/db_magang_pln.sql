-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_magang_pln
CREATE DATABASE IF NOT EXISTS `db_magang_pln` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_magang_pln`;

-- Dumping structure for table db_magang_pln.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.cache: ~0 rows (approximately)

-- Dumping structure for table db_magang_pln.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.cache_locks: ~0 rows (approximately)

-- Dumping structure for table db_magang_pln.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table db_magang_pln.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.jobs: ~0 rows (approximately)

-- Dumping structure for table db_magang_pln.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.job_batches: ~0 rows (approximately)

-- Dumping structure for table db_magang_pln.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.migrations: ~7 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_02_18_011407_create_pendaftars_table', 1),
	(5, '2026_02_18_074534_add_wawancara_to_pendaftars_table', 2),
	(6, '2026_02_18_082452_add_jenis_magang_to_pendaftars_table', 3),
	(7, '2026_02_18_090247_add_periode_to_pendaftars_table', 4),
	(8, '2026_02_19_055028_add_tipe_mahasiswa_to_pendaftars_table', 5),
	(9, '2026_02_19_055901_add_ipk_to_pendaftars_table', 5),
	(10, '2026_02_19_061000_change_dokumen_pendukung_to_text_in_pendaftars', 5);

-- Dumping structure for table db_magang_pln.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table db_magang_pln.pendaftars
CREATE TABLE IF NOT EXISTS `pendaftars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_magang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_mahasiswa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `asal_kampus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ipk` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periode_magang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transkrip_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surat_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Menunggu','Wawancara','Diterima','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `wawancara_waktu` datetime DEFAULT NULL,
  `wawancara_lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci,
  `surat_permohonan_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dokumen_pendukung_path` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pendaftars_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.pendaftars: ~5 rows (approximately)
INSERT INTO `pendaftars` (`id`, `nama`, `jenis_magang`, `tipe_mahasiswa`, `email`, `no_hp`, `alamat`, `asal_kampus`, `jurusan`, `ipk`, `semester`, `periode`, `periode_magang`, `cv_path`, `transkrip_path`, `surat_path`, `status`, `created_at`, `updated_at`, `wawancara_waktu`, `wawancara_lokasi`, `pesan`, `surat_permohonan_path`, `dokumen_pendukung_path`) VALUES
	(6, 'Bachtiar Yusuf', 'mahasiswa', NULL, 'tiar20057@gmail.com', '089629750413', 'Tangsel', 'ITPLN', 'Informatika', NULL, '6', '19 February 2026 s/d 30 June 2026', NULL, 'dokumen_magang/FdNNSsPlQGngZmMeoxakJFvedv12EVLcUpJiB3ch.pdf', 'dokumen_magang/4VaIXvL7ouzcjxtuLnalnGSaBM73OYOKGve4fS4P.pdf', 'dokumen_magang/YNkPVxUc8ecAP0QNoda1jYBwbpPt5xriuufPRdkx.pdf', 'Wawancara', '2026-02-18 19:50:23', '2026-02-18 19:51:25', '2026-02-19 12:51:00', 'http', 'jangan telat', 'dokumen_magang/TtufOjYPBKBEcs9rUzq7mnsi8wBUTeISoHyZoO2a.pdf', 'dokumen_magang/yb36kUlCcLVoqBgOl57WW9NG8S9gsUD4JFigFeFc.pdf'),
	(7, 'Rakha Nugroho', 'mahasiswa', NULL, 'rakhane7hueyeygu@gmail.com', '6285782849765', 'jakarta timur, cakung desa cakung', 'ITPLN', 'Sistem Informasi', NULL, '6', '02 February 2026 s/d 28 February 2026', NULL, 'dokumen_magang/fhj7wG8EiN9z1GzsGPd1Qx5zKmz7taSKJmgHgubI.pdf', 'dokumen_magang/a2JxeZu7CeXPi6N0RuBOqThVeRmYcRCxPHd7pYLZ.pdf', 'dokumen_magang/lWYjBl1AB8mv5NpljPud1VQyA7XVLOqHXEbdEr5j.pdf', 'Menunggu', '2026-02-18 20:21:26', '2026-02-18 20:21:26', NULL, NULL, NULL, 'dokumen_magang/KQmf8CRDtGCJ89pWm28tGz8z1qyLHCV7grd0Ctmu.pdf', 'dokumen_magang/ADmwZNWvd87HqtAau0unNndZ6OEApKTgiGyLsRxu.pdf'),
	(8, 'arza', 'mahasiswa', 'pkl', 'aaa@gmail.com', '6282213846689', 'jakarta utara', 'ITPLN', 'INFORMATIKA', '3.5', '6', '02 February 2026 s/d 03 March 2026', NULL, 'dokumen_magang/h8vyL1pMH7DgrmAYIxaJbKqo8J25zQOsETcaiUcL.pdf', 'dokumen_magang/6Soy6nA1lTnWH3eG0RQihZf4b397pDLMKbIoRdhB.pdf', 'dokumen_magang/jv2cz8d7o5LGzA5cEXy8fSS4E0sTNM71SxUuCdov.pdf', 'Menunggu', '2026-02-18 23:24:20', '2026-02-18 23:24:20', NULL, NULL, NULL, 'dokumen_magang/FsIZOkKHWLsXDHXSweQLi5aOzh15JMYkY5clu6kg.pdf', '["dokumen_magang\\/aa0BK3pn0qLzlY6GLFUu0q5N3f5OWjadP7VBPQFh.pdf"]'),
	(9, 'Chandra Brahimsyah', 'fresh_graduate', NULL, 'chandra@gmail.com', '62812345678911', 'Bekasi', 'ITPLN', 'INFORMATIKA', '3.5', NULL, '03 February 2026 s/d 03 March 2026', NULL, 'dokumen_magang/tSR8ePUXgjftp13ZDAmnpcYcv6XK7T5OLs8uVFpV.pdf', 'dokumen_magang/DeEp9OeCNXnDRGfeiPgjpW9y6osGJiZ2QBOTUvD1.pdf', NULL, 'Diterima', '2026-02-18 23:30:47', '2026-02-18 23:55:45', '2026-02-19 13:49:00', 'sini', NULL, 'dokumen_magang/TmKQyqufAjv8nRLQjY4auldBloOV3CUsK0P7Pxt6.pdf', '["dokumen_magang\\/1CmiQOCQfsOYHybG8OdWGZadsQtEseKOrlznDqmJ.pdf","dokumen_magang\\/ibBowc6lGYy9d6dcGigTXjfZuK4Hj9jeNjLmrrZ4.pdf","dokumen_magang\\/SmviLjHUyW1A79V7Zw050ArBlaj3HefUrJWZUaBS.pdf"]'),
	(10, 'Annora Iffah Diniyah Darma', 'mahasiswa', 'pkl', 'annoraiffah@gmail.com', '6285696705541', 'Jl. Raya Duri Kosambi', 'ITPLN', 'INFORMATIKA', '3.9', '5', '01 March 2026 s/d 01 April 2026', NULL, 'dokumen_magang/xwLcD01EAuBqDEzuySERJDaBDgOGWSINawQr7UF5.pdf', 'dokumen_magang/0hf4kKxzIDsW9spO9rYJ4nSSMg0QR9010as4MrQW.pdf', 'dokumen_magang/n4YSerHWYUqVGPC6uMb4g62D8WzISvW0ouwN3nF3.pdf', 'Diterima', '2026-02-19 19:02:15', '2026-02-19 19:18:20', '2026-02-21 10:02:00', 'http', NULL, 'dokumen_magang/qXQHGUW0T4Zu9oI6tlVkq5UWzJ1X7SHqpMO18ZKz.pdf', '["dokumen_magang\\/puWFAHZ0k6OGhsPreLC51k85TFn0YbR27MgJEqrG.pdf"]');

-- Dumping structure for table db_magang_pln.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.sessions: ~3 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('5zUlTUzl0DmHO3E90cPJqG0xjFW2cB4uzEIp91sj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQzF5cVRRVFBJdXdTb2hmelJiVUxzU0duQzNMczJXS1ZiY0dTbXhKSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9kYWZ0YXItbWFnYW5nLnRlc3QvbWFnYW5nL2RhZnRhciI7czo1OiJyb3V0ZSI7czoxMToibWFnYW5nLmZvcm0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6ImlzX2FkbWluIjtiOjE7fQ==', 1771557829),
	('jACRbdpQcuryaZ5kkLvSdyRg6hcm1pKJB2zSlujE', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT3lpZEp1ald5eDBkeEVoMVhFVVFHVmpEaExDaWVBajNiWllDZVpaVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYWdhbmcvYWRtaW4vZGV0YWlsLzYiO3M6NToicm91dGUiO3M6MTk6Im1hZ2FuZy5hZG1pbi5kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771552308),
	('jH8ZeFlTFidShkWskt2ZZVeEFDlyDtg1P1ISmfiE', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMnRGY2pWcUpmbTBDMm1GazRiSlJoT09qREpSQTRSN1pwT0dPV290ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYWdhbmcvYWRtaW4vZGV0YWlsLzEiO3M6NToicm91dGUiO3M6MTk6Im1hZ2FuZy5hZG1pbi5kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771552253);

-- Dumping structure for table db_magang_pln.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_magang_pln.users: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
