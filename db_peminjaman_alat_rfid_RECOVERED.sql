-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_peminjaman_alat_rfid
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `nama_admin` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Admin Studio','admin','123456','2026-07-10 18:16:11');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alat`
--

DROP TABLE IF EXISTS `alat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alat` (
  `id_alat` int(11) NOT NULL AUTO_INCREMENT,
  `kode_alat` varchar(30) DEFAULT NULL,
  `barcode_alat` varchar(100) DEFAULT NULL,
  `nama_alat` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `maks_lama_pinjam` int(11) DEFAULT NULL,
  `status_alat` varchar(50) NOT NULL DEFAULT 'Tersedia',
  `foto_alat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_alat`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alat`
--

LOCK TABLES `alat` WRITE;
/*!40000 ALTER TABLE `alat` DISABLE KEYS */;
INSERT INTO `alat` VALUES (2,'GTR002','','Gitar Akustik',NULL,3,'Rusak Ringan','gitar_akustik_unu.png','2026-07-14 05:52:17','2026-07-29 08:24:16'),(4,'GTR004','','gitar tele','Gitar',4,'Rusak Ringan','gitar_tele_unu.png','2026-07-14 07:24:23','2026-07-29 08:24:16'),(5,'GTR006','','gitar ibanez 983','Gitar',3,'Rusak Ringan','gitar_ibanez_unu.png','2026-07-14 09:29:51','2026-07-29 08:24:16'),(6,'GTR003','','drum pearl','Drum',3,'Rusak Ringan','1784392708_ChatGPT Image 9 Jul 2026, 13.50.29.png','2026-07-18 09:38:28','2026-07-29 08:24:16'),(7,'GTR001','GTR001','keyboard korg','Keyboard',3,'Rusak Berat','1784392739_ChatGPT Image 9 Jul 2026, 13.50.29.png','2026-07-18 09:38:59','2026-07-31 11:43:34'),(8,'GTR005','GTR005','ampli marshal','Amplifier',3,'Rusak Ringan','1784392796_ChatGPT Image 10 Jul 2026, 21.57.49.png','2026-07-18 09:39:56','2026-07-29 01:43:53'),(9,'ALT0009','ALT0009','gitar ukulele','Gitar',3,'Rusak Ringan','1784901506_ChatGPT Image 10 Jul 2026, 17.26.36.png','2026-07-24 06:58:26','2026-07-31 11:43:47'),(10,'ALT0010','ALT0010','bass betot','Gitar',3,'Rusak Berat','1784901696_ChatGPT Image 9 Jul 2026, 13.49.29.png','2026-07-24 07:01:36','2026-07-29 01:56:51'),(11,'ALT0011','ALT0011','drum honda','Drum',3,'Rusak Ringan','1784912896_ChatGPT Image 10 Jul 2026, 17.26.36.png','2026-07-24 10:08:16','2026-07-29 11:26:25'),(12,'ALT0012','ALT0012','piano korg','Keyboard',3,'Rusak Berat','1785137321_ChatGPT Image 10 Jul 2026, 17.26.36.png','2026-07-27 00:28:41','2026-07-31 02:32:25');
/*!40000 ALTER TABLE `alat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anggota`
--

DROP TABLE IF EXISTS `anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL AUTO_INCREMENT,
  `id_pendaftaran` int(11) DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `prodi` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `id_rfid` varchar(50) DEFAULT NULL,
  `uid_rfid` varchar(50) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `foto_ktm` varchar(255) DEFAULT NULL,
  `status_anggota` varchar(50) NOT NULL DEFAULT 'Aktif',
  `tanggal_mulai_sanksi` date DEFAULT NULL,
  `tanggal_berakhir_sanksi` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_anggota`),
  UNIQUE KEY `id_rfid` (`id_rfid`),
  UNIQUE KEY `nim` (`nim`),
  UNIQUE KEY `anggota_uid_rfid_unique` (`uid_rfid`),
  KEY `fk_anggota_pendaftaran` (`id_pendaftaran`),
  CONSTRAINT `fk_anggota_pendaftaran` FOREIGN KEY (`id_pendaftaran`) REFERENCES `pendaftaran_anggota` (`id_pendaftaran`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota`
--

LOCK TABLES `anggota` WRITE;
/*!40000 ALTER TABLE `anggota` DISABLE KEYS */;
INSERT INTO `anggota` VALUES (3,5,'32342342','rizki raharjo','Teknik Komputer','jetis','38D512F5','38D512F5','08932472342','muhammad.rizky.kom22@student.unu-jogja.ac.id','1783883835_Ig63L7ZB1m.png','Aktif',NULL,NULL,'2026-07-12 12:17:58','2026-07-31 11:44:34'),(4,10,'2214241212','supri','Teknik Komputer','jetisss',NULL,NULL,'324234234234','m.gesang.kom22@student.unu-jogja.ac.id','1783886361_FUSxFzTgyU.png','Ditangguhkan','2026-07-29',NULL,'2026-07-12 12:59:51','2026-07-29 01:29:23'),(5,12,'221132131','ruli','Teknik Komputer','barongan\r\nrt 01','F4604CEB','F4604CEB','0839236234234','m.gesang.kom22@student.unu-jogja.ac.id','1783967620_ncNuxmzEu3.png','Nonaktif',NULL,NULL,'2026-07-13 11:34:21','2026-07-31 11:10:27'),(10,18,'213333123','Firyaal naufi al kautsar','Farmasi','Gamping tengah ambarketawang sleman','38CE09F5','38CE09F5','089507723451','firyaal.naufi.far21@student.unu-jogja.ac.id','1785083527_SU2g7nnajk.JPG','Aktif',NULL,NULL,'2026-07-26 09:34:03','2026-07-31 02:18:09'),(20,23,'221131231','farhan','Teknik Komputer','bantul','60025C5C','60025C5C','047234923423','251111028@student.unu-jogja.ac.id','1785489819_Z2i3eI0hzy.png','Aktif',NULL,NULL,'2026-07-31 02:24:31','2026-07-31 02:35:07');
/*!40000 ALTER TABLE `anggota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catatan_pelanggaran`
--

DROP TABLE IF EXISTS `catatan_pelanggaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catatan_pelanggaran` (
  `id_pelanggaran` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengembalian` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `batas_pengembalian` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `hari_terlambat` int(11) DEFAULT 0,
  `denda_keterlambatan` decimal(10,2) DEFAULT 0.00,
  `denda_kerusakan` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jenis_pelanggaran` varchar(100) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `sanksi` varchar(100) DEFAULT NULL,
  `status_pembayaran` varchar(50) NOT NULL DEFAULT 'Belum Lunas',
  `tanggal_pembayaran` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pelanggaran`),
  KEY `id_pengembalian` (`id_pengembalian`),
  KEY `id_anggota` (`id_anggota`),
  CONSTRAINT `catatan_pelanggaran_ibfk_1` FOREIGN KEY (`id_pengembalian`) REFERENCES `pengembalian` (`id_pengembalian`),
  CONSTRAINT `catatan_pelanggaran_ibfk_2` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catatan_pelanggaran`
--

LOCK TABLES `catatan_pelanggaran` WRITE;
/*!40000 ALTER TABLE `catatan_pelanggaran` DISABLE KEYS */;
INSERT INTO `catatan_pelanggaran` VALUES (1,25,4,'2026-07-22','2026-07-19',0,0.00,0.00,'Rusak',NULL,'-','Belum Lunas',NULL,'2026-07-19 11:40:26','2026-07-29 08:24:16'),(2,26,8,'2026-07-15','2026-07-19',4,40000.00,0.00,'Terlambat',NULL,'-','Lunas','2026-07-29','2026-07-19 11:41:00','2026-07-29 01:38:35'),(3,27,5,'2026-07-21','2026-07-20',0,0.00,0.00,'Rusak',NULL,'-','Belum Lunas',NULL,'2026-07-20 07:20:43','2026-07-29 08:24:16'),(4,28,7,'2026-07-10','2026-07-20',10,100000.00,0.00,'Terlambat,Rusak',NULL,'-','Lunas','2026-07-29','2026-07-20 07:24:45','2026-07-29 01:39:11'),(5,31,5,'2026-07-23','2026-07-20',0,0.00,50000.00,'Rusak',NULL,'-','Belum Lunas',NULL,'2026-07-20 07:50:44','2026-07-29 08:24:16'),(6,32,4,'2026-07-23','2026-07-20',0,0.00,150000.00,'Rusak',NULL,'-','Lunas','2026-07-31','2026-07-20 07:51:53','2026-07-31 11:09:03'),(7,33,8,'2026-07-23','2026-07-20',0,0.00,500000.00,'Hilang',NULL,'-','Lunas','2026-07-29','2026-07-20 07:52:01','2026-07-29 01:38:42'),(8,34,5,'2026-06-02','2026-07-20',48,480000.00,50000.00,'Terlambat,Rusak',NULL,'Keanggotaan Dinonaktifkan','Belum Lunas',NULL,'2026-07-20 10:11:01','2026-07-29 08:24:16'),(9,35,7,'2026-07-10','2026-07-20',10,100000.00,150000.00,'Terlambat,Rusak',NULL,'Ditangguhkan 7 Hari','Lunas','2026-07-29','2026-07-20 10:11:30','2026-07-29 01:39:02'),(10,36,1,'2026-07-23','2026-07-24',1,10000.00,50000.00,'Terlambat,Rusak',NULL,'-','Belum Lunas',NULL,'2026-07-24 01:58:58','2026-07-29 08:24:16'),(11,37,8,'2026-07-27','2026-07-24',0,0.00,150000.00,'Rusak','Bagian senar putus dan gitar pecah','-','Lunas','2026-07-29','2026-07-24 08:34:41','2026-07-29 01:35:53'),(12,38,7,'2026-07-27','2026-07-24',0,0.00,150000.00,'Rusak',NULL,'-','Lunas','2026-07-27','2026-07-24 08:35:37','2026-07-29 08:24:16'),(13,39,9,'2026-07-01','2026-07-24',23,230000.00,150000.00,'Terlambat,Rusak',NULL,'Ditangguhkan 7 Hari','Lunas','2026-07-24','2026-07-24 10:12:43','2026-07-29 08:24:16'),(14,50,11,'2026-07-30','2026-07-27',0,0.00,50000.00,'Kerusakan Alat (Rusak Ringan)','senar putus','Ditangguhkan 7 Hari & Denda Tertunggak','Lunas','2026-07-29','2026-07-27 08:10:58','2026-07-29 09:28:23'),(15,51,1,'2026-07-30','2026-07-27',0,0.00,50000.00,'Kerusakan Alat (Rusak Ringan)','Uji coba kerusakan senar gitar','Ditangguhkan 7 Hari & Denda Tertunggak','Belum Lunas',NULL,'2026-07-27 13:41:29','2026-07-29 08:24:16'),(16,52,2,'2026-07-30','2026-07-27',0,0.00,50000.00,'Kerusakan Alat (Rusak Ringan)','Uji coba kerusakan senar gitar','Ditangguhkan 7 Hari & Denda Tertunggak','Belum Lunas',NULL,'2026-07-27 14:16:09','2026-07-29 08:24:16'),(17,53,9,'2026-07-30','2026-07-27',0,0.00,150000.00,'Kerusakan Alat (Rusak Berat)',NULL,'Ditangguhkan 7 Hari & Denda Tertunggak','Lunas','2026-07-29','2026-07-27 14:41:50','2026-07-29 10:53:59'),(18,54,7,'2026-07-30','2026-07-27',0,0.00,50000.00,'Kerusakan Alat (Rusak Ringan)',NULL,'Ditangguhkan 7 Hari & Denda Tertunggak','Lunas','2026-07-27','2026-07-27 14:43:52','2026-07-29 08:24:16'),(19,53,7,'2026-08-01','2026-07-29',0,0.00,100.00,'Kerusakan (Rusak Ringan)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-29','2026-07-29 01:43:53','2026-07-29 01:55:25'),(20,54,8,'2026-08-01','2026-07-29',0,0.00,500000.00,'Kerusakan (Rusak Berat)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-29','2026-07-29 01:45:36','2026-07-29 08:09:18'),(21,55,10,'2026-08-01','2026-07-29',0,0.00,200000.00,'Kerusakan (Rusak Berat)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-29','2026-07-29 01:56:51','2026-07-29 01:57:19'),(24,59,11,'2026-08-01','2026-07-29',0,0.00,100000.00,'Kerusakan (Rusak Ringan)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-29','2026-07-29 09:36:21','2026-07-29 10:53:40'),(25,72,10,'2026-08-01','2026-07-29',0,0.00,100000.00,'Kerusakan (Rusak Ringan)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-31','2026-07-29 11:26:25','2026-07-31 02:18:09'),(26,73,9,'2026-08-02','2026-07-30',0,0.00,1000000.00,'Kerusakan (Rusak Berat)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-31','2026-07-30 11:45:49','2026-07-31 02:18:05'),(27,74,11,'2026-08-02','2026-07-31',0,0.00,100000.00,'Kerusakan (Rusak Ringan)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-31','2026-07-31 02:19:46','2026-07-31 02:20:04'),(28,76,20,'2026-08-03','2026-07-31',0,0.00,5000000.00,'Kerusakan (Rusak Berat)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-31','2026-07-31 02:32:25','2026-07-31 02:35:07'),(29,78,3,'2026-08-03','2026-07-31',0,0.00,200000.00,'Kerusakan (Rusak Berat)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-31','2026-07-31 11:43:34','2026-07-31 11:44:34'),(30,79,3,'2026-08-03','2026-07-31',0,0.00,100000.00,'Kerusakan (Rusak Ringan)',NULL,'Ditangguhkan (Sanksi & Denda Tertunggak)','Lunas','2026-07-31','2026-07-31 11:43:47','2026-07-31 11:44:27');
/*!40000 ALTER TABLE `catatan_pelanggaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_peminjaman`
--

DROP TABLE IF EXISTS `detail_peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_peminjaman` (
  `id_detail` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) NOT NULL,
  `id_alat` int(11) NOT NULL,
  `status_detail` varchar(50) NOT NULL DEFAULT 'dipinjam',
  `tanggal_dikembalikan` date DEFAULT NULL,
  `kondisi_dikembalikan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `detail_peminjaman_id_peminjaman_foreign` (`id_peminjaman`),
  KEY `detail_peminjaman_id_alat_foreign` (`id_alat`),
  CONSTRAINT `detail_peminjaman_id_alat_foreign` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id_alat`),
  CONSTRAINT `detail_peminjaman_id_peminjaman_foreign` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_peminjaman`
--

LOCK TABLES `detail_peminjaman` WRITE;
/*!40000 ALTER TABLE `detail_peminjaman` DISABLE KEYS */;
INSERT INTO `detail_peminjaman` VALUES (1,1,5,'dikembalikan',NULL,NULL,'2026-07-17 09:05:57','2026-07-18 09:33:19'),(2,2,2,'dikembalikan',NULL,NULL,'2026-07-17 11:47:01','2026-07-18 09:36:38'),(3,3,8,'dikembalikan',NULL,NULL,'2026-07-18 09:40:06','2026-07-18 09:41:05'),(4,4,7,'dikembalikan',NULL,NULL,'2026-07-18 09:40:19','2026-07-18 09:40:58'),(5,5,6,'dikembalikan',NULL,NULL,'2026-07-18 09:40:35','2026-07-18 09:40:49'),(6,6,2,'dikembalikan',NULL,NULL,'2026-07-18 09:52:59','2026-07-19 11:41:00'),(7,7,4,'dikembalikan',NULL,NULL,'2026-07-18 11:00:22','2026-07-19 11:40:26'),(8,8,2,'dikembalikan',NULL,NULL,'2026-07-20 07:14:35','2026-07-20 07:20:43'),(9,9,5,'dikembalikan',NULL,NULL,'2026-07-20 07:23:49','2026-07-20 07:24:45'),(10,10,8,'dikembalikan',NULL,NULL,'2026-07-20 07:35:42','2026-07-20 07:52:01'),(11,11,6,'dikembalikan',NULL,NULL,'2026-07-20 07:35:50','2026-07-20 07:51:53'),(12,12,5,'dikembalikan',NULL,NULL,'2026-07-20 07:36:03','2026-07-20 07:50:44'),(13,13,8,'dikembalikan',NULL,NULL,'2026-07-20 10:08:50','2026-07-20 10:11:30'),(14,14,6,'dikembalikan',NULL,NULL,'2026-07-20 10:08:57','2026-07-20 10:11:01'),(15,15,5,'dikembalikan',NULL,NULL,'2026-07-20 10:50:11','2026-07-24 01:58:58'),(16,16,10,'dikembalikan',NULL,NULL,'2026-07-24 08:30:30','2026-07-24 08:35:37'),(17,17,9,'dikembalikan',NULL,NULL,'2026-07-24 08:33:07','2026-07-24 08:34:41'),(18,18,11,'dikembalikan',NULL,NULL,'2026-07-24 10:10:57','2026-07-24 10:12:44'),(19,19,12,'dikembalikan',NULL,NULL,'2026-07-27 00:35:55','2026-07-27 00:46:35'),(20,20,11,'dikembalikan',NULL,NULL,'2026-07-27 00:53:55','2026-07-27 00:59:06'),(21,21,8,'dikembalikan',NULL,NULL,'2026-07-27 08:10:29','2026-07-27 08:10:58'),(22,22,7,'dikembalikan',NULL,NULL,'2026-07-27 13:41:29','2026-07-27 13:41:29'),(23,23,11,'dikembalikan',NULL,NULL,'2026-07-27 14:13:55','2026-07-27 14:41:50'),(24,24,12,'dikembalikan',NULL,NULL,'2026-07-27 14:16:09','2026-07-27 14:16:09'),(25,25,9,'dikembalikan',NULL,NULL,'2026-07-27 14:43:31','2026-07-27 14:43:52'),(26,26,8,'dikembalikan',NULL,NULL,'2026-07-29 01:43:13','2026-07-29 01:43:53'),(27,27,11,'dikembalikan',NULL,NULL,'2026-07-29 01:44:52','2026-07-29 01:45:36'),(28,28,10,'dikembalikan',NULL,NULL,'2026-07-29 01:56:11','2026-07-29 01:56:51'),(29,31,9,'dikembalikan',NULL,NULL,'2026-07-29 09:29:49','2026-07-29 09:34:51'),(30,32,9,'dikembalikan',NULL,NULL,'2026-07-29 09:35:54','2026-07-29 09:36:21'),(43,37,11,'dikembalikan','2026-07-29','Rusak Ringan','2026-07-29 11:24:37','2026-07-29 11:26:25'),(44,38,9,'dikembalikan','2026-07-30','Rusak Berat','2026-07-30 11:45:30','2026-07-30 11:45:49'),(45,39,7,'dikembalikan','2026-07-31','Rusak Ringan','2026-07-30 11:47:37','2026-07-31 02:19:46'),(46,40,12,'dikembalikan','2026-07-31','Baik','2026-07-31 02:31:00','2026-07-31 02:31:35'),(47,41,12,'dikembalikan','2026-07-31','Rusak Berat','2026-07-31 02:32:07','2026-07-31 02:32:25'),(48,42,7,'dikembalikan','2026-07-31','Baik','2026-07-31 11:12:40','2026-07-31 11:19:14'),(49,43,7,'dikembalikan','2026-07-31','Rusak Berat','2026-07-31 11:20:19','2026-07-31 11:43:34'),(50,43,9,'dikembalikan','2026-07-31','Rusak Ringan','2026-07-31 11:20:19','2026-07-31 11:43:47');
/*!40000 ALTER TABLE `detail_peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_aktivitas` (
  `id_log` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) DEFAULT NULL,
  `nama_admin` varchar(255) DEFAULT NULL,
  `aksi` varchar(255) NOT NULL,
  `modul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
INSERT INTO `log_aktivitas` VALUES (1,NULL,'System / Admin','Transaksi Peminjaman','Peminjaman','Peminjaman alat musik \'Gitar Test 1\' (ALT_TEST_1) untuk anggota Test Member 1 (NIM: TEST001).','127.0.0.1','2026-07-29 09:21:13','2026-07-29 09:21:13'),(2,NULL,'System / Admin','Proses Pengembalian','Pengembalian','Pengembalian alat \'Gitar Test 1\' oleh Test Member 1. Kondisi: Baik. Total Denda: Rp100,000','127.0.0.1','2026-07-29 09:21:13','2026-07-29 09:21:13'),(3,NULL,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota Test Member 1 (Pelanggaran #22) telah dinyatakan LUNAS.','127.0.0.1','2026-07-29 09:21:13','2026-07-29 09:21:13'),(4,NULL,'System / Admin','Transaksi Peminjaman','Peminjaman','Peminjaman alat musik \'Gitar Test 1\' (ALT_TEST_1) untuk anggota Test Member 1 (NIM: TEST001).','127.0.0.1','2026-07-29 09:21:47','2026-07-29 09:21:47'),(5,NULL,'System / Admin','Proses Pengembalian','Pengembalian','Pengembalian alat \'Gitar Test 1\' oleh Test Member 1. Kondisi: Baik. Total Denda: Rp100,000','127.0.0.1','2026-07-29 09:21:47','2026-07-29 09:21:47'),(6,NULL,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota Test Member 1 (Pelanggaran #23) telah dinyatakan LUNAS.','127.0.0.1','2026-07-29 09:21:47','2026-07-29 09:21:47'),(7,1,'System / Admin','Registrasi RFID','Anggota','Registrasi kartu RFID UID A80501F5 untuk anggota gesang cesar (NIM: 221123313423).','127.0.0.1','2026-07-29 09:26:19','2026-07-29 09:26:19'),(8,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota gesang cesar (Pelanggaran #14) telah dinyatakan LUNAS.','127.0.0.1','2026-07-29 09:28:23','2026-07-29 09:28:23'),(9,1,'System / Admin','Transaksi Peminjaman','Peminjaman','Peminjaman alat musik \'gitar ukulele\' (ALT0009) untuk anggota diska (NIM: 23435686).','127.0.0.1','2026-07-29 09:29:49','2026-07-29 09:29:49'),(10,1,'System / Admin','Proses Pengembalian','Pengembalian','Pengembalian alat \'gitar ukulele\' oleh diska. Kondisi: Baik.','127.0.0.1','2026-07-29 09:34:51','2026-07-29 09:34:51'),(11,1,'System / Admin','Transaksi Peminjaman','Peminjaman','Peminjaman alat musik \'gitar ukulele\' (ALT0009) untuk anggota gesang cesar (NIM: 221123313423).','127.0.0.1','2026-07-29 09:35:54','2026-07-29 09:35:54'),(12,1,'System / Admin','Proses Pengembalian','Pengembalian','Pengembalian alat \'gitar ukulele\' oleh gesang cesar. Kondisi: Rusak Ringan. Total Denda: Rp100,000','127.0.0.1','2026-07-29 09:36:21','2026-07-29 09:36:21'),(13,NULL,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260729202607290033 (3 Alat: Gitar Yamaha (ALT_HD_01), Bass Ibanez (ALT_HD_02), Mikrofon Shure (ALT_HD_03)) berhasil dibuat untuk anggota Gesang Cesar (NIM: TEST_HD001).','127.0.0.1','2026-07-29 10:23:00','2026-07-29 10:23:00'),(14,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Gitar Yamaha\' untuk Transaksi #TRX-20260729202607290033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Aktif.','127.0.0.1','2026-07-29 10:23:01','2026-07-29 10:23:01'),(15,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Mikrofon Shure\' untuk Transaksi #TRX-20260729202607290033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Aktif.','127.0.0.1','2026-07-29 10:23:01','2026-07-29 10:23:01'),(16,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Bass Ibanez\' untuk Transaksi #TRX-20260729202607290033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Selesai.','127.0.0.1','2026-07-29 10:23:01','2026-07-29 10:23:01'),(17,NULL,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260729202607290033 (3 Alat: Gitar Yamaha (ALT_HD_01), Bass Ibanez (ALT_HD_02), Mikrofon Shure (ALT_HD_03)) berhasil dibuat untuk anggota Gesang Cesar (NIM: TEST_HD001).','127.0.0.1','2026-07-29 10:23:18','2026-07-29 10:23:18'),(18,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Gitar Yamaha\' untuk Transaksi #TRX-20260729202607290033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Aktif.','127.0.0.1','2026-07-29 10:23:18','2026-07-29 10:23:18'),(19,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Mikrofon Shure\' untuk Transaksi #TRX-20260729202607290033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Aktif.','127.0.0.1','2026-07-29 10:23:18','2026-07-29 10:23:18'),(20,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Bass Ibanez\' untuk Transaksi #TRX-20260729202607290033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Selesai.','127.0.0.1','2026-07-29 10:23:18','2026-07-29 10:23:18'),(21,NULL,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-2026072920260729033 (3 Alat: Gitar Yamaha (ALT_HD_01), Bass Ibanez (ALT_HD_02), Mikrofon Shure (ALT_HD_03)) berhasil dibuat untuk anggota Gesang Cesar (NIM: TEST_HD001).','127.0.0.1','2026-07-29 10:24:07','2026-07-29 10:24:07'),(22,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Gitar Yamaha\' untuk Transaksi #TRX-2026072920260729033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Aktif.','127.0.0.1','2026-07-29 10:24:07','2026-07-29 10:24:07'),(23,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Mikrofon Shure\' untuk Transaksi #TRX-2026072920260729033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Aktif.','127.0.0.1','2026-07-29 10:24:07','2026-07-29 10:24:07'),(24,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Bass Ibanez\' untuk Transaksi #TRX-2026072920260729033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Selesai.','127.0.0.1','2026-07-29 10:24:07','2026-07-29 10:24:07'),(25,NULL,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260729033 (3 Alat: Gitar Yamaha (ALT_HD_01), Bass Ibanez (ALT_HD_02), Mikrofon Shure (ALT_HD_03)) berhasil dibuat untuk anggota Gesang Cesar (NIM: TEST_HD001).','127.0.0.1','2026-07-29 10:24:31','2026-07-29 10:24:31'),(26,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Gitar Yamaha\' untuk Transaksi #TRX-20260729033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Aktif.','127.0.0.1','2026-07-29 10:24:31','2026-07-29 10:24:31'),(27,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Mikrofon Shure\' untuk Transaksi #TRX-20260729033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Aktif.','127.0.0.1','2026-07-29 10:24:31','2026-07-29 10:24:31'),(28,NULL,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'Bass Ibanez\' untuk Transaksi #TRX-20260729033 oleh Gesang Cesar. Kondisi: Baik. Status Transaksi: Selesai.','127.0.0.1','2026-07-29 10:24:31','2026-07-29 10:24:31'),(29,1,'System / Admin','Registrasi RFID','Anggota','Registrasi kartu RFID UID 38CE09F5 untuk anggota Firyaal naufi al kautsar (NIM: 213333123).','127.0.0.1','2026-07-29 10:28:13','2026-07-29 10:28:13'),(30,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota gesang cesar (Pelanggaran #24) telah dinyatakan LUNAS.','127.0.0.1','2026-07-29 10:53:40','2026-07-29 10:53:40'),(31,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota halimah (Pelanggaran #17) telah dinyatakan LUNAS.','127.0.0.1','2026-07-29 10:53:59','2026-07-29 10:53:59'),(32,1,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260729033 (1 Alat: drum honda (ALT0011)) berhasil dibuat untuk anggota Firyaal naufi al kautsar (NIM: 213333123).','127.0.0.1','2026-07-29 11:24:37','2026-07-29 11:24:37'),(33,1,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'drum honda\' untuk Transaksi #TRX-20260729033 oleh Firyaal naufi al kautsar. Kondisi: Rusak Ringan. Status Transaksi: Selesai.','127.0.0.1','2026-07-29 11:26:25','2026-07-29 11:26:25'),(34,1,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260730001 (1 Alat: gitar ukulele (ALT0009)) berhasil dibuat untuk anggota halimah (NIM: 32232323).','127.0.0.1','2026-07-30 11:45:30','2026-07-30 11:45:30'),(35,1,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'gitar ukulele\' untuk Transaksi #TRX-20260730001 oleh halimah. Kondisi: Rusak Berat. Status Transaksi: Selesai.','127.0.0.1','2026-07-30 11:45:49','2026-07-30 11:45:49'),(36,1,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260730002 (1 Alat: keyboard korg (GTR001)) berhasil dibuat untuk anggota gesang cesar (NIM: 221123313423).','127.0.0.1','2026-07-30 11:47:37','2026-07-30 11:47:37'),(37,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota halimah (Pelanggaran #26) telah dinyatakan LUNAS.','127.0.0.1','2026-07-31 02:18:05','2026-07-31 02:18:05'),(38,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota Firyaal naufi al kautsar (Pelanggaran #25) telah dinyatakan LUNAS.','127.0.0.1','2026-07-31 02:18:10','2026-07-31 02:18:10'),(39,1,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'keyboard korg\' untuk Transaksi #TRX-20260730002 oleh gesang cesar. Kondisi: Rusak Ringan. Status Transaksi: Selesai.','127.0.0.1','2026-07-31 02:19:46','2026-07-31 02:19:46'),(40,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota gesang cesar (Pelanggaran #27) telah dinyatakan LUNAS.','127.0.0.1','2026-07-31 02:20:04','2026-07-31 02:20:04'),(41,1,'System / Admin','Registrasi RFID','Anggota','Registrasi kartu RFID UID 60025C5C untuk anggota farhan (NIM: 221131231).','127.0.0.1','2026-07-31 02:28:57','2026-07-31 02:28:57'),(42,1,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260731001 (1 Alat: piano korg (ALT0012)) berhasil dibuat untuk anggota farhan (NIM: 221131231).','127.0.0.1','2026-07-31 02:31:00','2026-07-31 02:31:00'),(43,1,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'piano korg\' untuk Transaksi #TRX-20260731001 oleh farhan. Kondisi: Baik. Status Transaksi: Selesai.','127.0.0.1','2026-07-31 02:31:35','2026-07-31 02:31:35'),(44,1,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260731002 (1 Alat: piano korg (ALT0012)) berhasil dibuat untuk anggota farhan (NIM: 221131231).','127.0.0.1','2026-07-31 02:32:07','2026-07-31 02:32:07'),(45,1,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'piano korg\' untuk Transaksi #TRX-20260731002 oleh farhan. Kondisi: Rusak Berat. Status Transaksi: Selesai.','127.0.0.1','2026-07-31 02:32:26','2026-07-31 02:32:26'),(46,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda untuk anggota farhan (Pelanggaran #28) telah dinyatakan LUNAS.','127.0.0.1','2026-07-31 02:35:07','2026-07-31 02:35:07'),(47,1,'System / Admin','Registrasi RFID','Anggota','Registrasi kartu RFID UID 38D512F5 untuk anggota rizki raharjo (NIM: 32342342).','127.0.0.1','2026-07-31 09:10:15','2026-07-31 09:10:15'),(48,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda sebesar Rp 100.000 untuk anggota supri (Pelanggaran #6) berhasil diproses. Status: Belum Lunas (Sisa: Rp 50.000).','127.0.0.1','2026-07-31 11:08:41','2026-07-31 11:08:41'),(49,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda sebesar Rp 50.000 untuk anggota supri (Pelanggaran #6) berhasil diproses. Status: LUNAS (Total Dibayar: Rp 150.000).','127.0.0.1','2026-07-31 11:09:03','2026-07-31 11:09:03'),(50,1,'System / Admin','Registrasi RFID','Anggota','Registrasi kartu RFID UID F4604CEB untuk anggota ruli (NIM: 221132131).','127.0.0.1','2026-07-31 11:10:27','2026-07-31 11:10:27'),(51,1,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260731003 (1 Alat: keyboard korg (GTR001)) berhasil dibuat untuk anggota rizki raharjo (NIM: 32342342).','127.0.0.1','2026-07-31 11:12:40','2026-07-31 11:12:40'),(52,1,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'keyboard korg\' untuk Transaksi #TRX-20260731003 oleh rizki raharjo. Kondisi: Baik. Status Transaksi: Selesai.','127.0.0.1','2026-07-31 11:19:14','2026-07-31 11:19:14'),(53,1,'System / Admin','Transaksi Peminjaman Baru','Peminjaman','Transaksi #TRX-20260731004 (2 Alat: keyboard korg (GTR001), gitar ukulele (ALT0009)) berhasil dibuat untuk anggota rizki raharjo (NIM: 32342342).','127.0.0.1','2026-07-31 11:20:19','2026-07-31 11:20:19'),(54,1,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'keyboard korg\' untuk Transaksi #TRX-20260731004 oleh rizki raharjo. Kondisi: Rusak Berat. Status Transaksi: Aktif.','127.0.0.1','2026-07-31 11:43:34','2026-07-31 11:43:34'),(55,1,'System / Admin','Pengembalian Alat','Pengembalian','Pengembalian alat \'gitar ukulele\' untuk Transaksi #TRX-20260731004 oleh rizki raharjo. Kondisi: Rusak Ringan. Status Transaksi: Selesai.','127.0.0.1','2026-07-31 11:43:47','2026-07-31 11:43:47'),(56,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda sebesar Rp 50.000 untuk anggota rizki raharjo (Pelanggaran #30) berhasil diproses. Status: Belum Lunas (Sisa: Rp 50.000).','127.0.0.1','2026-07-31 11:44:16','2026-07-31 11:44:16'),(57,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda sebesar Rp 50.000 untuk anggota rizki raharjo (Pelanggaran #30) berhasil diproses. Status: LUNAS (Total Dibayar: Rp 100.000).','127.0.0.1','2026-07-31 11:44:27','2026-07-31 11:44:27'),(58,1,'System / Admin','Pembayaran Denda','Denda','Pembayaran denda sebesar Rp 200.000 untuk anggota rizki raharjo (Pelanggaran #29) berhasil diproses. Status: LUNAS (Total Dibayar: Rp 200.000).','127.0.0.1','2026-07-31 11:44:34','2026-07-31 11:44:34');
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_27_000001_create_admin_table',1),(5,'2026_07_27_000002_create_pendaftaran_anggota_table',1),(6,'2026_07_27_000003_create_anggota_table',1),(7,'2026_07_27_000004_create_alat_table',1),(8,'2026_07_27_000005_create_peminjaman_table',1),(9,'2026_07_27_000006_create_pengembalian_table',1),(10,'2026_07_27_000007_create_catatan_pelanggaran_table',1),(11,'2026_07_27_000008_add_deleted_at_to_anggota_table',1),(12,'2026_07_28_000001_add_uid_rfid_to_anggota_table',2),(13,'2026_07_29_000001_update_business_rules_schema',3),(14,'2026_07_29_000002_create_log_aktivitas_table',4),(15,'2026_07_29_000003_create_detail_peminjaman_table',5),(16,'2026_08_01_000001_remove_soft_deletes_from_anggota',6),(17,'2026_08_01_000002_create_pembayaran_denda_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran_denda`
--

DROP TABLE IF EXISTS `pembayaran_denda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pembayaran_denda` (
  `id_pembayaran` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pelanggaran` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `nominal_pembayaran` decimal(10,2) NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `pembayaran_denda_id_pelanggaran_foreign` (`id_pelanggaran`),
  KEY `pembayaran_denda_id_admin_foreign` (`id_admin`),
  CONSTRAINT `pembayaran_denda_id_admin_foreign` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL,
  CONSTRAINT `pembayaran_denda_id_pelanggaran_foreign` FOREIGN KEY (`id_pelanggaran`) REFERENCES `catatan_pelanggaran` (`id_pelanggaran`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran_denda`
--

LOCK TABLES `pembayaran_denda` WRITE;
/*!40000 ALTER TABLE `pembayaran_denda` DISABLE KEYS */;
INSERT INTO `pembayaran_denda` VALUES (1,6,1,'2026-07-31',100000.00,NULL,'tunai distudio','2026-07-31 11:08:41','2026-07-31 11:08:41'),(2,6,1,'2026-07-31',50000.00,NULL,'lunas di studio','2026-07-31 11:09:03','2026-07-31 11:09:03'),(3,30,1,'2026-07-31',50000.00,NULL,'tunai distudio','2026-07-31 11:44:16','2026-07-31 11:44:16'),(4,30,1,'2026-07-31',50000.00,NULL,'tunai distudio','2026-07-31 11:44:27','2026-07-31 11:44:27'),(5,29,1,'2026-07-31',200000.00,NULL,'Pembayaran Denda','2026-07-31 11:44:34','2026-07-31 11:44:34');
/*!40000 ALTER TABLE `pembayaran_denda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(50) DEFAULT NULL,
  `id_anggota` int(11) DEFAULT NULL,
  `id_alat` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `batas_kembali` date DEFAULT NULL,
  `status_pinjam` enum('dipinjam','dikembalikan') DEFAULT 'dipinjam',
  `status_transaksi` varchar(50) NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_peminjaman`),
  KEY `id_anggota` (`id_anggota`),
  KEY `id_alat` (`id_alat`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`),
  CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id_alat`),
  CONSTRAINT `peminjaman_ibfk_3` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES (1,'TRX-20260717001',4,5,1,'2026-07-17','2026-07-20','dikembalikan','Selesai','2026-07-17 09:05:57','2026-07-29 17:23:59'),(2,'TRX-20260717002',4,2,1,'2026-07-17','2026-07-20','dikembalikan','Selesai','2026-07-17 11:47:01','2026-07-29 17:23:59'),(3,'TRX-20260718003',1,8,1,'2026-07-18','2026-07-21','dikembalikan','Selesai','2026-07-18 09:40:06','2026-07-29 17:23:59'),(4,'TRX-20260718004',5,7,1,'2026-07-18','2026-07-21','dikembalikan','Selesai','2026-07-18 09:40:19','2026-07-29 17:23:59'),(5,'TRX-20260718005',8,6,1,'2026-07-18','2026-07-21','dikembalikan','Selesai','2026-07-18 09:40:35','2026-07-29 17:23:59'),(6,'TRX-20260718006',8,2,1,'2026-07-18','2026-07-15','dikembalikan','Selesai','2026-07-18 09:52:59','2026-07-29 17:23:59'),(7,'TRX-20260718007',4,4,1,'2026-07-18','2026-07-22','dikembalikan','Selesai','2026-07-18 11:00:22','2026-07-29 17:23:59'),(8,'TRX-20260720008',5,2,NULL,'2026-07-20','2026-07-21','dikembalikan','Selesai','2026-07-20 07:14:35','2026-07-29 17:23:59'),(9,'TRX-20260720009',7,5,NULL,'2026-07-20','2026-07-10','dikembalikan','Selesai','2026-07-20 07:23:49','2026-07-29 17:23:59'),(10,'TRX-20260720010',8,8,NULL,'2026-07-20','2026-07-23','dikembalikan','Selesai','2026-07-20 07:35:42','2026-07-29 17:23:59'),(11,'TRX-20260720011',4,6,NULL,'2026-07-20','2026-07-23','dikembalikan','Selesai','2026-07-20 07:35:50','2026-07-29 17:23:59'),(12,'TRX-20260720012',5,5,NULL,'2026-07-20','2026-07-23','dikembalikan','Selesai','2026-07-20 07:36:03','2026-07-29 17:23:59'),(13,'TRX-20260720013',7,8,NULL,'2026-07-20','2026-07-10','dikembalikan','Selesai','2026-07-20 10:08:50','2026-07-29 17:23:59'),(14,'TRX-20260720014',5,6,NULL,'2026-07-20','2026-06-02','dikembalikan','Selesai','2026-07-20 10:08:57','2026-07-29 17:23:59'),(15,'TRX-20260720015',1,5,NULL,'2026-07-20','2026-07-23','dikembalikan','Selesai','2026-07-20 10:50:11','2026-07-29 17:23:59'),(16,'TRX-20260724016',7,10,NULL,'2026-07-24','2026-07-27','dikembalikan','Selesai','2026-07-24 08:30:30','2026-07-29 17:23:59'),(17,'TRX-20260724017',8,9,NULL,'2026-07-24','2026-07-27','dikembalikan','Selesai','2026-07-24 08:33:07','2026-07-29 17:23:59'),(18,'TRX-20260724018',9,11,NULL,'2026-07-24','2026-07-01','dikembalikan','Selesai','2026-07-24 10:10:57','2026-07-29 17:23:59'),(19,'TRX-20260727019',11,12,1,'2026-07-27','2026-07-30','dikembalikan','Selesai','2026-07-27 00:35:55','2026-07-29 17:23:59'),(20,'TRX-20260727020',7,11,1,'2026-07-27','2026-07-30','dikembalikan','Selesai','2026-07-27 00:53:55','2026-07-29 17:23:59'),(21,'TRX-20260727021',11,8,1,'2026-07-27','2026-07-30','dikembalikan','Selesai','2026-07-27 08:10:29','2026-07-29 17:23:59'),(22,'TRX-20260727022',1,7,NULL,'2026-07-27','2026-07-30','dikembalikan','Selesai','2026-07-27 13:41:29','2026-07-29 17:23:59'),(23,'TRX-20260727023',9,11,1,'2026-07-27','2026-07-30','dikembalikan','Selesai','2026-07-27 14:13:55','2026-07-29 17:23:59'),(24,'TRX-20260727024',2,12,NULL,'2026-07-27','2026-07-30','dikembalikan','Selesai','2026-07-27 14:16:09','2026-07-29 17:23:59'),(25,'TRX-20260727025',7,9,1,'2026-07-27','2026-07-30','dikembalikan','Selesai','2026-07-27 14:43:31','2026-07-29 17:23:59'),(26,'TRX-20260729026',7,8,1,'2026-07-29','2026-08-01','dikembalikan','Selesai','2026-07-29 01:43:13','2026-07-29 17:23:59'),(27,'TRX-20260729027',8,11,1,'2026-07-29','2026-08-01','dikembalikan','Selesai','2026-07-29 01:44:52','2026-07-29 17:23:59'),(28,'TRX-20260729028',10,10,1,'2026-07-29','2026-08-01','dikembalikan','Selesai','2026-07-29 01:56:11','2026-07-29 17:23:59'),(31,'TRX-20260729031',7,9,1,'2026-07-29','2026-08-01','dikembalikan','Selesai','2026-07-29 09:29:49','2026-07-29 17:23:59'),(32,'TRX-20260729032',11,9,1,'2026-07-29','2026-08-01','dikembalikan','Selesai','2026-07-29 09:35:54','2026-07-29 17:23:59'),(37,'TRX-20260729033',10,11,1,'2026-07-29','2026-08-01','dikembalikan','Selesai','2026-07-29 11:24:37','2026-07-29 11:26:25'),(38,'TRX-20260730001',9,9,1,'2026-07-30','2026-08-02','dikembalikan','Selesai','2026-07-30 11:45:30','2026-07-30 11:45:49'),(39,'TRX-20260730002',11,7,1,'2026-07-30','2026-08-02','dikembalikan','Selesai','2026-07-30 11:47:37','2026-07-31 02:19:46'),(40,'TRX-20260731001',20,12,1,'2026-07-31','2026-08-03','dikembalikan','Selesai','2026-07-31 02:31:00','2026-07-31 02:31:35'),(41,'TRX-20260731002',20,12,1,'2026-07-31','2026-08-03','dikembalikan','Selesai','2026-07-31 02:32:07','2026-07-31 02:32:26'),(42,'TRX-20260731003',3,7,1,'2026-07-31','2026-08-03','dikembalikan','Selesai','2026-07-31 11:12:40','2026-07-31 11:19:14'),(43,'TRX-20260731004',3,7,1,'2026-07-31','2026-08-03','dikembalikan','Selesai','2026-07-31 11:20:19','2026-07-31 11:43:47');
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pendaftaran_anggota`
--

DROP TABLE IF EXISTS `pendaftaran_anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pendaftaran_anggota` (
  `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `prodi` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `foto_ktm` varchar(255) NOT NULL,
  `status_verifikasi` enum('menunggu','diterima','ditolak') DEFAULT 'menunggu',
  `catatan_admin` text DEFAULT NULL,
  `status_email` enum('pending','terkirim','gagal') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pendaftaran`),
  UNIQUE KEY `nim` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendaftaran_anggota`
--

LOCK TABLES `pendaftaran_anggota` WRITE;
/*!40000 ALTER TABLE `pendaftaran_anggota` DISABLE KEYS */;
INSERT INTO `pendaftaran_anggota` VALUES (1,'M Gesang Cesar Nugroho','221112023','Teknik Komputer','barongan','089505549520','','1783796525_YdlBdrTcmS.png','diterima',NULL,'','2026-07-11 19:02:05','2026-07-12 16:43:45'),(3,'hanafi','232323','Teknik Komputer','barongan','311130786676','','1783873778_LjQmt6Nig2.png','ditolak','rambut terlalu gondrong','','2026-07-12 16:29:38','2026-07-12 10:50:51'),(4,'anton','2432423423','Teknik Komputer','solo','075675685675','gesang@student.unu-jogja.ac.id','1783881759_RImnLvQptJ.png','ditolak','dfafafa','pending','2026-07-12 11:42:39','2026-07-12 12:48:09'),(5,'rizki raharjo','32342342','Teknik Komputer','jetis','08932472342','muhammad.rizky.kom22@student.unu-jogja.ac.id','1783883835_Ig63L7ZB1m.png','diterima',NULL,'pending','2026-07-12 12:17:15','2026-07-12 12:17:58'),(6,'Gesang Cesar','342342342','Teknik Komputer','barongan\r\nrt 01','0756756856754','m.gesang.kom22@student.unu-jogja.ac.id','1783884472_nj5weEQqJv.png','ditolak','rambutmu gondrong','pending','2026-07-12 12:27:52','2026-07-12 12:28:21'),(7,'Gesang Cesar sda','2432423423324','Teknik Komputer','barongan\r\nrt 01','08934623842','m.gesang.kom22@student.unu-jogja.ac.id','1783884600_yBKGVJEyvI.png','ditolak','gatau','pending','2026-07-12 12:30:00','2026-07-12 12:30:38'),(8,'Gesang Cesar gdsdsd','3242352452','Teknik Komputer','barongan\r\nrt 01','54746745645645','m.gesang.kom22@student.unu-jogja.ac.id','1783885247_0mwwFtx3l5.png','ditolak','gondrong','pending','2026-07-12 12:40:47','2026-07-12 12:44:24'),(9,'paijo','324234234234','Teknik Komputer','barongan\r\nrt 01','324234234234234','m.gesang.kom22@student.unu-jogja.ac.id','1783886209_jxWaycjVPY.png','ditolak','gondrong','pending','2026-07-12 12:56:49','2026-07-12 12:57:10'),(10,'supri','2214241212','Teknik Komputer','jetisss','324234234234','m.gesang.kom22@student.unu-jogja.ac.id','1783886361_FUSxFzTgyU.png','diterima',NULL,'pending','2026-07-12 12:59:21','2026-07-12 12:59:51'),(11,'bica','22111432','Teknik Komputer','gamping','08976372352','m.gesang.kom22@student.unu-jogja.ac.id','1783965321_iznM9oY4p7.png','ditolak','cantik','pending','2026-07-13 10:55:21','2026-07-13 10:56:51'),(12,'ruli','221132131','Teknik Komputer','barongan\r\nrt 01','0839236234234','m.gesang.kom22@student.unu-jogja.ac.id','1783967620_ncNuxmzEu3.png','diterima',NULL,'terkirim','2026-07-13 11:33:40','2026-07-13 11:34:21'),(13,'fahru','2212131231','Teknik Komputer','barongan\r\nrt 01','0743294362342','m.gesang.kom22@student.unu-jogja.ac.id','1783967719_NDj4VrA1nJ.png','diterima',NULL,'terkirim','2026-07-13 11:35:19','2026-07-13 11:35:38'),(14,'ivan','221212123','Teknik Komputer','barongan\r\nrt 01','045350345834','m.gesang.kom22@student.unu-jogja.ac.id','1783967796_SksagLf0cQ.jpg','ditolak','gondrong','terkirim','2026-07-13 11:36:36','2026-07-13 11:36:59'),(15,'diska','23435686','Teknik Komputer','barongan\r\nrt 01','075675685675','m.gesang.kom22@student.unu-jogja.ac.id','1783969302_T51kovxFtO.png','diterima',NULL,'terkirim','2026-07-13 12:01:42','2026-07-13 12:02:02'),(16,'dwi','222313123','Teknik Komputer','jetis','032472394234','m.gesang.kom22@student.unu-jogja.ac.id','1784031079_SHwsNk8NDZ.png','diterima',NULL,'terkirim','2026-07-14 05:11:19','2026-07-14 05:12:21'),(17,'halimah','32232323','Teknik Komputer','barongan\r\nrt 01','93232723923','m.gesang.kom22@student.unu-jogja.ac.id','1784912780_k95TVAITIb.png','diterima',NULL,'terkirim','2026-07-24 10:06:20','2026-07-24 10:07:02'),(18,'Firyaal naufi al kautsar','213333123','Farmasi','Gamping tengah ambarketawang sleman','089507723451','firyaal.naufi.far21@student.unu-jogja.ac.id','1785083527_SU2g7nnajk.JPG','diterima',NULL,'terkirim','2026-07-26 09:32:07','2026-07-26 09:34:03'),(22,'gesang cesar','221123313423','Teknik Komputer','barongan\r\nrt 01','08453934573','m.gesang.kom22@student.unu-jogja.ac.id','1785136766_O1wtA8IjR1.png','diterima',NULL,'terkirim','2026-07-27 00:19:26','2026-07-27 00:21:55'),(23,'farhan','221131231','Teknik Komputer','bantul','047234923423','251111028@student.unu-jogja.ac.id','1785489819_Z2i3eI0hzy.png','diterima',NULL,'terkirim','2026-07-31 02:23:39','2026-07-31 02:24:39');
/*!40000 ALTER TABLE `pendaftaran_anggota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengembalian`
--

DROP TABLE IF EXISTS `pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) DEFAULT NULL,
  `id_detail` bigint(20) unsigned DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `kondisi_alat` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pengembalian`),
  KEY `id_peminjaman` (`id_peminjaman`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`),
  CONSTRAINT `pengembalian_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengembalian`
--

LOCK TABLES `pengembalian` WRITE;
/*!40000 ALTER TABLE `pengembalian` DISABLE KEYS */;
INSERT INTO `pengembalian` VALUES (1,2,2,1,'2026-07-18','Baik',NULL,'2026-07-18 09:28:24','2026-07-29 17:21:07'),(2,2,2,1,'2026-07-18','Baik',NULL,'2026-07-18 09:28:36','2026-07-29 17:21:07'),(3,1,1,1,'2026-07-18','Baik',NULL,'2026-07-18 09:33:19','2026-07-29 17:21:07'),(4,2,2,1,'2026-07-18','Baik',NULL,'2026-07-18 09:36:38','2026-07-29 17:21:07'),(7,3,3,1,'2026-07-18','Hilang',NULL,'2026-07-18 09:41:05','2026-07-29 17:21:07'),(8,3,3,1,'2026-07-18','Hilang',NULL,'2026-07-18 09:41:32','2026-07-29 17:21:07'),(9,3,3,1,'2026-07-18','Hilang',NULL,'2026-07-18 09:47:25','2026-07-29 17:21:07'),(10,6,6,1,'2026-07-18','Baik',NULL,'2026-07-18 09:55:57','2026-07-29 17:21:07'),(11,6,6,1,'2026-07-18','Baik',NULL,'2026-07-18 09:57:27','2026-07-29 17:21:07'),(12,6,6,1,'2026-07-18','Baik',NULL,'2026-07-18 10:04:02','2026-07-29 17:21:07'),(13,6,6,1,'2026-07-18','Baik',NULL,'2026-07-18 10:06:18','2026-07-29 17:21:07'),(14,6,6,1,'2026-07-18','Baik',NULL,'2026-07-18 10:08:04','2026-07-29 17:21:07'),(15,6,6,1,'2026-07-18','Baik',NULL,'2026-07-18 10:19:25','2026-07-29 17:21:07'),(16,7,7,1,'2026-07-18','Baik',NULL,'2026-07-18 11:00:43','2026-07-29 17:21:07'),(17,6,6,1,'2026-07-18','Baik',NULL,'2026-07-18 11:00:58','2026-07-29 17:21:07'),(20,6,6,1,'2026-07-18','Hilang',NULL,'2026-07-18 11:01:45','2026-07-29 17:21:07'),(22,6,6,1,'2026-07-19','Baik',NULL,'2026-07-19 11:35:16','2026-07-29 17:21:07'),(23,7,7,1,'2026-07-19','Baik',NULL,'2026-07-19 11:35:24','2026-07-29 17:21:07'),(24,7,7,1,'2026-07-19','Rusak Ringan',NULL,'2026-07-19 11:37:02','2026-07-29 17:21:07'),(25,7,7,1,'2026-07-19','Rusak Ringan',NULL,'2026-07-19 11:40:26','2026-07-29 17:21:07'),(26,6,6,1,'2026-07-19','Baik',NULL,'2026-07-19 11:41:00','2026-07-29 17:21:07'),(27,8,8,1,'2026-07-20','Rusak Ringan',NULL,'2026-07-20 07:20:43','2026-07-29 17:21:07'),(28,9,9,1,'2026-07-20','Rusak Berat',NULL,'2026-07-20 07:24:45','2026-07-29 17:21:07'),(29,12,12,1,'2026-07-20','Rusak Ringan',NULL,'2026-07-20 07:36:25','2026-07-29 17:21:07'),(30,12,12,1,'2026-07-20','Rusak Ringan',NULL,'2026-07-20 07:36:39','2026-07-29 17:21:07'),(31,12,12,1,'2026-07-20','Rusak Ringan',NULL,'2026-07-20 07:50:44','2026-07-29 17:21:07'),(32,11,11,1,'2026-07-20','Rusak Berat',NULL,'2026-07-20 07:51:53','2026-07-29 17:21:07'),(33,10,10,1,'2026-07-20','Hilang',NULL,'2026-07-20 07:52:01','2026-07-29 17:21:07'),(34,14,14,1,'2026-07-20','Rusak Ringan',NULL,'2026-07-20 10:11:01','2026-07-29 17:21:07'),(35,13,13,1,'2026-07-20','Rusak Berat',NULL,'2026-07-20 10:11:30','2026-07-29 17:21:07'),(36,15,15,1,'2026-07-24','Rusak Ringan',NULL,'2026-07-24 01:58:58','2026-07-29 17:21:07'),(37,17,17,NULL,'2026-07-24','Rusak Berat','Bagian senar putus dan gitar pecah','2026-07-24 08:34:41','2026-07-29 17:21:07'),(38,16,16,NULL,'2026-07-24','Rusak Berat',NULL,'2026-07-24 08:35:37','2026-07-29 17:21:07'),(39,18,18,1,'2026-07-24','Rusak Berat',NULL,'2026-07-24 10:12:43','2026-07-29 17:21:07'),(40,19,19,1,'2026-07-27','Rusak Ringan','senar putus','2026-07-27 00:37:24','2026-07-29 17:21:07'),(41,19,19,1,'2026-07-27','Rusak Ringan','Senar putus','2026-07-27 00:39:56','2026-07-29 17:21:07'),(42,19,19,1,'2026-07-27','Rusak Berat','Senar putus','2026-07-27 00:41:37','2026-07-29 17:21:07'),(43,19,19,1,'2026-07-27','Rusak Berat','Senar putus','2026-07-27 00:45:14','2026-07-29 17:21:07'),(44,19,19,1,'2026-07-27','Hilang',NULL,'2026-07-27 00:46:11','2026-07-29 17:21:07'),(45,19,19,1,'2026-07-27','Baik',NULL,'2026-07-27 00:46:35','2026-07-29 17:21:07'),(46,20,20,1,'2026-07-27','Rusak Berat','Drum jebol','2026-07-27 00:55:15','2026-07-29 17:21:07'),(47,20,20,1,'2026-07-27','Rusak Ringan','Drum jebol','2026-07-27 00:55:43','2026-07-29 17:21:07'),(48,20,20,1,'2026-07-27','Rusak Ringan',NULL,'2026-07-27 00:58:28','2026-07-29 17:21:07'),(49,20,20,1,'2026-07-27','Baik',NULL,'2026-07-27 00:59:06','2026-07-29 17:21:07'),(50,21,21,1,'2026-07-27','Rusak Ringan','senar putus','2026-07-27 08:10:58','2026-07-29 17:21:07'),(51,22,22,NULL,'2026-07-27','Rusak Ringan','Uji coba kerusakan senar gitar','2026-07-27 13:41:29','2026-07-29 17:21:07'),(52,24,24,NULL,'2026-07-27','Rusak Ringan','Uji coba kerusakan senar gitar','2026-07-27 14:16:09','2026-07-29 17:21:07'),(53,26,26,1,'2026-07-29','Rusak Ringan',NULL,'2026-07-29 01:43:53','2026-07-29 17:21:07'),(54,27,27,1,'2026-07-29','Rusak Berat',NULL,'2026-07-29 01:45:36','2026-07-29 17:21:07'),(55,28,28,1,'2026-07-29','Rusak Berat',NULL,'2026-07-29 01:56:51','2026-07-29 17:21:07'),(58,31,29,1,'2026-07-29','Baik',NULL,'2026-07-29 09:34:51','2026-07-29 17:21:07'),(59,32,30,1,'2026-07-29','Rusak Ringan',NULL,'2026-07-29 09:36:21','2026-07-29 17:21:07'),(72,37,43,1,'2026-07-29','Rusak Ringan',NULL,'2026-07-29 11:26:25','2026-07-29 11:26:25'),(73,38,44,1,'2026-07-30','Rusak Berat',NULL,'2026-07-30 11:45:49','2026-07-30 11:45:49'),(74,39,45,1,'2026-07-31','Rusak Ringan',NULL,'2026-07-31 02:19:46','2026-07-31 02:19:46'),(75,40,46,1,'2026-07-31','Baik',NULL,'2026-07-31 02:31:35','2026-07-31 02:31:35'),(76,41,47,1,'2026-07-31','Rusak Berat',NULL,'2026-07-31 02:32:25','2026-07-31 02:32:25'),(77,42,48,1,'2026-07-31','Baik',NULL,'2026-07-31 11:19:14','2026-07-31 11:19:14'),(78,43,49,1,'2026-07-31','Rusak Berat',NULL,'2026-07-31 11:43:34','2026-07-31 11:43:34'),(79,43,50,1,'2026-07-31','Rusak Ringan',NULL,'2026-07-31 11:43:47','2026-07-31 11:43:47');
/*!40000 ALTER TABLE `pengembalian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-02  3:13:00
