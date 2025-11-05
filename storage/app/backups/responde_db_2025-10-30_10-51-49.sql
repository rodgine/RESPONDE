-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: responde_db
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
-- Table structure for table `backups`
--

DROP TABLE IF EXISTS `backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint(20) unsigned NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backups`
--

LOCK TABLES `backups` WRITE;
/*!40000 ALTER TABLE `backups` DISABLE KEYS */;
INSERT INTO `backups` VALUES (2,'responde_db_2025-10-29_22-26-04.sql','C:\\xampp\\htdocs\\responde_v2\\storage\\app/backups/responde_db_2025-10-29_22-26-04.sql',31921,NULL,'2025-10-29 14:26:05','2025-10-29 14:26:05'),(3,'responde_db_2025-10-29_22-26-10.sql','C:\\xampp\\htdocs\\responde_v2\\storage\\app/backups/responde_db_2025-10-29_22-26-10.sql',32109,NULL,'2025-10-29 14:26:11','2025-10-29 14:26:11'),(4,'responde_db_2025-10-29_22-26-21.sql','C:\\xampp\\htdocs\\responde_v2\\storage\\app/backups/responde_db_2025-10-29_22-26-21.sql',32297,NULL,'2025-10-29 14:26:21','2025-10-29 14:26:21'),(5,'responde_db_2025-10-29_22-27-48.sql','C:\\xampp\\htdocs\\responde_v2\\storage\\app/backups/responde_db_2025-10-29_22-27-48.sql',32485,NULL,'2025-10-29 14:27:49','2025-10-29 14:27:49'),(6,'responde_db_2025-10-30_09-26-38.sql','C:\\xampp\\htdocs\\responde_v2\\storage\\app/backups/responde_db_2025-10-30_09-26-38.sql',31864,NULL,'2025-10-30 01:26:39','2025-10-30 01:26:39');
/*!40000 ALTER TABLE `backups` ENABLE KEYS */;
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
  PRIMARY KEY (`key`)
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
  PRIMARY KEY (`key`)
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
-- Table structure for table `incident_reports`
--

DROP TABLE IF EXISTS `incident_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incident_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference_code` varchar(255) NOT NULL,
  `incident_type` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `landmark_photos` longtext DEFAULT NULL,
  `proof_photos` longtext DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `date_reported` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `responder_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `incident_reports_reference_code_unique` (`reference_code`),
  KEY `incident_reports_user_id_foreign` (`user_id`),
  KEY `incident_reports_responder_id_foreign` (`responder_id`),
  CONSTRAINT `incident_reports_responder_id_foreign` FOREIGN KEY (`responder_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `incident_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incident_reports`
--

LOCK TABLES `incident_reports` WRITE;
/*!40000 ALTER TABLE `incident_reports` DISABLE KEYS */;
INSERT INTO `incident_reports` VALUES (1,'MGEU8UH0','Accident','Smart, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/fHcZzdi4jm.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/izB1TtXkxT.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/qpovXFSbnK.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/bVSlU7by13.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/TT7IgbXGAi.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/nVnMZX4gYn.jpeg\\\"]\"','Resolved','2025-10-24 21:52:55',4,'2025-10-24 21:52:55','2025-10-24 22:20:46',5),(2,'E4U4LOY4','Fire','Camalaniugan-Santa Ana Road, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/SpmI899Ufb.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/L2nRIMIbUB.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/MW557RjfMJ.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/NPPnkMz2tF.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/XqOYkdNZ8X.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/e4xXwuVCua.jpeg\\\"]\"','In Progress','2025-10-24 21:59:14',4,'2025-10-24 21:59:14','2025-10-24 22:18:49',5),(3,'JXCWI2IE','Fire','Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/cwuJgBUHNI.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/enHN0ztG00.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/lIm5EjnmQ3.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/zUEaoz9ZOt.jpeg\\\"]\"','Pending','2025-10-25 22:58:06',4,'2025-10-25 22:58:06','2025-10-25 22:58:06',NULL),(4,'LXGU4HG9','Earthquake','Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/5t4yZ5vrS7.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/RiBz6HQhUX.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/xaKFmd5gU1.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/pQOefrjIrd.jpeg\\\"]\"','In Progress','2025-10-25 22:59:48',4,'2025-10-25 22:59:48','2025-10-25 23:00:24',5),(5,'GTVXB0PK','Accident','Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/nqPJx1pq7k.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/MjIxMDefeK.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/9ns6AMeEky.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/j9ERFZrIqI.jpeg\\\"]\"','In Progress','2025-10-25 23:02:22',4,'2025-10-25 23:02:22','2025-10-28 17:32:11',5),(6,'0QO9UFF2','Accident','Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/sWwRTWUkv7.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/TfEGqqYy6o.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/W0JXNkI0NE.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/VWN1VaRNMZ.jpeg\\\"]\"','Resolved','2025-10-28 01:37:31',4,'2025-10-28 01:37:31','2025-10-28 13:08:37',5),(7,'2ZY15EQZ','Fire','Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/Kfg1JPp7zg.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/yhpxifZlAf.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/u8x8qWPIlD.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/98NJxsFMic.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/cnt2T3APSj.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/un6Ytsq7dG.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/v7f4QQfL2c.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/y112YV2c3P.jpeg\\\"]\"','In Progress','2025-10-28 04:42:42',4,'2025-10-28 04:42:42','2025-10-28 17:42:24',5),(8,'VADRY83A','Earthquake','Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/MCjnJFIyA1.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/OxYJSXGYP5.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/OnIS0xbglM.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/oPk60V5C0S.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/z0TIAkhiOF.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/kluWgIQGdV.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/JmWSdwRPmm.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/JIJbNiFFSB.jpeg\\\"]\"','Resolved','2025-10-28 12:46:44',4,'2025-10-28 12:46:44','2025-10-28 13:29:39',6),(9,'9ZVALVQV','Flood','Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','\"[\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/Q7FIJ0gJqZ.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/TZGbkgDwJ5.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/guTDKcHbFS.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/landmarks\\\\\\/asXIwiyMSD.jpeg\\\"]\"','\"[\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/zYVCfgUQ4c.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/JNbb8IonAn.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/ljEVwEYY2m.jpeg\\\",\\\"storage\\\\\\/reports\\\\\\/proofs\\\\\\/P3SLwzGDrV.jpeg\\\"]\"','Resolved','2025-10-28 14:10:48',7,'2025-10-28 14:10:48','2025-10-28 14:15:14',6);
/*!40000 ALTER TABLE `incident_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incidents`
--

DROP TABLE IF EXISTS `incidents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incidents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(255) NOT NULL,
  `responder_id` bigint(20) unsigned NOT NULL,
  `details` longtext NOT NULL,
  `action_taken` longtext DEFAULT NULL,
  `victims_count` int(11) DEFAULT 0,
  `deaths_count` int(11) DEFAULT 0,
  `rescued_count` int(11) DEFAULT 0,
  `documentation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documentation`)),
  `date_resolved` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `incidents_reference_number_unique` (`reference_number`),
  KEY `incidents_responder_id_foreign` (`responder_id`),
  CONSTRAINT `incidents_responder_id_foreign` FOREIGN KEY (`responder_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incidents`
--

LOCK TABLES `incidents` WRITE;
/*!40000 ALTER TABLE `incidents` DISABLE KEYS */;
INSERT INTO `incidents` VALUES (2,'MGEU8UH0',5,'A vehicular collision involving two cars and a motorcycle occurred along the main road. Multiple passengers sustained minor injuries, and traffic was heavily disrupted. Debris and spilled fuel posed additional hazards, requiring immediate containment.','Secured the scene and assisted injured individuals.\n\nCoordinated with local authorities to redirect traffic.\n\nCleared debris and ensured safety measures for bystanders.\n\nDocumented the incident with photographs for official reporting.',5,0,5,'[\"responder_docs\\/68fc6c3eac0a6.jpeg\",\"responder_docs\\/68fc6c3eb22e6.jpeg\",\"responder_docs\\/68fc6c3eb3114.jpeg\",\"responder_docs\\/68fc6c3eb46da.jpeg\"]','2025-10-29 10:24:23','2025-10-24 22:20:46','2025-10-24 22:20:46'),(3,'0QO9UFF2',5,'Sample details about some incident reported by some responder of this system','Action once taken by responder. Action two taken by responder. Action three taken by responder. Action four taken by responder.',101,2,99,'[\"responder_docs\\/6900c055984c0.jpeg\",\"responder_docs\\/6900c055a11c0.jpeg\",\"responder_docs\\/6900c055a3ef5.jpeg\",\"responder_docs\\/6900c055a850e.jpeg\",\"responder_docs\\/6900c055ab2e7.jpeg\"]','2025-10-29 10:24:28','2025-10-28 13:08:37','2025-10-28 13:08:37'),(4,'VADRY83A',6,'This is a detail I dunno what to say','Action taken I don\'t know either',3,0,2,'[\"responder_docs\\/6900c54356c4a.jpeg\",\"responder_docs\\/6900c543637c2.jpeg\",\"responder_docs\\/6900c54364d9a.jpeg\"]','2025-10-29 10:29:01','2025-10-28 13:29:39','2025-10-28 13:29:39'),(5,'9ZVALVQV',6,'Qwertyuiopasdfghjklzxcvbnm','Whdksfnkccndkcnf',12,1,10,'[\"responder_docs\\/6900cff26f64e.jpeg\",\"responder_docs\\/6900cff27af56.jpeg\",\"responder_docs\\/6900cff27ec2e.jpeg\"]','2025-10-29 10:29:07','2025-10-28 14:15:14','2025-10-28 14:15:14');
/*!40000 ALTER TABLE `incidents` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2025_10_23_105221_create_users_table',1),(4,'2025_10_23_155833_create_incident_reports_table',1),(5,'2025_10_23_185307_add_responder_id_to_incident_reports_table',1),(6,'2025_10_23_201002_create_incidents_table',1),(7,'2025_10_24_093254_create_notifications_table',1),(8,'2025_10_24_125149_create_sessions_table',2),(9,'2025_10_29_211916_create_backups_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `responder_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_responder_id_foreign` (`responder_id`),
  CONSTRAINT `notifications_responder_id_foreign` FOREIGN KEY (`responder_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,'new_incident','New incident reported: Accident at Smart, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":1,\"incident_type\":\"Accident\",\"details\":null,\"location\":\"Smart, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Princess Mae Solancho\",\"reported_by\":\"Princess Mae Solancho\"}',1,NULL,'2025-10-24 21:52:55','2025-10-24 21:53:08'),(2,'new_incident','New incident reported: Fire at Camalaniugan-Santa Ana Road, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":2,\"incident_type\":\"Fire\",\"details\":null,\"location\":\"Camalaniugan-Santa Ana Road, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Princess Mae Solancho\",\"reported_by\":\"Princess Mae Solancho\"}',1,NULL,'2025-10-24 21:59:14','2025-10-24 21:59:28'),(3,'responder_assignment','🚨 You’ve been assigned to a new incident: Accident at Smart, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":1,\\\"incident_type\\\":\\\"Accident\\\",\\\"location\\\":\\\"Smart, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,5,'2025-10-24 22:02:02','2025-10-24 22:03:52'),(4,'responder_assignment','🚨 You’ve been assigned to a new incident: Fire at Camalaniugan-Santa Ana Road, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":2,\\\"incident_type\\\":\\\"Fire\\\",\\\"location\\\":\\\"Camalaniugan-Santa Ana Road, Gonzaga, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,5,'2025-10-24 22:07:49','2025-10-24 22:08:00'),(5,'new_incident','New incident reported: Fire at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":3,\"incident_type\":\"Fire\",\"details\":null,\"location\":\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Princess Mae Solancho\",\"reported_by\":\"Princess Mae Solancho\"}',1,NULL,'2025-10-25 22:58:06','2025-10-25 22:58:23'),(6,'new_incident','New incident reported: Earthquake at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":4,\"incident_type\":\"Earthquake\",\"details\":null,\"location\":\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Princess Mae Solancho\",\"reported_by\":\"Princess Mae Solancho\"}',1,NULL,'2025-10-25 22:59:48','2025-10-25 22:59:58'),(7,'responder_assignment','🚨 You’ve been assigned to a new incident: Earthquake at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":4,\\\"incident_type\\\":\\\"Earthquake\\\",\\\"location\\\":\\\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,5,'2025-10-25 23:00:24','2025-10-25 23:01:18'),(8,'new_incident','New incident reported: Accident at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":5,\"incident_type\":\"Accident\",\"details\":null,\"location\":\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Princess Mae Solancho\",\"reported_by\":\"Princess Mae Solancho\"}',1,NULL,'2025-10-25 23:02:22','2025-10-25 23:02:33'),(9,'new_incident','New incident reported: Accident at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":6,\"incident_type\":\"Accident\",\"details\":null,\"location\":\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Princess Mae Solancho\",\"reported_by\":\"Princess Mae Solancho\"}',1,NULL,'2025-10-28 01:37:31','2025-10-28 01:37:48'),(10,'responder_assignment','🚨 You’ve been assigned to a new incident: Accident at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":6,\\\"incident_type\\\":\\\"Accident\\\",\\\"location\\\":\\\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,5,'2025-10-28 01:39:34','2025-10-28 01:39:47'),(11,'new_incident','New incident reported: Fire at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":7,\"incident_type\":\"Fire\",\"details\":null,\"location\":\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Princess Mae Solancho\",\"reported_by\":\"Princess Mae Solancho\"}',1,NULL,'2025-10-28 04:42:42','2025-10-28 04:42:56'),(12,'new_incident','New incident reported: Earthquake at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":8,\"incident_type\":\"Earthquake\",\"details\":null,\"location\":\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Princess Mae Solancho\",\"reported_by\":\"Princess Mae Solancho\"}',1,NULL,'2025-10-28 12:46:44','2025-10-28 12:46:55'),(13,'responder_assignment','🚨 You’ve been assigned to a new incident: Earthquake at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":8,\\\"incident_type\\\":\\\"Earthquake\\\",\\\"location\\\":\\\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,6,'2025-10-28 13:27:28','2025-10-28 13:27:39'),(14,'new_incident','New incident reported: Flood at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines','{\"report_id\":9,\"incident_type\":\"Flood\",\"details\":null,\"location\":\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\",\"responder\":\"Rose Marie Bigornia\",\"reported_by\":\"Rose Marie Bigornia\"}',1,NULL,'2025-10-28 14:10:48','2025-10-28 14:11:00'),(15,'responder_assignment','🚨 You’ve been assigned to a new incident: Flood at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":9,\\\"incident_type\\\":\\\"Flood\\\",\\\"location\\\":\\\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,6,'2025-10-28 14:11:26','2025-10-28 14:13:42'),(16,'responder_assignment','🚨 You’ve been assigned to a new incident: Accident at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":5,\\\"incident_type\\\":\\\"Accident\\\",\\\"location\\\":\\\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,5,'2025-10-28 17:32:13','2025-10-28 17:43:15'),(17,'responder_assignment','🚨 You’ve been assigned to a new incident: Fire at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":7,\\\"incident_type\\\":\\\"Fire\\\",\\\"location\\\":\\\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,5,'2025-10-28 17:35:28','2025-10-28 17:43:10'),(18,'responder_assignment','🚨 You’ve been assigned to a new incident: Fire at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":7,\\\"incident_type\\\":\\\"Fire\\\",\\\"location\\\":\\\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,5,'2025-10-28 17:38:35','2025-10-28 17:43:06'),(19,'responder_assignment','🚨 You’ve been assigned to a new incident: Fire at Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines. Immediate response required.','\"{\\\"report_id\\\":7,\\\"incident_type\\\":\\\"Fire\\\",\\\"location\\\":\\\"Smart, Santa Clara, Cagayan, Cagayan Valley, 3513, Philippines\\\",\\\"status\\\":\\\"In Progress\\\",\\\"assigned_by\\\":\\\"Admin User\\\"}\"',1,5,'2025-10-28 17:42:26','2025-10-28 17:43:02');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('0DUcpg5HHozKJcU4pI3p5rss2HZ27RrahvCpeK01',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieWdFWUl1anlPN1I1Wm1UUkdnMVpKUHpBTWwzb3A0Z1FGcXQ3RGxKYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mZXRjaCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1761792698);
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
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','responder','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Administrator','admin','admin@responde.com','+639263070491','$2y$12$1zey38uGsG1oqaxdq67BJelcpK2ld7YlTJkoBlNzk7.1Oe4faJolW','admin','2025-10-24 04:50:01','2025-10-29 10:23:43'),(2,'Responder One','responder','responder@responde.com','+639657961380','$2y$12$pLDiC31kC2bPfEUBkDKQZOttA0cpfcHQg0XLvgMzf7I01kwo3HEDy','responder','2025-10-24 04:50:02','2025-10-24 04:50:02'),(3,'Regular User','user','user@responde.com','+639263070491','$2y$12$vuNX07ImbdQWryddwKG/bu3x3Rgp/9fxejrwjDmpBAiKrghZo/1mW','user','2025-10-24 04:50:02','2025-10-29 10:24:23'),(4,'Princess Mae Solancho Testing','incess_10','princessmaesolancho@gmail.com','+6391049386039','$2y$12$TLSd/.Gvze.JfU48uzUNue22cUMvnTl3Xw6mwqG/EW6ziPbreZl5y','user','2025-10-24 20:03:16','2025-10-30 00:07:50'),(5,'Nzae Ramos','nzae0101','nzaeramos@gmail.com','+639973927626','$2y$12$Zbk7ejruWzKUQQS6Ew/ZSuXeuIKCJYGmXnr/ADMQzPUCUtLccFnVe','responder','2025-10-24 22:01:13','2025-10-30 00:28:52'),(6,'Karen Joy Tajadao','karenjoy242','karenjoytajadao@gmail.com','+639919061492','$2y$12$YoL3v1nXw9e7T2pSDox8VOnFm/O2UwGmkQR/T4BBZbYTLId27XLfe','responder','2025-10-28 13:25:18','2025-10-28 13:25:18'),(7,'Rose Marie Bigornia','rosemarie123','rosemariebigornia@gmail.com','+639402958391','$2y$12$8aWG7wHOWBf4A8ztUmBsy.g4JVHCJQ.V.iYKUqN1LGHy40K3fKoJO','user','2025-10-28 13:26:02','2025-10-29 10:25:30'),(9,'New User','newuser123','newuser@gmail.com','+639104930593','$2y$12$JjCE/F0BCSVJaqfZzPFksu2/ke/BKF75MC86tUm5PaZPAL6hvCtje','user','2025-10-29 11:22:13','2025-10-29 11:22:13');
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

-- Dump completed on 2025-10-30 10:51:50
