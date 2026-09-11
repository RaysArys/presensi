-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: presensi_db
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `hari_libur`
--

DROP TABLE IF EXISTS `hari_libur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hari_libur` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `nama_libur` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hari_libur_tanggal_unique` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hari_libur`
--

LOCK TABLES `hari_libur` WRITE;
/*!40000 ALTER TABLE `hari_libur` DISABLE KEYS */;
/*!40000 ALTER TABLE `hari_libur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jabatan`
--

DROP TABLE IF EXISTS `jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jabatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jabatan_nama_jabatan_unique` (`nama_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jabatan`
--

LOCK TABLES `jabatan` WRITE;
/*!40000 ALTER TABLE `jabatan` DISABLE KEYS */;
INSERT INTO `jabatan` VALUES (1,'Staff','2026-09-02 00:11:02','2026-09-02 00:11:02'),(2,'Supervisor','2026-09-02 00:11:02','2026-09-02 00:11:02'),(3,'Administrasi','2026-09-02 00:11:02','2026-09-02 00:11:02');
/*!40000 ALTER TABLE `jabatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ketidakhadiran`
--

DROP TABLE IF EXISTS `ketidakhadiran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ketidakhadiran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pegawai_id` bigint unsigned NOT NULL,
  `jenis` enum('izin','sakit','cuti') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_alasan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `file_bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pengajuan` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `diproses_oleh` bigint unsigned DEFAULT NULL,
  `diproses_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ketidakhadiran_pegawai_id_foreign` (`pegawai_id`),
  KEY `ketidakhadiran_diproses_oleh_foreign` (`diproses_oleh`),
  KEY `ketidakhadiran_tanggal_mulai_tanggal_selesai_index` (`tanggal_mulai`,`tanggal_selesai`),
  KEY `ketidakhadiran_status_pengajuan_index` (`status_pengajuan`),
  CONSTRAINT `ketidakhadiran_diproses_oleh_foreign` FOREIGN KEY (`diproses_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ketidakhadiran_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ketidakhadiran`
--

LOCK TABLES `ketidakhadiran` WRITE;
/*!40000 ALTER TABLE `ketidakhadiran` DISABLE KEYS */;
INSERT INTO `ketidakhadiran` VALUES (1,1,'cuti','Cuti Tahunan','Cuti Tahunan','2026-09-02','2026-09-02',NULL,'disetujui','siap abangku',1,'2026-09-02 07:50:42','2026-09-02 00:50:13','2026-09-02 00:50:42'),(2,2,'izin','Urusan keluarga','Urusan keluarga','2026-09-07','2026-09-07',NULL,'disetujui',NULL,1,'2026-09-07 08:22:44','2026-09-07 01:22:31','2026-09-07 01:22:44');
/*!40000 ALTER TABLE `ketidakhadiran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lokasi_presensi`
--

DROP TABLE IF EXISTS `lokasi_presensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lokasi_presensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `radius_meter` int unsigned NOT NULL DEFAULT '100',
  `zona_waktu` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `jam_masuk` time NOT NULL DEFAULT '08:00:00',
  `batas_terlambat` time NOT NULL DEFAULT '08:15:00',
  `jam_pulang` time NOT NULL DEFAULT '16:00:00',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lokasi_presensi`
--

LOCK TABLES `lokasi_presensi` WRITE;
/*!40000 ALTER TABLE `lokasi_presensi` DISABLE KEYS */;
INSERT INTO `lokasi_presensi` VALUES (1,'SMK TUNAS MEDIA','SMK TUNAS MEDIA',-6.37065300,106.74535500,100,'Asia/Jakarta','08:00:00','08:15:00','16:00:00','aktif','2026-09-02 00:11:03','2026-09-02 00:11:03');
/*!40000 ALTER TABLE `lokasi_presensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_09_01_000001_create_presensi_tables',1),(2,'2026_09_07_000001_update_user_roles',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pegawai`
--

DROP TABLE IF EXISTS `pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pegawai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jabatan_id` bigint unsigned DEFAULT NULL,
  `nomor_pegawai` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_handphone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `foto_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pegawai` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pegawai_nomor_pegawai_unique` (`nomor_pegawai`),
  UNIQUE KEY `pegawai_email_unique` (`email`),
  KEY `pegawai_jabatan_id_foreign` (`jabatan_id`),
  CONSTRAINT `pegawai_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pegawai`
--

LOCK TABLES `pegawai` WRITE;
/*!40000 ALTER TABLE `pegawai` DISABLE KEYS */;
INSERT INTO `pegawai` VALUES (1,1,'PGW001','User Demo','L','user@demo.test','08123456789','Jakarta',NULL,'aktif','2026-09-02 00:11:02','2026-09-02 00:11:02'),(2,1,'PGW002','Roni Madropi','L','ronimadropi@gmail.com','0895379864269','Perumahan Nuansa Asri Cinangka',NULL,'aktif','2026-09-02 00:16:10','2026-09-02 00:16:10');
/*!40000 ALTER TABLE `pegawai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presensi`
--

DROP TABLE IF EXISTS `presensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pegawai_id` bigint unsigned NOT NULL,
  `lokasi_presensi_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `foto_masuk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_masuk` decimal(10,8) DEFAULT NULL,
  `longitude_masuk` decimal(11,8) DEFAULT NULL,
  `akurasi_masuk` decimal(8,2) DEFAULT NULL,
  `jarak_masuk` decimal(10,2) DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `foto_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude_pulang` decimal(10,8) DEFAULT NULL,
  `longitude_pulang` decimal(11,8) DEFAULT NULL,
  `akurasi_pulang` decimal(8,2) DEFAULT NULL,
  `jarak_pulang` decimal(10,2) DEFAULT NULL,
  `status` enum('hadir','terlambat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presensi_pegawai_id_tanggal_unique` (`pegawai_id`,`tanggal`),
  KEY `presensi_lokasi_presensi_id_foreign` (`lokasi_presensi_id`),
  KEY `presensi_tanggal_status_index` (`tanggal`,`status`),
  CONSTRAINT `presensi_lokasi_presensi_id_foreign` FOREIGN KEY (`lokasi_presensi_id`) REFERENCES `lokasi_presensi` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `presensi_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presensi`
--

LOCK TABLES `presensi` WRITE;
/*!40000 ALTER TABLE `presensi` DISABLE KEYS */;
INSERT INTO `presensi` VALUES (1,2,1,'2026-09-02','08:00:07','presensi/2026/09/2-20260902-080007-masuk.jpg',-6.37079137,106.74602817,102.00,75.97,NULL,NULL,NULL,NULL,NULL,NULL,'hadir',NULL,'2026-09-02 01:00:07','2026-09-02 01:00:07'),(2,1,1,'2026-09-07','08:21:28','presensi/2026/09/1-20260907-082127-masuk.jpg',-6.37082400,106.74608800,241.00,83.20,NULL,NULL,NULL,NULL,NULL,NULL,'terlambat',NULL,'2026-09-07 01:21:28','2026-09-07 01:21:28');
/*!40000 ALTER TABLE `presensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pegawai_id` bigint unsigned DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','supervisor','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `status_akun` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_pegawai_id_unique` (`pegawai_id`),
  CONSTRAINT `users_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'admin','$2y$12$eyVIswlPq5Phh1bCgo6i8OaNB.tYNdXDXOA8v3kBmoYjqTAfceOzW','admin','aktif',NULL,'2026-09-02 00:11:02','2026-09-02 00:11:02'),(2,1,'user','$2y$12$va52xGTAicj8LV4QbReBXesdSiuDz2zwsw7KC9pPRlygz3cJ7v3ny','staff','aktif',NULL,'2026-09-02 00:11:03','2026-09-02 00:11:03'),(3,2,'roni','$2y$12$hmgMIISUehaP3Qz2hkxNpOD3F9Sk87fPiBMQX5ylkutZlT9Aa9zs.','staff','aktif',NULL,'2026-09-02 00:16:10','2026-09-02 00:16:10');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'presensi_db'
--

--
-- Dumping routines for database 'presensi_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-11 19:45:51
