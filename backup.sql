-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: dev_oauth
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.22.04.1

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
-- Table structure for table `app_domains`
--

DROP TABLE IF EXISTS `app_domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_domains` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_domains`
--

LOCK TABLES `app_domains` WRITE;
/*!40000 ALTER TABLE `app_domains` DISABLE KEYS */;
INSERT INTO `app_domains` VALUES (1,'moodle','https://moodle-sepay.code/enrol/sepay/ajax.php?action=webhook.lead','2025-07-16 08:23:04','2025-07-16 08:23:04'),(2,'perfex','https://perfex-crm.code/sepay_client/ipn','2025-07-16 08:31:10','2025-07-16 08:31:10'),(3,'opencart','https://opencart.code/index.php?route=extension/sepay/payment/sepay.lead','2025-07-16 08:31:32','2025-07-17 02:16:40');
/*!40000 ALTER TABLE `app_domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_oauths`
--

DROP TABLE IF EXISTS `app_oauths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_oauths` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(196) NOT NULL,
  `description` varchar(196) NOT NULL,
  `client_id` varchar(196) NOT NULL,
  `redirect_uri` varchar(196) NOT NULL,
  `state` varchar(196) NOT NULL,
  `key` varchar(196) NOT NULL,
  `access_token` tinytext,
  `code` tinytext,
  `client_secret` tinytext,
  `refresh_token` varchar(255) DEFAULT NULL,
  `expires_in` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `rawdata` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_oauths`
--

LOCK TABLES `app_oauths` WRITE;
/*!40000 ALTER TABLE `app_oauths` DISABLE KEYS */;
INSERT INTO `app_oauths` VALUES (1,'Tích hợp SePay','Tích hợp SePay','','https://oauth.igniter.code/oauth/callback','RANDOM_STATE_VALUE','se-pay',NULL,NULL,NULL,NULL,NULL,'2025-07-16 07:42:02','2025-07-16 07:42:02',NULL,NULL);
/*!40000 ALTER TABLE `app_oauths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_sepay_transactions`
--

DROP TABLE IF EXISTS `app_sepay_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_sepay_transactions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int unsigned NOT NULL,
  `bank_account_id` int unsigned NOT NULL,
  `bank_brand_name` varchar(255) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `amount_out` decimal(10,2) NOT NULL,
  `amount_in` decimal(10,2) NOT NULL,
  `accumulated` decimal(10,2) NOT NULL,
  `transaction_content` text NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `sub_account` varchar(50) DEFAULT NULL,
  `additional_data` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_sepay_transactions`
--

LOCK TABLES `app_sepay_transactions` WRITE;
/*!40000 ALTER TABLE `app_sepay_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_sepay_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_sepay_webhooks`
--

DROP TABLE IF EXISTS `app_sepay_webhooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_sepay_webhooks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `webhook_id` int unsigned NOT NULL,
  `bank_account_id` int unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `authen_type` varchar(255) NOT NULL,
  `webhook_url` varchar(255) NOT NULL,
  `is_verify_payment` int NOT NULL,
  `skip_if_no_code` int NOT NULL,
  `active` int NOT NULL,
  `only_va` int NOT NULL,
  `request_content_type` varchar(255) NOT NULL,
  `addition_data` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_sepay_webhooks`
--

LOCK TABLES `app_sepay_webhooks` WRITE;
/*!40000 ALTER TABLE `app_sepay_webhooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_sepay_webhooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025-05-28-043006','App\\Database\\Migrations\\AddTableSetting','default','App',1752651716,1),(2,'2025-05-30-024439','App\\Database\\Migrations\\CreatePayment','default','App',1752651716,1),(3,'2025-05-30-091330','App\\Database\\Migrations\\CreateTableTransaction','default','App',1752651716,1),(4,'2025-06-01-112212','App\\Database\\Migrations\\CreateTableConnection','default','App',1752651716,1),(6,'2025-07-16-080305','App\\Database\\Migrations\\CreateDomainsTable','default','App',1752653832,2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-17 11:18:43
